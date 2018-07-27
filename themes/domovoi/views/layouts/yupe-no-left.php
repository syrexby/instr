<!DOCTYPE html>
<html lang="<?= Yii::app()->getLanguage(); ?>">

<head>
    <?php
    \yupe\components\TemplateEvent::fire(DomovoiThemeEvents::HEAD_START);

    Yii::app()->getClientScript()->registerCssFile('https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic');
    Yii::app()->getClientScript()->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css');
    Yii::app()->getClientScript()->registerCssFile($this->mainAssets . '/fonts/flaticon/Flaticon.css');
    Yii::app()->getClientScript()->registerCssFile($this->mainAssets . '/styles/style.css');

    Yii::app()->getClientScript()->registerCoreScript('jquery');

    $isFrontpage = false;
    if (Yii::app()->controller->id == 'site' && Yii::app()->controller->action->id == 'index') {
      $isFrontpage = true;
    }

    ?>
    <title>
      <? if($isFrontpage) {
            echo $this->title;
         }
         else {
            echo str_word_count($this->title,0 , 'АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя') >= 5 ?
                $this->title :
                $this->title . ' | «Домовой» — стройматериалы с доставкой по Дрогичину и Брестской обл.';
         } ?>
    </title>
    <meta charset="utf-8">
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
    <meta name="yandex-verification" content="bc8ea9a1dbe313dc" />

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter49172998 = new Ya.Metrika2({
                        id:49172998,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/tag.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks2");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/49172998" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <meta name="google-site-verification" content="Vhboj2yi26ITse-jprNDOuBLxPSOeZlC333rmsBXicw" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-62136900-8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-62136900-8');
    </script>

    <?php \yupe\components\TemplateEvent::fire(DomovoiThemeEvents::HEAD_END);?>
</head>

<body>
<?php \yupe\components\TemplateEvent::fire(DomovoiThemeEvents::BODY_START);?>
<div class="overlay"></div>
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
<!--                    <div class = "header__phones">-->
<!--                        <img src="--><?//= $this->mainAssets?><!--/img/phone-marker.png" width="15" height="15" alt="">-->
<!--                        <a href="tel:+375293298038">8 (029) 329-80-38</a>-->
<!--                    </div>-->

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
                    <!-- Модальное окно -->
<!--                    <label class="modal__btn" for="modal-1">Заказать обратный звонок</label>-->
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
                <a href="/store" class="menu__catalog">
                    <div><img src="<?= $this->mainAssets?>/img/menu-lines.png" alt=""></div>
                    <div>Каталог товаров</div>
                </a>
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
                <div class="navbar__user">
                    <?php if (Yii::app()->getUser()->getIsGuest()): ?>
                        <a href="<?= Yii::app()->createUrl('/user/account/login') ?>" class="btn btn_login-button">
                            <div class="glyph-icon flaticon-005-ecommerce-2"></div><?= Yii::t('UserModule.user', 'Login'); ?>
                        </a>
                    <?php else: ?>
                        <div class="toolbar-button toolbar-button_dropdown">
                            <span class="toolbar-button__label">
                                <div class="glyph-icon flaticon-005-ecommerce-2"></div> Мой кабинет
                            </span>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu__header">Ваш профиль - <?= Yii::app()->getUser()->getProfile()->getFullName() ?></div>
                                <div class="dropdown-menu__item">
                                    <div class="dropdown-menu__link">
                                        <a href="<?= Yii::app()->createUrl('/order/user/index') ?>">Мои заказы</a>
                                    </div>
                                </div>
                                <div class="dropdown-menu__item">
                                    <div class="dropdown-menu__link">
                                        <a href="<?= Yii::app()->createUrl('/user/profile/profile') ?>">
                                            <?= Yii::t('UserModule.user', 'My profile') ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="dropdown-menu__separator"></div>
                                <div class="dropdown-menu__item">
                                    <div class="dropdown-menu__link dropdown-menu__link_exit">
                                        <a href="<?= Yii::app()->createUrl('/user/account/logout') ?>">
                                            <?= Yii::t('UserModule.user', 'Logout'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="shopping-cart-widget" id="shopping-cart-widget">
                    <?php $this->widget('application.modules.cart.widgets.ShoppingCartWidget'); ?>
                </div>
            </div><!--menu-flex-->
        </div><!--container-->
    </section><!--menu-->

    <section class = "catalog"><!--catalog-->
        <div class = "catalog__back"><!--catalog-back-->
            <div class="container clearfix"><!--container-->
                <div class = "catalog__flex-container"><!--catalog-flex-->
                    <div class = "catalog__box no-left-bar">
                        <div class="breadcrumbs">
                          <?php $this->widget(
                              'zii.widgets.CBreadcrumbs',
                              [
                                  'links' => $this->breadcrumbs,
                                  'tagName' => 'ul',
                                  'separator' => '>',
                                  'homeLink' => '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                                    <a href="/" itemprop="item" title="'. Yii::t('YupeModule.yupe', 'To Homepage') .'"><span itemprop="name">
                                                    ' . Yii::t('Yii.zii', 'Home')
                                                    . '<span></a>',
                                  'activeLinkTemplate' => '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                                            <a itemprop="item" href="{url}" title="К разделу «{label}»">
                                                                <span itemprop="name">{label}</span>
                                                            </a>',
                                  'inactiveLinkTemplate' => '<li><a>{label}</a>',
                                  'htmlOptions' => ['itemscope itemtype' => 'http://schema.org/BreadcrumbList'],
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
                    <p>&#169; Домовой плюс, 2009 - <?= date('Y')?></p>
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
<!--                        <div class = "footer__phones">-->
<!--                            <img src="--><?//= $this->mainAssets?><!--/img/phone-marker.png" width="15" height="15" alt="">-->
<!--                            <a href="tel:+375293298038">8 (029) 329-80-38</a>-->
<!--                        </div>-->
                    </div>
                </div>
            </div><!--footer-flex-->
            <div class="legal-info">ООО "КОРЕАЛ ТРЕЙД", УНП 192701933. 225611, г. Дрогичин, ул. Шевченко 93А, <br/>
                Свидетельство No 695, от 20.06.2000 г. Выдано: Минским городским исполнительным комитетом <br/>
                Зарегистрирован в Торговом реестре Республики Беларусь 29.08.2016 №349556</div>

        </div> <!--container-->
    </footer><!--footer-->
</div>
<?php \yupe\components\TemplateEvent::fire(DomovoiThemeEvents::BODY_END);?>
<div class='notifications top-right' id="notifications"></div>
<?php
Yii::app()->getClientScript()->registerScriptFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/product-gallery.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/index.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/store.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/jquery.collapse.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/jquery.collapse_storage.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/tabs.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/main.js', CClientScript::POS_END);
?>
</body>
</html>