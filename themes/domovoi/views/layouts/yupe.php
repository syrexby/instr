<!DOCTYPE html>
<html lang="<?= Yii::app()->getLanguage(); ?>">

<head>
    <?php
    \yupe\components\TemplateEvent::fire(DomovoiThemeEvents::HEAD_START);

    Yii::app()->getClientScript()->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css');
    Yii::app()->getClientScript()->registerCssFile($this->mainAssets . '/styles/style.css');

    Yii::app()->getClientScript()->registerCoreScript('jquery');


    ?>
    <title><?= $this->title;?></title>
    <meta name="description" content="<?= $this->description;?>" />
    <meta name="keywords" content="<?= $this->keywords;?>" />
    <?php if ($this->canonical): ?>
        <link rel="canonical" href="<?= $this->canonical ?>" />
    <?php endif; ?>
    <meta name="format-detection" content="telephone=no">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#c44343">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <script type="text/javascript">
        var yupeTokenName = '<?= Yii::app()->getRequest()->csrfTokenName;?>';
        var yupeToken = '<?= Yii::app()->getRequest()->getCsrfToken();?>';
        var yupeCartDeleteProductUrl = '<?= Yii::app()->createUrl('/cart/cart/delete/')?>';
        var yupeCartUpdateUrl = '<?= Yii::app()->createUrl('/cart/cart/update/')?>';
        var yupeCartWidgetUrl = '<?= Yii::app()->createUrl('/cart/cart/widget/')?>';
    </script>
    <?php \yupe\components\TemplateEvent::fire(DomovoiThemeEvents::HEAD_END);?>
</head>

<body>
<?php \yupe\components\TemplateEvent::fire(DomovoiThemeEvents::BODY_START);?>
<div class = "wrapper">

    <header class = "header"><!--header-->
        <div class="container clearfix"><!--container-->
            <div class = "header__flex-container"><!--header-flex-->
                <div class = "menu__logo">
                    <a href = "/">
                        <img src = "<?= $this->mainAssets?>/img/logo.png" alt = "...">
                    </a>
                </div>
                <?php $this->widget('application.modules.store.widgets.SearchProductWidget'); ?>

                <div class="header__info">
                    <div class="header__info-map">
                        <img src="<?= $this->mainAssets?>/img/map-marker.png" alt="">
                        <a href="/contacts">Карта проезда</a>
                    </div>
                    <div class = "header__phones">
                        <img src="<?= $this->mainAssets?>/img/phone-marker.png" width="15" height="15" alt="">
                        <a href="tel:+375164441063">8 (01644) 4-10-63</a>
                    </div>
                    <div class = "header__phones">
                        <img src="<?= $this->mainAssets?>/img/phone-marker.png" width="15" height="15" alt="">
                        <a href="tel:+375293298038">8 (029) 329-80-38</a>
                    </div>

                </div>
                <div class="header__contacts">
                    <div class="header__info-time">
                        <img src="<?= $this->mainAssets?>/img/time-marker.png" width="15" height="15" alt="">
                        <div>
                            <p>Пн - Пт: 9:00 - 18:00</p>
                            <p>Обед: 14:00 - 15:00</p>
                            <p>Сб, Вс: 9:00 - 14:00</p>
                        </div>
                    </div>
<!--                    <label class="modal__btn" for="modal-1">Заказать обратный звонок</label>-->
                    <!-- Модальное окно -->
<!--                    <div class="modal">-->
<!--                        <input class="modal-open" id="modal-1" type="checkbox" hidden>-->
<!--                        <div class="modal-wrap" aria-hidden="true" role="dialog">-->
<!--                            <label class="modal-overlay" for="modal-1"></label>-->
<!--                            <div class="modal-dialog">-->
<!--                                <label class="btn-close" for="modal-1" aria-hidden="true">×</label>-->
<!--                                <div class="modal-body">-->
<!--                                    <p class = "body-caption">Заказать обратный звонок</p>-->
<!--                                    <p class = "body-text">Оставьте ваши контактные данные и мы свяжемся с вами в ближайшее время.</p>-->
<!--                                    <form class = modal__form>-->
<!--                                        <input type="text" placeholder="Ваше имя">-->
<!--                                        <input type="text" placeholder="Ваш телефон">-->
<!--                                        <button class = "modal__btn-pimary">-->
<!--                                            <a href="">Перезвоните мне</a>-->
<!--                                        </button>-->
<!--                                    </form>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                  <!-- Модальное окно -->
                </div>
            </div><!--header-flex-->
        </div><!--container-->
    </header><!--header-->



    <section class = "menu"><!--menu-->
        <div class="container clearfix"><!--container-->
            <div class = "menu__flex-container"><!--menu-flex-->
                <div class="menu__catalog">
                    <a href="/store"><img src="<?= $this->mainAssets?>/img/menu-lines.png" alt=""></a>
                    <a href="/store">Каталог товаров</a>
                </div>
                <nav class = "menu__nav">
                    <ul>
                        <li><a href="/">Главная</a></li>
                        <li><a href="/dostavka-i-oplata">Оплата и доставка</a></li>
                        <li><a href="/sale">Акции</a></li>
