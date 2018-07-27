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
set_time_limit(0);
ini_set('memory_limit', '2048M');
ignore_user_abort(false);
error_reporting(E_ALL);


require('phpQuery/phpQuery.php');

$start = microtime(true);

/* ATTR TYPES */
const TYPE_LIST = 'list';
const TYPE_TEXT = 'text';
const TYPE_NUM = 'num';
const TYPE_CHECK = 'check';
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

$ch = curl_init();
/**
 * @param $url
 * @return mixed
 */
function get_xml_page($url) {
  global $ch;
  curl_setopt_array($ch, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_FOLLOWLOCATION => 1,
  ));
  $page = curl_exec($ch);
  return $page;
}


/**
 * @return array
 * Get categories links
 */
function getCatLinks($url){
  $results = phpQuery::newDocument(get_xml_page($url));
  $elements = $results->find('ul.catalog-scp li a');
  $cat_links = array();
  $i = 1;
  foreach ($elements as $element){
    // IS CATEGORY?
    $cat_id = $i++;
    $cat_title = trim(pq($element)->attr('title'));
    $link = substr(pq($element)->attr('href'), 9);
    if(
        $link == 'nabory-akkumulyatornyh-instrumentov' ||
        $link == 'mikser-dreli'
    ) continue;
    $img = substr(pq($element)->find('img')->attr('src'),0);
    if($img == '/sites/default/files/imagecache/subcat_preview/img/no-image.jpg') $img = false;
//    $is_parent = pq($element)->nextAll('._ico-arr-right')->is('._ico-arr-right');
//    $have_items = false;
//    if($is_parent) $have_items = pq($element)->nextAll('.subnav')->is('.subnav');

    $cat_links[$link] = array('id' => $cat_id, 'title' => $cat_title, 'link' => $link, 'is_parent' => false, 'img' => $img);
  }

  return $cat_links;
}
/**
 * @param $cat_links
 * @return array
 * Get categories links
 */
function getSubCatLinks($cat_links){
  foreach ($cat_links as $cat_item){
    $url = 'https://7745.by/catalog/' . $cat_item['link'];
    $results = phpQuery::newDocument(get_xml_page($url));
    $elements = $results->find('ul.catalog-scp li a');

    if($elements->html()) {
      $cat_links[$cat_item['link']]['is_parent'] = true;

    }
    $i = count($cat_links);
    foreach ($elements as $element){
      // IS CATEGORY?
      $cat_id = $i++;
      $cat_title = trim(pq($element)->attr('title'));
      $link = substr(pq($element)->attr('href'), 9);
      $img = substr(pq($element)->find('img')->attr('src'),0);
      if($img == '/sites/default/files/imagecache/subcat_preview/img/no-image.jpg') $img = false;
//    $is_parent = pq($element)->nextAll('._ico-arr-right')->is('._ico-arr-right');
//    $have_items = false;
//    if($is_parent) $have_items = pq($element)->nextAll('.subnav')->is('.subnav');

      $cat_links[$cat_item['link']]['subcats'][$link] = array('id' => $cat_id, 'title' => $cat_title, 'link' => $link, 'img' => $img);
    }
  }
  return $cat_links;
}

/**
 * @param $cat_links
 * @return array
 * Get categories info
 */
function getCatInfo($cat_links){
  foreach($cat_links as $cat_link){
    $cat_tmp = array();
    $url = 'http://www.postroyka.by/'.$cat_link['link'];
    $results_page = file_get_contents($url);
    $results = phpQuery::newDocument($results_page);
    $cat_tmp['title'] = $cat_link['cat'];
    $cat_tmp['link'] = $cat_link['link'];
    $cat_desc = $results->find('div._content > div._blk')->html();
    if(!empty($cat_desc)){
      $cat_tmp['desc'] = $cat_desc;
    }
    $cat_tmp['is_parent'] = $cat_link['is_parent'];
    $cat_info[] = $cat_tmp;
  }
  return $cat_info;
}

/**
 * @param $cat_links
 * @param $pdo
 */
