<?php

namespace yupe\components;

/**
 * Class ReCaptcha
 * @package yupe\components
 */
class ReCaptcha extends \CApplicationComponent
{

    private static $_signupUrl = "https://www.google.com/recaptcha/admin";
    private static $_siteVerifyUrl =
        "https://www.google.com/recaptcha/api/siteverify?";
    private $_secret = '6LcKOlcUAAAAACw2C0M6i6Cd5E_ZGfup_P5Qjoz_';
    private static $_version = "php_1.0";

    /**
     * Encodes the given data into a query string format.
     *
     * @param array $data array of string elements to be encoded.
     *
     * @return string - encoded request.
     */
    private function _encodeQS($data)
    {
      $req = "";
      foreach ($data as $key => $value) {
        $req .= $key . '=' . urlencode(stripslashes($value)) . '&';
      }
      // Cut the last '&'
      $req=substr($req, 0, strlen($req)-1);
      return $req;
    }
    /**
     * Submits an HTTP GET to a reCAPTCHA server.
     *
     * @param string $path url path to recaptcha server.
     * @param array  $data array of parameters to be sent.
     *
     * @return string response
     */
    private function _submitHTTPGet($path, $data)
    {
      $req = $this->_encodeQS($data);
      $response = file_get_contents($path . $req);
      return $response;
    }
    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes
     * CAPTCHA test.
     *
     * @param string $remoteIp   IP address of end user.
     * @param string $response   response string from recaptcha verification.
     *
     * @return array
     */
    public function verifyResponse($remoteIp, $response)
    {
      // Discard empty solution submissions
      if ($response == null || strlen($response) == 0) {
        $recaptchaResponse = [];
        $recaptchaResponse['success'] = false;
        $recaptchaResponse['errorCodes'] = 'missing-input';
        return $recaptchaResponse;
      }
      $getResponse = $this->_submitHttpGet(
          self::$_siteVerifyUrl,
          array (
              'secret' => $this->_secret,
              'remoteip' => $remoteIp,
              'v' => self::$_version,
              'response' => $response
          )
      );

      $answers = json_decode($getResponse, true);

      $recaptchaResponse = [];
      if (trim($answers ['success']) == true) {
        $recaptchaResponse['success'] = true;
      } else {
        $recaptchaResponse['success'] = false;
        $recaptchaResponse['errorCodes'] = $answers ['error-codes'];
      }
      return $recaptchaResponse;
    }
}
