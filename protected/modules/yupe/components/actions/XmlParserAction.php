<?php
namespace yupe\components\actions;

use Sabre;
use CAction;

/**
 * Class XmlParser
 */
class XmlParserAction extends CAction
{
  /**
   * @var
   */
  public $path;

  /**
   * @throws \CHttpException
   */
  public function init()
  {

  }
  /**
   * @throws \CHttpException
   */
  public function run()
  {
    $this->parse($this->path);
  }

  public function parse($path)
  {
    $input = file_get_contents($path);
    $service = new Sabre\Xml\Service();
    $result = $service->parse($input);
    echo '<pre>';
    var_dump($result);
    echo '</pre>';
    die();
  }
}