function insertCatInfo($cat_links, $pdo){
  $parent_id = false;
  $i = 1;
//  pr($cat_links);
  foreach ($cat_links as $key => $val) {
//    pr($val);
    $url = 'https://7745.by/catalog/' . $val->link;
    $results = phpQuery::newDocument(get_xml_page($url));
    $data =
      [
        'name' => $val->title,
        'slug' => $val->link,
        'description' => $results->find('.seo-text.clearfix')->html() ?: '',
//        'parent_id' => $val->is_parent === true ? null : ($parent_id !== false ? $parent_id : null),
//        'image' => $val->img,
        'sort' => $i
      ];
//  pr($data);
    $qry = $pdo->prepare("
    INSERT INTO yupe_store_category ( name, slug, description, sort)
    values ( :name, :slug, :description, :sort)");
    //$duplicate = false;

    try {
      $qry->execute($data);
//      if($val->is_parent === true) $parent_id = $pdo->lastInsertId();
      $i++;
    } catch (PDOException $e) {
      echo "<p>".$e->getMessage()."</p>";
      //$duplicate = true;
    }

  }
}


/**
 * @param $cats
 * @return array
 * Get products links from price page
 */
function getProdLinks($cats){

  $links = array();
  foreach ($cats as $key => $val){
    // IS CATEGORY?
//    pr($val);
    if($val->is_parent === false){
      $url = 'https://7745.by/catalog/' . $val->link;

      $results = phpQuery::newDocument(get_xml_page($url));
      $pagination = $results->find('div.pagination .pagination-list-item > a');
      if($pagination->html()){
        //Перебираем сначала первую страницу
        $elements = $results->find('div.catalog-pp .catalog-item .item-block_photo > a');
        foreach ($elements as $el) {
          $have_variant = pq($el)->parents('.catalog-item__wrapper')[0]->find('a.item-block_kit-link')->html() ? true : false;
          $links[] =
              [
                  'link' => pq($el)->attr('href'),
                  'cat' => $val->link,
                  'cat_name' => $val->title,
                  'have_variant' => $have_variant
              ];
        }
        //Потом перебираем все остальные страницы из пагинации
        foreach($pagination as $item){
          $url = $url = 'https://7745.by' . pq($item)->attr('href');
          $results = phpQuery::newDocument(get_xml_page($url));
          $elements = $results->find('div.catalog-pp .catalog-item .item-block_photo > a');
          foreach ($elements as $el) {
            $have_variant = pq($el)->parents('.catalog-item__wrapper')[0]->find('a.item-block_kit-link')->html() ? true : false;
            $links[] =
                [
                    'link' => pq($el)->attr('href'),
                    'cat' => $val->link,
                    'cat_name' => $val->title,
                    'have_variant' => $have_variant
                ];
          }

          phpQuery::unloadDocuments($results->documentID);
        }
      } else {
        $elements = $results->find('div.catalog-pp .catalog-item .item-block_photo > a');
        foreach ($elements as $el) {
          $have_variant = pq($el)->parents('.catalog-item__wrapper')[0]->find('a.item-block_kit-link')->html() ? true : false;
          $links[] =
              [
                  'link' => pq($el)->attr('href'),
                  'cat' => $val->link,
                  'cat_name' => $val->title,
                  'have_variant' => $have_variant
              ];
        }
      }


    }
    phpQuery::unloadDocuments();
  }
  return $links;
}


/**
 * @param $links
 * @param $cats
 * @param $pdo PDO
 * @return bool
 */
function jsonProdInfo($links, $cats ,$pdo){
  $info = array();
//  pr($links);
  $i = 0;
  foreach($links as $idx => $link){

    $link = (object)$link;


    $url = 'https://7745.by' . $link->link;

    $results = phpQuery::newDocument(get_xml_page($url));
    // Переберём варианты
    if($link->have_variant){
      $variants = $results->find('div.kit__option');
      foreach ($variants as $var){
        if(pq($var)->attr('data-kit-choosed')) continue;
        $links[] = [
            'link' => $link->link . '#p' . pq($var)->attr('data-prod-ids'),
            'cat' => $link->cat,
            'cat_name' => $link->cat_name,
            'have_variant' => '0'
            ];
      }
    }

    $title = $results->find('h1.product__title')->text();
//    V Если нужно убрать скобки с кодом V
//    preg_match("/(.+)[(](.+)[)]$/u", $title, $matches);
//    $title =  isset($matches[1]) ? trim($matches[1]) : $title;

    $art = $results->find('.product-head__code')->html();
    $cat = $link->cat;
    $cat_name = $link->cat_name;
    $brand = $results->find('.prod-card-brand-name')->text();
    $price = $results->find('span.additionalPrice')->text();
    $price = $price ?:  $results->find('span.product_price-value')->text();
    $price = tofloat($price);
    $desc = $results->find('h3.product__title.product__title--h3 + p')->html() ?: $results->find('.product_wrapper  .preview-description > p')->html();

    $package = trim($results->find('.inline-lists .circle-marker-list')->html());
    $country_produce = pq($results)->find('div.manufacturer')->html();

    preg_match("/Страна производства:\\s(.+?)<br>/ui", $country_produce, $matches);
    $country_produce = $matches[1];


    $importer = pq($results)->find('div.manufacturer')->html();
    if(!preg_match("/Импортер: (.*ТД Комплект.+)<br>/ui", $importer, $matches)) continue;
    $importer = $matches[1];

    $warranty = (int)pq($results)->find('li.assurance a')->text();

    $attributes = [];
    $table = $results->find('table.features tr');

    foreach ($table as $tr) {
      if(trim(pq($tr)->find('td:nth-child(2)')->text())) {
        $attributes[] =
            [
                trim(pq($tr)->find('td:nth-child(1)')->text()) => (trim(pq($tr)->find('td:nth-child(2)')->text())),
            ];
      }
    }
    $attributes[] = ['Импортер' => $importer];
    $attributes[] = ['Страна производства' => $country_produce];
    $attributes[] = ['Гарантия' => $warranty];

//    pr($attributes);
    $images = [];
    $images_tmp = $results->find('div.product_wrapper .product_container--main-left .product__media-preview img');
    foreach ($images_tmp as $img) {
        $img_tmp = str_replace("imagecache/card_main_preview/", "", pq($img)->attr('src'));
        $pos = strpos($img_tmp, '/uploads/zoomos');
        $img_tmp = $pos ? str_replace("/sites/default/files", "", $img_tmp) : $img_tmp;
        $images[] = $img_tmp;

    }

    $slug = str2url($title);

    //$category_id = array_search($cat, array_column($cats, 'link'), true) + 417;

    $info_tmp = array('name' => $title, 'art' => $art, 'slug' => $slug, 'cat_slug' => $cat, 'cat_name' => $cat_name, 'price' => $price,
        'images' => $images, 'description' => $desc, 'attributes' => $attributes, 'brand' => $brand, 'package' => $package,
        );
//    pr($info_tmp);
    $count = $pdo->prepare("SELECT slug FROM products WHERE slug = ?");
    $count->execute([$slug]);
    $count = $count->fetchAll();
    if(count($count) == 0) {
      $data = [
          'json' => json_encode($info_tmp),
          'slug' => $slug,
      ];
//      pr($data);
      $qry = $pdo->prepare("
      INSERT INTO products (json, slug)
      values (:json, :slug)");
      //$duplicate = false;

      try {
        $qry->execute($data);
      } catch (PDOException $e) {
        echo "<p>" . $e->getMessage() . "</p>";

      }
    }

    phpQuery::unloadDocuments();
    gc_collect_cycles();
//    usleep(100);
//    if($i == 49) break;
//    $i++;
  }
//  pr($info);
  return true;
}

/**
 * @param $prod_info
 * @param $pdo PDO
 */
function insertProdInfo($prod_info, $pdo){
  $pos = 1;

  foreach ($prod_info as $el) {
//    $price = (float)str_replace(' ', '', substr($el['price'], 0, -3));
//    pr($el);
    $count = $pdo->prepare("SELECT slug FROM yupe_store_product WHERE slug = ?");
    $count->execute([$el->slug]);
    $count = $count->fetchAll();
    if(count($count) == 0) {
      pr($el->name, false);
      $img_link = 'https://7745.by' . $el->images[0];
      $cat_id = $pdo->prepare("SELECT id FROM yupe_store_category WHERE slug = ?");
      $cat_id->execute([$el->cat_slug]);
      $cat_id = $cat_id->fetchAll()[0]['id'];

      $brand = $pdo->prepare("SELECT id, sort FROM yupe_store_producer WHERE name = ?");
      $brand->execute([$el->brand]);
      $brand = $brand->fetchAll();
      if(count($brand)){
        $brand_id = $brand[0]['id'];
      } else{
        $last_brand = $pdo->prepare("SELECT id, sort FROM yupe_store_producer ORDER BY ID DESC LIMIT 1");
        $last_brand->execute();
        $last_brand = $last_brand->fetchAll();
        $data = [
            'name_short' => $el->brand,
            'name' => $el->brand,
            'slug' => str2url($el->brand),
            'status' => '1',
            'sort' => $last_brand[0]['sort'] + 1,
        ];
        $qry = $pdo->prepare("
        INSERT INTO yupe_store_producer (name_short, name, slug, status, sort)
        values (:name_short, :name, :slug, :status, :sort)");
        try{
          $qry->execute($data);
          $brand_id = $pdo->lastInsertId();
        } catch (PDOException $e) {
          echo "<p>" . $e->getMessage() . "</p>";
        }
      }
      $img = basename($img_link) == 'no-image.jpg' ? null : md5(uniqid('', true));
      $img = $img ? $img . '.' . pathinfo($img_link,PATHINFO_EXTENSION) : null;
//      pr($brand_id);
//      pr($img);
      $price = $el->price != 0 ? round($el->price / 1.015,1, PHP_ROUND_HALF_DOWN) : 0;
      $type_id = $pdo->prepare("SELECT id FROM yupe_store_type WHERE name = ?");
      $type_id->execute([$el->cat_name]);
      $type_id = $type_id->fetchAll()[0]['id'];
      $data = array(
          'name' => $el->name,
          'slug' => $el->slug,
          'category_id' => $cat_id,
          'producer_id' => $brand_id ?? '1',
          'type_id' => $type_id ?? '1',
          'price' => $price,
          'description' => $el->description,
          'image' => $img,
          'create_time' => date("Y-m-d H:i:s"),
          'update_time' => date("Y-m-d H:i:s"),
          'data' => $el->package,
          'position' => $pos);
//    pr($data);

      $qry = $pdo->prepare("
        INSERT INTO yupe_store_product (name, slug, type_id, category_id, producer_id, price, description, image, create_time, update_time, data, position)
        values (:name, :slug, :type_id, :category_id, :producer_id, :price, :description, :image, :create_time, :update_time, :data, :position)");
      //$duplicate = false;



      try {
        $qry->execute($data);
        $el->id = $pdo->lastInsertId();
        assignAttributes($el, $pdo);
        $pos++;
        if($img) copy($img_link, '../uploads/store/product/' . $img);
        if(count($el->images) > 1){
          foreach($el->images as $idx => $image){
            if($idx == 0) continue;
            $img_link = 'https://7745.by' . $image;
            $img = basename($img_link) == 'no-image.jpg' ? null : md5(uniqid('', true));
            $img .= '.' . pathinfo($img_link,PATHINFO_EXTENSION);
            $data = [
                'product_id' => $el->id,
                'name' => $img,
                'title' => $el->name,
            ];

            $qry = $pdo->prepare("
              INSERT INTO yupe_store_product_image (product_id, name, title)
              values (:product_id, :name, :title)");
            $qry->execute($data);
            copy($img_link, '../uploads/store/product/' .$img);
          }

        }
//        if($pos == 5) die();
      } catch (PDOException $e) {
        echo "<p>" . $e->getMessage() . "</p>";
        //$duplicate = true;
      }

    }
  }
}
/**
 * @param $product
 * @param $pdo
 */
function assignAttributes($product, $pdo){
//    pr($product, false);
    $type_text = 1;
    $type_list = 2;
    $type_check = 3;
    $type_num = 6;

//  pr($product, false);
    foreach ($product->attributes as $attr){
      // Убрать запятые из NUM
      //if($type == 6){
      //  $data_options[$key] = trim(str_replace(',', '.', $option));
      //}
      // Чистим от unit все значения
      //if ($unit) {
      //  foreach ($data_options as $key => $option) {
      //    $data_options[$key] = trim(str_replace($unit, '', $option));
      //  }
      //}

      $attr = (array)$attr;
      $attr_val = $attr[key($attr)];
      $qry = $pdo->prepare("SELECT * FROM yupe_store_attribute WHERE title = ?");
      $qry->execute([key($attr)]);
      $attr = $qry->fetchAll();
      $unit = $attr[0]['unit'];
//      pr($attr, false);
      $data = [
          'product_id' => $product->id,
          'attribute_id' => $attr[0]['id'],
          'string_value' => null,
          'option_value' => null,
          'number_value' => null,
          ];


      switch ($attr[0]['type']){
        case 1:
          if ($unit) {
            $attr_val = trim(str_replace($unit, '', $attr_val));
          }
          $data['string_value'] = $attr_val;

          break;
        case 2:
          //Сделать запрос к базе в option чтоб ID узнать
          if ($unit) {
            $attr_val = trim(str_replace($unit, '', $attr_val));
          }
          $qry = $pdo->prepare("SELECT * FROM yupe_store_attribute_option WHERE attribute_id = :attribute_id AND value = :value");
          $qry->execute(['attribute_id' => $attr[0]['id'], 'value' => $attr_val]);
//          pr($attr, false);
//          pr($attr_val, false);
          $opt_id = $qry->fetchAll()[0]['id'];
          $data['option_value'] = $opt_id;
          break;
        case 3:
          $check = stripos($attr_val, 'есть');
          $data['number_value'] = $check !== false ? 1 : 0;
          break;
        case 6:
//          pr($attr);
          if ($unit) {
            $attr_val = trim(str_replace($unit, '', $attr_val));
            $attr_val = trim(str_replace(',', '.', $attr_val));
          }

          if($attr[0]['title'] == 'Вес нетто' && $attr_val < 100) $attr_val = $attr_val * 1000;
          $data['number_value'] = $attr_val;
          break;
      }

//      pr($data, false);
      $qry = $pdo->prepare("
        INSERT INTO yupe_store_product_attribute_value (product_id, attribute_id, number_value, string_value, option_value)
        values (:product_id, :attribute_id, :number_value, :string_value, :option_value)");



      try {
        $qry->execute($data);
      } catch (PDOException $e) {
        echo "<p>" . $e->getMessage() . "</p>";
      }
    }
//die();


}
/**
 * Получение массива с уникальными значениями атрибутов (название и значение)
 * @param $prods
 * @return array
 */
function getAllAttributes($prods){
  $attributes = [];
  $values = [];
  $attr_temp = [];
  $attr_temp2 = [];
  $attr_temp3 = [];
  $attr_temp4 = [];

  foreach ($prods as $prod){
//    pr('<strong>' . $prod->name . '</strong>', false);
    foreach ($prod->attributes as $attr){
      $attr = (array)$attr;
      $attr_temp[$prod->cat_slug][] = key($attr);

      $attr_temp2[key($attr)][] = $attr[key($attr)];
//      pr(key($attr), false);
    }
  }
//  pr($attr_temp);

  foreach($attr_temp2 as $cat => $attrs){
    $values[$cat] = array_unique($attrs);
  }
//  pr($values);


  foreach($attr_temp as $cat => $attrs){
    $attr_temp3[$cat] = array_unique($attrs);
    foreach($attr_temp3[$cat] as $attr){
      $type = null;
      $unit = '';
      $filter = true;
      switch ($attr){
        case 'Импортер':
          $type = TYPE_LIST;
          $filter = false;
          break;
        case 'Страна производства':
          $type = TYPE_TEXT;
          $filter = false;
          break;
        case 'Гарантия':
          $type = TYPE_NUM;
          $unit = 'месяцев';
          break;
        /* shurupoverty */
        case 'Класс профессиональности':
          $type = TYPE_LIST;
          break;
        case 'Серия Heavy Duty':
          $type = TYPE_CHECK;
          break;
        case 'Напряжение аккумулятора':
          $type = TYPE_LIST;
          $unit = 'В';
          break;
        case 'Тип аккумулятора':
          $type = TYPE_LIST;
          break;
        case 'Емкость аккумулятора':
          $type = TYPE_LIST;
          $unit = 'А·ч';
          break;
        case 'Количество аккумуляторов':
          $type = TYPE_LIST;
          $unit = 'шт.';
          break;
        case 'Максимальный крутящий момент':
          $type = TYPE_NUM;
          $unit = 'Н·м';
          break;
        case 'Число оборотов холостого хода':
          $type = TYPE_TEXT;
          $unit = 'об/мин';
          $filter = false;
          break;
        case 'Число ступеней крутящего момента':
          $type = TYPE_TEXT;
          $filter = false;
          break;
        case 'Количество скоростей':
          $type = TYPE_LIST;
          break;
        case 'Диаметр шурупа':
          $type = TYPE_NUM;
          $unit = 'мм';
          $filter = false;
          break;
        case 'Диаметр сверления в дереве':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Диаметр сверления в стали':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Быстрозажимной патрон':
          $type = TYPE_CHECK;
          break;
        case 'Подсветка':
          $type = TYPE_CHECK;
          break;
        case 'Регулировка оборотов':
          $type = TYPE_CHECK;
          break;
        case 'Реверс':
          $type = TYPE_CHECK;
          break;
        case 'Вес нетто':
          $type = TYPE_NUM;
          $unit = 'г';
          break;
        case 'Упаковка':
          $type = TYPE_LIST;
          break;
        case 'Частота ударов':
          $type = TYPE_TEXT;
          $unit = 'уд/мин';
          $filter = false;
          break;
        case 'Диаметр сверления в кирпичной кладке':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ударный механизм':
          $type = TYPE_CHECK;
          break;
        case 'Мощность':
          $type = TYPE_NUM;
          $unit = 'Вт';
          break;
        case 'Внутренний шестигранник 1/4 дюйма':
          $type = TYPE_CHECK;
          break;
        case 'Номинальный крутящий момент':
          $type = TYPE_NUM;
          $unit = 'Н·м';
          $filter = false;
          break;
        case 'SDS-Plus патрон':
          $type = TYPE_CHECK;
          break;
        case 'Угловая':
          $type = TYPE_CHECK;
          break;
        case 'Зубчатый':
          $type = TYPE_CHECK;
          break;

        /* ugloshlifmashiny-bolgarki */
        case 'Питание':
          $type = TYPE_LIST;
          break;
        case 'Диаметр круга':
          $type = TYPE_LIST;
          $unit = 'мм';
          break;
        case 'Плавный пуск':
          $type = TYPE_CHECK;
          break;
        case 'Система гашения вибрации':
          $type = TYPE_CHECK;
          break;
        case '"Константная" электроника':
          $type = TYPE_CHECK;
          break;

        /* perforatory */
        case 'Патрон':
          $type = TYPE_LIST;
          break;
        case 'Максимальная энергия единичного удара':
          $type = TYPE_NUM;
          $unit = 'Дж';
          break;
        case 'Количество режимов':
          $type = TYPE_LIST;
          break;
        case 'Диаметр отверстия в бетоне':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Сверление':
          $type = TYPE_CHECK;
          break;
        case 'Сверление с ударом':
          $type = TYPE_CHECK;
          break;
        case 'Долбление':
          $type = TYPE_CHECK;
          break;
        case 'Режим "мягкого" удара':
          $type = TYPE_CHECK;
          break;

        /* dreli */

        /* frezery */
        case 'Тип':
          $type = TYPE_LIST;
          break;
        case 'Максимальный ход фрезы':
          $type = TYPE_NUM;
          $unit = 'Дж';
          break;
        case 'Подключение к пылесосу':
          $type = TYPE_CHECK;
          break;
        case '6 мм':
          $type = TYPE_CHECK;
          break;
        case '8 мм':
          $type = TYPE_CHECK;
          break;
        case '12 мм':
          $type = TYPE_CHECK;
          break;

        /* lobziki */
        case 'Глубина резания алюминия':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Глубина резания в древесине':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Глубина резания стали':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Частота ходов в холостом режиме':
          $type = TYPE_TEXT;
          $unit = 'ход/мин';
          break;
        case 'Длина хода':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Наклон подошвы 45°':
          $type = TYPE_CHECK;
          break;
        case 'Маятниковый ход':
          $type = TYPE_CHECK;
          break;
        case 'Сдув опилок':
          $type = TYPE_CHECK;
          break;
        case 'Лазер':
          $type = TYPE_CHECK;
          break;

          /* pily-torcovochnye-i-otreznye */
        case 'Диаметр диска':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Посадочный диаметр диска':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Угол наклона влево/вправо':
          $type = TYPE_LIST;
          break;
        case 'Угол поворота влево/вправо':
          $type = TYPE_LIST;
          break;
        case 'Глубина реза при 90°':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ширина реза при 0°':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Глубина реза при наклоне 45°':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ширина реза при наклоне 45°':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Глубина реза при повороте 45°':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ширина реза при повороте 45°':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Протяжка':
          $type = TYPE_CHECK;
          break;
        case 'Наклон в обе стороны':
          $type = TYPE_CHECK;
          break;

        /* pily-cirkulyarnye */
        case 'Работа с направляющей шиной':
          $type = TYPE_CHECK;
          break;
        case 'Циркулярный станок':
          $type = TYPE_CHECK;
          break;

        /* rubanki-elektricheskie */
        case 'Ширина строгания':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Глубина строгания':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;

        /* shlifovalnye-mashiny */
        case 'Диаметр шлифкруга':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Число колебаний холостого хода':
          $type = TYPE_TEXT;
          $unit = 'колебаний/мин';
          break;
        case 'Амплитуда колебаний':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Регулировка частоты колебаний':
          $type = TYPE_CHECK;
          break;
        case 'Длина рабочей поверхности':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ширина рабочей поверхности':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Длина шлифленты':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ширина шлифленты':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Скорость протяжки ленты':
          $type = TYPE_LIST;
          $unit = 'м/мин';
          break;
        case 'Длина шлифпластины':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ширина шлифпластины':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Длина':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Высота':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ширина':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Эксцентриситет':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Мощность выходная':
          $type = TYPE_NUM;
          $unit = 'Вт';
          break;
        case 'Диаметр шейки шпинделя':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;

        /* gravery */

        /* kleevye-pistolety */
        case 'Назначение':
          $type = TYPE_LIST;
          $filter = false;
          break;
        case 'Производительность подачи клея':
          $type = TYPE_NUM;
          $unit = 'г/мин';
          break;
        case 'Диаметр клеевого стержня':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Максимальная длина клеевого стержня':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Время нагрева':
          $type = TYPE_NUM;
          $unit = 'мин';
          break;
        case 'Литий-ионная технология':
          $type = TYPE_CHECK;
          $filter = false;
          break;
        case 'Длина клеевого стержня':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Автоматическое отключение':
          $type = TYPE_CHECK;
          break;

        /* feny-stroitelnye */
        case 'Регулировка температуры':
          $type = TYPE_LIST;
          break;
        case 'Максимальная рабочая температура':
          $type = TYPE_NUM;
          $unit = '°C';
          break;
        case 'Производительность на выходе':
          $type = TYPE_NUM;
          $unit = 'л/мин';
          break;
        case 'Наличие дисплея':
          $type = TYPE_CHECK;
          break;

        /* gaykoverty */
        case 'Максимальный размер гайки':
          $type = TYPE_NUM;
          break;

        /* shtroborezy-borozdodely */
        case 'Max ширина паза':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Max глубина паза':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ограничение пускового тока':
          $type = TYPE_CHECK;
          break;
        case 'Блокировка от случайного включения':
          $type = TYPE_CHECK;
          break;

        /* mnogofunkcionalnye-instrumenty-renovatory */
        case 'Угол колебаний слева/справа':
          $type = TYPE_NUM;
          $unit = '°';
          break;

        /* nozhovki-elektricheskie */
        case 'Глубина резания в древесине':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;

        /* tochila */
        case 'Ширина шлифкруга':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Посадочный диаметр':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;

        /* gvozde-i-skobozabivnye-pistolety */
        case 'Тип используемой оснастки':
          $type = TYPE_LIST;
          break;
        case 'Совместимая оснастка':
          $type = TYPE_LIST;
          break;
        case 'Максимальная длина скобы':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Максимальная длина гвоздя':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Регулировка силы удара':
          $type = TYPE_CHECK;
          break;
        case 'Работа 2 скобами':
          $type = TYPE_CHECK;
          break;

        /* kraskoraspyliteli-elektricheskie */
        case 'Производительность':
          $type = TYPE_NUM;
          $unit = 'мл/мин';
          break;
        case 'Длина шланга':
          $type = TYPE_NUM;
          $unit = 'м';
          break;
        case 'Объем бачка':
          $type = TYPE_NUM;
          $unit = 'мл';
          break;

        /* otboynye-molotki */

        /* nozhnicy-elektricheskie */
        case 'Производительность в стали до 400 Н/мм2':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Самый малый радиус':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Ширина дорожки':
          $type = TYPE_NUM;
          $unit = 'мм';
          $filter = false;
          break;
        case 'Предварительное сверление для внутренних вырезов':
          $type = TYPE_NUM;
          $unit = 'мм';
          $filter = false;
          break;

        /* polirovalnye-mashiny */
        case 'Посадка':
          $type = TYPE_LIST;
          break;

        /* shlifovateli-po-betonu */
        case 'Резьба шлифовального шпинделя':
          $type = TYPE_LIST;
          break;

        /* shlifovateli-shchetochnye */
        case 'Ширина щетки':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;

        /* radio-dlya-stroitelnyh-ploshchadok */

        /* akkumulyatory-i-zaryadnye-ustroystva */
        case 'Зарядный ток':
          $type = TYPE_LIST;
          break;
        case 'Li-Ion':
          $type = TYPE_CHECK;
          break;
        case '12 В':
          $type = TYPE_CHECK;
          break;
        case '18 В':
          $type = TYPE_CHECK;
          break;
        case '21 В':
          $type = TYPE_CHECK;
          break;
        case '14,4 В':
          $type = TYPE_CHECK;
          break;
        case '13,5 В':
          $type = TYPE_CHECK;
          break;
        case '10,8 В':
          $type = TYPE_CHECK;
          break;
        case '7,2 В':
          $type = TYPE_CHECK;
          break;
        case 'Ni-MH':
          $type = TYPE_CHECK;
          break;
        case 'Ni-Cd':
          $type = TYPE_CHECK;
          break;
        case '36 В':
          $type = TYPE_CHECK;
          break;

        /* stanki-derevoobrabatyvayushchie */

        /* plitkorezy-elektricheskie */
        case 'Напряжение питающей сети':
          $type = TYPE_LIST;
          $filter = false;
          break;
        case 'Мощность двигателя':
          $type = TYPE_NUM;
          $unit = 'Вт';
          break;
        case 'Глубина распила при 90°':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Глубина распила при наклоне в 45°':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Угол наклона':
          $type = TYPE_LIST;
          $unit = '°';
          $filter = false;
          break;
        case 'Длина реза':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Подача воды':
          $type = TYPE_CHECK;
          break;

        /* sverlilnye-stanki */
        case 'Высота подъема шпинделя':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;
        case 'Диаметр хвостовика':
          $type = TYPE_NUM;
          $unit = 'мм';
          break;

        /* pylesosy-stroitelnye */
        case 'Конструкция':
          $type = TYPE_LIST;
          $filter = false;
          break;
        case 'Тип пылесборника':
          $type = TYPE_LIST;
          $filter = false;
          break;
        case 'Объем пылесборника':
          $type = TYPE_NUM;
          $unit = 'л';
          break;
        case 'Тип уборки':
          $type = TYPE_LIST;
          break;
        case 'Класс пыли':
          $type = TYPE_LIST;
          break;
        case 'Расход воздуха':
          $type = TYPE_LIST;
          $unit = 'л/с';
          break;
        case 'Автоматика дистанционного включения':
          $type = TYPE_CHECK;
          break;
        case 'Фильтр тонкой очистки':
          $type = TYPE_CHECK;
          break;
        case 'Вес':
          $type = TYPE_NUM;
          $unit = 'кг';
          break;
        case 'Источник питания':
          $type = TYPE_LIST;
          $filter = false;
          break;
        case 'Макс. потребляемая мощность подключенного к пылесосу инструмента':
          $type = TYPE_NUM;
          $unit = 'Вт';
          break;




        default:
          $type = TYPE_TEXT;
          $unit = '';
          $filter = true;
          break;
      }

      $attr_temp4[$cat][$attr]['chars'] = ['type' => $type, 'unit' => $unit, 'filter' => $filter];
      $attr_temp4[$cat][$attr]['vals'] = $values[$attr];

    }
  }
//  pr($attr_temp4, false);
//  pr('_________________', false);
//  pr($values);
  return $attr_temp4;
}
/**
 * @param $cat_links
 * @param $pdo
 */
function insertAttributes($attrs, $pdo){

  $attr_sort = 1;
  $option_sort = 1;
  foreach ($attrs as $index => $attr) {

    foreach($attr as $name => $attr_info){

      $title = $name;
      $name = str2url($name);

      /* TYPE */
      switch($attr_info['chars']['type']){
        case TYPE_LIST:
          $type = 2;
          break;
        case TYPE_TEXT:
          $type = 1;
          break;
        case TYPE_NUM:
          $type = 6;
          break;
        case TYPE_CHECK:
          $type = 3;
          break;
        default:
          $type = 1;
          break;
      }

      $unit = $attr_info['chars']['unit'];
      $is_filter = $attr_info['chars']['filter'];

      $data_options = $attr_info['vals'];

      $data = [
        'title' => $title,
        'name' => $name,
        'type' => $type,
        'unit' => $unit,
        'is_filter' => $is_filter,
        'sort' => $attr_sort
      ];

      $attr_count = $pdo->prepare("SELECT id FROM yupe_store_attribute WHERE name = ?");
      $attr_count->execute([$name]);
      $attr_count = $attr_count->fetchAll();

      if(count($attr_count) == 0) {
        $qry = $pdo->prepare("
          INSERT INTO yupe_store_attribute (title, name, type, unit, is_filter, sort)
          values (:title, :name, :type, :unit, :is_filter, :sort)");

        try {
          $qry->execute($data);
          $attr_id = $pdo->lastInsertId();
          $attr_sort++;

        } catch (PDOException $e) {
          echo "<p>" . $e->getMessage() . "</p>";
          continue;
        }


        /* Чистим от unit все значения */
        if ($unit) {
          foreach ($data_options as $key => $option) {
            $data_options[$key] = trim(str_replace($unit, '', $option));
          }
        }

        /* Когда закончили с самими атрибутами - приступаем к опциям, если тип - выпадающий список (2 = list) */
        if(!is_null($attr_id) && $type == 2){
          foreach($data_options as $option){

            $data = [
              'attribute_id' => $attr_id,
              'position' => $option_sort,
              'value' => $option,
            ];
            $qry = $pdo->prepare("
              INSERT INTO yupe_store_attribute_option (attribute_id, position, value)
              values (:attribute_id, :position, :value)");
            try {
              $qry->execute($data);
              $option_sort++;
            } catch (PDOException $e) {
              echo "<p>" . $e->getMessage() . "</p>";
              continue;
            }
          }
        }
      }
      /* Показывать атрибут в типах товара */
      $cat = $pdo->prepare("SELECT name FROM yupe_store_category WHERE slug = ?");
      $cat->execute([$index]);
      $cat = $cat->fetchAll();
      $typename = $cat[0]['name'];

      $type = $pdo->prepare("SELECT id FROM yupe_store_type WHERE name = ?");
      $type->execute([$typename]);
      $type = $type->fetchAll();

      $data = [];
      if(count($type) == 0) {
        $qry = $pdo->prepare("INSERT INTO yupe_store_type (name) VALUES (:name)");
        $qry->execute(['name' => $typename]);
        $data['type_id'] = $pdo->lastInsertId();
      } else {
        $data['type_id'] = $type[0]['id'];
      }

      $attr_id = $attr_count[0]['id'] ?? $attr_id;
      $data['attribute_id'] = $attr_id;
      $qry = $pdo->prepare("
              INSERT INTO yupe_store_type_attribute (type_id, attribute_id)
              values (:type_id, :attribute_id)");
      $qry->execute($data);
    }
  }
}
/**
 * @param $cat_links
 * @param $pdo
 */
function insertCatLinks($cat_links, $pdo){
    $data = [
        'json' => json_encode($cat_links),
        'source' => 'cats_electron',
    ];

    $qry = $pdo->prepare("
    INSERT INTO logs (json, source)
    values (:json, :source)");

    try {
      $qry->execute($data);
    } catch (PDOException $e) {
      echo "<p>".$e->getMessage()."</p>";
    }

}
/**
 * @param $prod_links
 * @param $pdo
 */
function jsonProdLinks($prod_links, $pdo){
  foreach ($prod_links as $link){

    $data = [
        'link' => $link['link'],
        'cat' => $link['cat'],
        'cat_name' => $link['cat_name'],
        'have_variant' => $link['have_variant'],
    ];
//    pr($data,false);

    $qry = $pdo->prepare("
    INSERT INTO prod_links (link, cat, cat_name, have_variant)
    values (:link, :cat, :cat_name, :have_variant)");

    try {
      $qry->execute($data);
    } catch (PDOException $e) {
      echo "<p>".$e->getMessage()."</p>";
    }
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
      'ь' => '',    'ы' => 'y',   'ъ' => '',
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
      'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
      'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
      '°' => ''
  );
  return strtr($string, $converter);
}
function str2url($str) {
  // переводим в транслит
  $str = rus2translit($str);
  // в нижний регистр
  $str = strtolower($str);
  // заменям все ненужное нам на "-"
  $str = preg_replace('~[^a-z0-9_-]+~u', '-', $str);
  // удаляем начальные и конечные '-'
  $str = trim($str, "-");
  return $str;
}

function tofloat($num) {
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
      ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }

    return floatval(
      preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
      preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
}
function truncate($pdo, $pdo_json){
    $pdo_json->prepare("TRUNCATE products")->execute();
    $pdo_json->prepare("TRUNCATE prod_links")->execute();
//    $pdo->prepare("TRUNCATE yupe_store_category")->execute();
    $pdo->prepare("TRUNCATE yupe_store_attribute")->execute();
    $pdo->prepare("TRUNCATE yupe_store_attribute_option")->execute();
    $pdo->prepare("TRUNCATE yupe_store_product")->execute();
    $pdo->prepare("TRUNCATE yupe_store_product_attribute_value")->execute();
    $pdo->prepare("TRUNCATE yupe_store_product_image")->execute();
    $pdo->prepare("TRUNCATE yupe_store_type")->execute();
    $pdo->prepare("TRUNCATE yupe_store_type_attribute")->execute();
}
/**
 init end
 */
// Site
//DB init
$info = array();
$links = array();
$host = 'localhost';
$db = 'instr';
//$db = 'fpplstudio_instr';
$db_json = 'json';
//$db_json = 'fpplstudio_json';
$user = 'root';
//$user = 'fpplstudio_instr';
$user_json = 'root';
//$user_json = 'fpplstudio_json';
$pass = '';
//$pass = '123-qwe-asd';

$dsn = "mysql:host=$host;dbname=$db";
$dsn_json = "mysql:host=$host;dbname=$db_json";
$opt = array(
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
$pdo = new PDO($dsn, $user, $pass, $opt);
$pdo->query("SET wait_timeout=1200;");
$pdo_json = new PDO($dsn_json, $user_json, $pass, $opt);
$pdo_json->query("SET wait_timeout=1200;");
// DB init END
truncate($pdo, $pdo_json);

$url = 'https://7745.by/catalog/elektroinstrument';

$cat_links = array();
$cat_info = array();
//
//$cat_links = getCatLinks($url);
//$allcat_links = getSubCatLinks($cat_links);
//insertCatLinks($allcat_links, $pdo_json);

$cat_links = $pdo_json->query("SELECT * FROM logs WHERE `source`='cats_electron' ORDER BY id DESC LIMIT 1;")->fetchAll()[0];
$cat_links = json_decode($cat_links['json']);
//insertCatInfo($allcat_links, $pdo);

$prod_links = getProdLinks($cat_links);

jsonProdLinks($prod_links, $pdo_json);
//pr($prod_links);
//pr('/**************PROD LINKS**************/', false);
$arr = $pdo_json->query('SELECT * FROM prod_links')->fetchAll();
$prod_links = [];
foreach ($arr as $key => $link){
  $prod_links[$key]['link'] = $link['link'];
  $prod_links[$key]['cat'] = $link['cat'];
  $prod_links[$key]['cat_name'] = $link['cat_name'];
  $prod_links[$key]['have_variant'] = $link['have_variant'];
}

//pr($prod_links);
jsonProdInfo($prod_links, $cat_links, $pdo_json);
//pr('/**************PROD INFO**************/', false);




$arr = $pdo_json->query('SELECT * FROM products')->fetchAll();
$prods = [];
foreach ($arr as $prod){
  $prods[] = json_decode($prod['json']);
}
//pr($prods);
$attrs = getAllAttributes($prods);


insertAttributes($attrs, $pdo);
insertProdInfo($prods, $pdo);


curl_close($ch);
$time = microtime(true) - $start;
pr($time ,false);