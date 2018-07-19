<?php
/* @var $model Page */
/* @var $this PageController */

Yii::app()->getClientScript()->registerScriptFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU&onload=init', CClientScript::POS_HEAD);

if ($model->layout) {
    $this->layout = "//layouts/{$model->layout}";
}

$this->title = $model->meta_title ?: $model->title;
$this->breadcrumbs = $this->getBreadCrumbs();
$this->description = $model->meta_description ?: Yii::app()->getModule('yupe')->siteDescription;
$this->keywords = $model->meta_keywords ?: Yii::app()->getModule('yupe')->siteKeyWords;
?>
<h1 class="catalog__caption"><?= $model->title; ?></h1>
<div class="page-content">
    <?= $model->body; ?>

    <script type="text/javascript">
        function init (ymaps) {
            var myMap = new ymaps.Map('map', {
                    zoom: 14,
                    center: [52.19575058108168,25.146798591181785],
                    controls: []
                }),
                myPlacemark = new ymaps.Placemark([52.199660093368294,25.14682004885389], {
                    hintContent: 'Домовой',
                    balloonContent: 'Брестская обл., г. Дрогичин, ул. Шевченко 93А.<br>' +
                    'Телефон магазина:<br>' +
                    '8 (01644) 4-10-63<br>' +
                    'Режим работы магазина:<br>' +
                    'Пн - Пт: 9:00 - 18:00<br>' +
                    'Обед: 14:00 - 15:00<br>' +
                    'Сб, Вс: 9:00 - 14:00'
                }, {
                    // Опции.
                    // Необходимо указать данный тип макета.
                    iconLayout: 'default#image',
                    // Своё изображение иконки метки.
                    iconImageHref: '/map-mark.png',
                    // Размеры метки.
                    iconImageSize: [50, 70],
                    // Смещение левого верхнего угла иконки относительно
                    // её "ножки" (точки привязки).
                    iconImageOffset: [-25, -80]
                });
            myMap.geoObjects.add(myPlacemark);
        }
    </script>
    <div class="contacts__map">
        <div class="loader">
            <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
        </div>
        <div id="map" onload="mapOnLoad()" width="100%" height="100%" frameborder="0"></div>
    </div>
</div>
