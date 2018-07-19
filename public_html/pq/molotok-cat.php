<?php
/**
 * Created by PhpStorm.
 * User: Yuri
 * Date: 07.07.2016
 * Time: 13:52
 */
/**
   init
 */

ini_set('max_execution_time', '0');
error_reporting(E_ALL);

require('phpQuery/phpQuery.php');

/**
 * @param $str
 * @param bool $die
 * @param string $name
 * @param bool $error
 */
function pr($str, $die = true, $name = '', $error=false){
  echo "<pre>";
  if ($name)
    if ($error)
      echo "<span style='color: red'>".$name.":</span>";
    else
      echo "<span style='color: green'>".$name.":</span>";
  var_dump($str);
  echo "</pre>";
  if ($die) die();
}

/**
 * @param $url
 * @return mixed
 */
function get_xml_page($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $page = curl_exec($ch);
  curl_close($ch);
  return $page;
}




/**
 * @return array
 * Get products links from price page
 */
function getProdLinks($cat_slug){

  $links = array();
    // IS CATEGORY?
      $url = 'http://xn--j1abciba1a.xn--90ais/catalog/' . $cat_slug;
      $results_page = file_get_contents($url);
      $results = phpQuery::newDocument($results_page);
      $elements = $results->find('.product-name a');
      foreach ($elements as $el) {
        $links[] = pq($el)->attr('href');
      }

  return $links;
}


/**
 * @param $links
 * @param $cats
 * @param $pdo PDO
 * @return array
 */
function getProdInfo($links, $pdo){
  $info = array();
  foreach($links as $link){
    $info_tmp = array();
    $url = 'http://xn--j1abciba1a.xn--90ais'.$link;
    $results_page = file_get_contents($url);
    $results = phpQuery::newDocument($results_page);

    $title = $results->find('.product-single-details h3');
    $title = $title->text();
//    pr($title);
    $price = $results->find('.product-single-details .item_current_price');
    $price = (float)strstr($price->text(), ' ', true);
    $desc_temp = $results->find('.bx_item_description p')->wrapInner("<div class='desc-top'></div>");
    $desc = $desc_temp->html();
//    pr($desc, false);
//    $desc_temp = $results->find('div.tab div.features_blk')->wrapInner("<div class='features'></div>");
//    $desc .= $desc_temp->html();
//    pr($desc, false);
//    $desc_temp = $results->find('div.tab div.text_blk:last')->wrapInner("<div class='desc-bottom'></div>");
//    $desc .= $desc_temp->html();
    $img_link = $results->find('.product-single-details img');
    $img_link = $img_link->attr('data-zoom-image');
    $slug = substr(strrchr(substr($link, 0, -1), "/"), 1);
//    $category_id = array_search($cat, array_column($cats, 'link'), true) + 417;
//    $count = $pdo->prepare("SELECT slug FROM yupe_store_product WHERE slug = ?");
//    $count->execute([$slug]);
//    $count = $count->fetchAll();
//    pr($cat, false);
//    pr(array_column($cats, 'link'));
//    pr($title, false);
//    pr($cat, false);
//    pr($price, false);
//    pr($desc, false);
//    pr($img_link);
//      if(count($count) == 0) {
          $info_tmp = array('name' => $title, 'slug' => $slug, /*'category_id' => $category_id, 'cat_title' => $cat,*/ 'price' => $price, 'image' => $img_link, 'description' => $desc);
//      $price = trim(explode(',', pq($price)->text())[0]);
          $info[] = $info_tmp;
//      }

//    pr($info);
    }
  return $info;
}

/**
 * @param $prod_info
 * @param $pdo PDO
 */
