<?php
/* @var $model Page */
/* @var $this PageController */

Yii::app()->getClientScript()->registerCssFile($this->mainAssets . '/styles/owl.carousel.min.css');
Yii::app()->getClientScript()->registerCssFile($this->mainAssets . '/styles/owl.theme.default.min.css');
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/owl.carousel.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('mapInit', /** @lang JavaScript */
    '
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            loop:false,
            margin:10,
            nav:true,
            items:2,
            slideBy: 2
            
        })
    });
        ', CClientScript::POS_END);
if ($model->layout) {
  $this->layout = "//layouts/{$model->layout}";
}

$this->title = $model->meta_title ?: $model->title;
$this->breadcrumbs = $this->getBreadCrumbs();
$this->description = $model->meta_description ?: Yii::app()->getModule('yupe')->siteDescription;
$this->keywords = $model->meta_keywords ?: Yii::app()->getModule('yupe')->siteKeyWords;

//Yii::app()->getClientScript()->registerCssFile($this->mainAssets . '/styles/style.css');
?>
<h1 class="catalog__caption"><?= $model->title; ?></h1>
<div class="page-content about-block">
    <p>Мы находимся по адресу:<br> <b>г. Дрогичин, ул. Шевченко 93А</b></p>
    <p>
        <div class="catalog-list">
            В нашем магазине вы, с комфортом и отличным обслуживанием, сможете приобрести
            <ul>
                <li>строительные материалы,</li>
                <li>крепеж всех видов,</li>
                <li>товары для дома и дачи,</li>
                <li>кухонную технику (духовки, плиты, вытяжки),</li>
                <li>сантехнику,</li>
                <li>плитку керамическую,</li>
                <li>электро и бензоинструмент,</li>
                <li>электрику,</li>
                <li>двери входные и межкомнатные,</li>
                <li>металлопрокат,</li>
                <li>плитку тротуарную и бордюры,</li>
                <li>блоки газосиликатные и кирпич.</li>
            </ul>
            а также еще очень много всего, что может Вам понадобиться для поддержания красоты
            и уюта в вашем доме или квартире.
        </div>
    </p>
    <p><b>Добро пожаловать!</b></p>
    <div>
        Реквизиты:
        <div class="pl-1 mt-1">
            <b>ООО «КореалТрейд»</b><br>
            225611, Брестская обл., г. Дрогичин, ул. Шевченко, дом 93а, тел.+375 1644 41064, факс +375 1644 41063.
            УНП 290924160, ОКПО 302944991000, Р/с BY95 АКВВ 3012 1233 5001 2130 0000 в ЦБУ 108  в г. Дрогичин филиала №802
            ОАО «АСБ Беларусбанк» г. Барановичи, ул. Штоккерау, 8а БИК AKBBBY21802. Свидетельство от 20.09.2013 Дрогичинским РИК.<br>
            <b>E-mail: korenyuk@tut.by</b>
        </div>

    </div>
    <?
        $dir    = Yii::getPathOfAlias('webroot') . $this->mainAssets . '/img/photo';
        $photos = array_diff(scandir($dir), array('..', '.', 'thumb'));
//        var_dump($photos)
    ?>
    <div class="owl-carousel about-carousel">
        <? foreach ($photos as $photo): ?>
            <div class="item">
                <a class="item__img" href="<?= $this->mainAssets ?>/img/photo/<?= $photo ?>" data-fancybox="gallery">
                    <img src="<?= $this->mainAssets ?>/img/photo/thumb/<?= $photo ?>"
                         alt="<?= $photo ?>"
                         title="<?= $photo ?>">
                </a>
            </div>
        <? endforeach; ?>
    </div>

</div>