<!--                        <li><a href="">Скидочная система</a></li>-->
<!--                        <li><a href="">Статьи</a></li>-->
                        <li><a href="/o-nas">О нас</a></li>
                        <li><a href="/contacts">Контакты</a></li>
                    </ul>
                </nav>
            </div><!--menu-flex-->
        </div><!--container-->
    </section><!--menu-->

    <section class = "catalog"><!--catalog-->
        <div class = "catalog__back"><!--catalog-back-->
            <div class="container clearfix"><!--container-->
                <div class = "catalog__flex-container"><!--catalog-flex-->
                    <div class="catalog__navigation">
                      <?php $this->widget('application.modules.store.widgets.CategoryWidget', ['view' => 'menu-category-widget']); ?>
                        <!--<div class = "catalog__button-download">
                            <button class = "catalog__button-price">
                                <a href = "">
                                    <img src = "<?/*= $this->mainAssets*/?>/img/price-list.png" alt="">
                                    <p>Скачать прайс</p>
                                </a>
                            </button>
                        </div>-->
                    </div>
                    <div class = "catalog__box">
                        <div class="breadcrumbs">
                          <?php $this->widget(
                              'zii.widgets.CBreadcrumbs',
                              [
                                  'links' => $this->breadcrumbs,
                                  'tagName' => 'ul',
                                  'separator' => '>',
                                  'homeLink' => '<li><a href="/">' . Yii::t('Yii.zii', 'Home') . '</a>',
                                  'activeLinkTemplate' => '<li><a href="{url}">{label}</a>',
                                  'inactiveLinkTemplate' => '<li><a>{label}</a>',
                                  'htmlOptions' => [],
                                  'encodeLabel' => false
                              ]
                          );?>
                        </div>
                        <?= $content ?>

                    </div>
                </div><!--catalog-flex-->
            </div><!--container-->
        </div><!--catalog-back-->
    </section><!--catalog-->
    <footer class = "footer"><!--footer-->
        <div class="container clearfix"><!--container-->
            <div class = "footer__flex-container"><!--footer-flex-->
                <div class = "footer__logo">
                    <a class="footer__img" href="/"></a>
                    <p>&#169; Домовой плюс, 2017</p>
                </div>
                <div class="footer__information">
                    <div class="footer__nav">
                        <nav class="footer__nav-ul">
                            <ul>
                                <li><a href="/dostavka-i-oplata">Оплата и доставка</a></li>
                                <li><a href="/sale">Акции</a></li>
<!--                                <li><a href="">Скидочная система</a></li>-->
                            </ul>
                        </nav>
                        <nav class="footer__nav-ul">
                            <ul>
<!--                                <li><a href="">Статьи</a></li>-->
                                <li><a href="/o-nas">О нас</a></li>
                                <li><a href="/contacts">Контакты</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="footer__info">
                        <div class="footer__info-map">
                            <img src="<?= $this->mainAssets?>/img/map-marker-white.png" width="15" height="15" alt="">
                            <p>Брестская обл.,<br> г. Дрогичин, ул. Шевченко 93А</p>
                        </div>
                        <div class="footer__info-time">
                            <img src="<?= $this->mainAssets?>/img/time-marker-white.png" width="15" height="15" alt="">
                            <div>
                                <p>Пн - Пт: 9:00 - 18:00</p>
                                <p>Обед: 14:00 - 15:00</p>
                                <p>Сб, Вс: 9:00 - 14:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="footer__contacts">
                        <div class = "footer__phones">
                            <img src="<?= $this->mainAssets?>/img/phone-marker.png" width="15" height="15" alt="">
                            <a href="tel:+375164441063">8 (01644) 4-10-63</a>
                        </div>
                        <div class = "footer__phones">
                            <img src="<?= $this->mainAssets?>/img/phone-marker.png" width="15" height="15" alt="">
                            <a href="tel:+375293298038">8 (029) 329-80-38</a>
                        </div>
                    </div>
                </div>
            </div><!--footer-flex-->
            <div class="legal-info">ООО "КОРЕАЛ ТРЕЙД", УНП 192701933. 224000, г. Дрогичин, ул. Шевченко 93А, р/с BY40 МТВК 3012 0001 0933 0007 2877 в ОАО "Белаграпромбанк" БИК МТВКВY22, г. Минск, ул. Толстого, 10.</div>
        </div> <!--container-->
    </footer><!--footer-->
<?php \yupe\components\TemplateEvent::fire(DomovoiThemeEvents::BODY_END);?>
<div class='notifications top-right' id="notifications"></div>
<?php
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/main.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js', CClientScript::POS_END);
?>
</body>
</html>