function insertProdInfo($prod_info, $cat_id, $pdo){
  $pos = 845;
  foreach ($prod_info as $el) {
//  pr($el);
//    $price = (float)str_replace(' ', '', substr($el['price'], 0, -3));
//    pr($price);
    $img_link = 'http://xn--j1abciba1a.xn--90ais' . $el['image'];
    $data = array(
      'name' => $el['name'],
      'slug' => str2url($el['name']),
      'category_id' => $cat_id,
      'price' => $el['price'] * 1.23,
      'description' => $el['description'],
      'image' => substr(strrchr($img_link, "/"), 1),
      'create_time' => date("Y-m-d H:i:s"),
      'update_time' => date("Y-m-d H:i:s"),
      'position' => $pos);
//  pr($data);
    $qry = $pdo->prepare("
    INSERT INTO yupe_store_product (name, slug, category_id, price, description, image, create_time, update_time, position)
    values (:name, :slug, :category_id, :price, :description, :image, :create_time, :update_time, :position)");
    //$duplicate = false;

    try {
      $qry->execute($data);
      $pos++;
    } catch (PDOException $e) {
      echo "<p>".$e->getMessage()."</p>";
      //$duplicate = true;
    }

    copy($img_link, '../uploads/store/product'.strrchr($img_link, "/"));
//    die();
  }
}

function rus2translit($string) {
  $converter = array(
      'а' => 'a',   'б' => 'b',   'в' => 'v',
      'г' => 'g',   'д' => 'd',   'е' => 'e',
      'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
      'и' => 'i',   'й' => 'y',   'к' => 'k',
      'л' => 'l',   'м' => 'm',   'н' => 'n',
      'о' => 'o',   'п' => 'p',   'р' => 'r',
      'с' => 's',   'т' => 't',   'у' => 'u',
      'ф' => 'f',   'х' => 'h',   'ц' => 'c',
      'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
      'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
      'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

      'А' => 'A',   'Б' => 'B',   'В' => 'V',
      'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
      'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
      'И' => 'I',   'Й' => 'Y',   'К' => 'K',
      'Л' => 'L',   'М' => 'M',   'Н' => 'N',
      'О' => 'O',   'П' => 'P',   'Р' => 'R',
      'С' => 'S',   'Т' => 'T',   'У' => 'U',
      'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
      'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
      'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
      'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
  );
  return strtr($string, $converter);
}
function str2url($str) {
  // переводим в транслит
  $str = rus2translit($str);
  // в нижний регистр
  $str = strtolower($str);
  // заменям все ненужное нам на "-"
  $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
  // удаляем начальные и конечные '-'
  $str = trim($str, "-");
  return $str;
}
/**
 init end
 */
// Site
//DB init
$info = array();
$links = array();
$host = 'localhost';
$db = 'fpplstudio_dmv';
$user = 'fpplstudio_dmv';
$pass = '123-qwe-asd';

$dsn = "mysql:host=$host;dbname=$db";
$opt = array(
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
$pdo = new PDO($dsn, $user, $pass, $opt);
$pdo->query("SET wait_timeout=1200;");
// DB init END

//$url = 'http://www.postroyka.by/catalog/';
//$results_page = file_get_contents($url);
//$results = phpQuery::newDocument($results_page);
//$elements = $results->find('div._content div.categories_blk div.item');
//$urls = array();
//$cat_links = array();
//$cat_info = array();
//
ini_set('max_execution_time', 10000);
//$cat_links = getCatLinks();
//pr('/**************CAT LINKS**************/', false);
//pr($cat_links, false);
//$cat_info = getCatInfo($cat_links);
//pr('/**************CAT INFO**************/', false);
//pr($cat_info,false);
//insertCatInfo($cat_info, $pdo);
/*************/


$prod_links = getProdLinks('stremianki/?sort=name&show=1000');

//pr('/**************PROD LINKS**************/', false);
//pr($prod_links, false);

$prod_info = getProdInfo($prod_links, $pdo);
pr('/**************PROD INFO**************/', false);
pr($prod_info, false);

insertProdInfo($prod_info, 488, $pdo);

//pr($pdo->query('SELECT * FROM ds_store_product')->fetchAll());
