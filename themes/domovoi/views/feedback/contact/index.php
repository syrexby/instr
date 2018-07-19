<?php
/**
 * @var CActiveForm $form
 */
Yii::app()->getClientScript()->registerScriptFile('https://www.google.com/recaptcha/api.js', CClientScript::POS_HEAD, ['async' => true, 'defer' => true]);
Yii::app()->getClientScript()->registerScriptFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU&onload=init', CClientScript::POS_HEAD);


$this->title = Yii::t('FeedbackModule.feedback', 'Contacts');
$this->breadcrumbs = [Yii::t('FeedbackModule.feedback', 'Contacts')];
Yii::import('application.modules.feedback.FeedbackModule');
Yii::import('application.modules.install.InstallModule');
?>

<h1 class="catalog__caption"><?= Yii::t('FeedbackModule.feedback', 'Contacts'); ?></h1>

<div class="main__catalog grid contacts">
    <?php $this->widget('yupe\widgets\YFlashMessages'); ?>
    <p>Мы находимся по адресу:<br> Брестская обл., г. Дрогичин, ул. Шевченко 93А.</p>
    <p>Телефон магазина:<br> <a href="tel:+375164441063">8 (01644) 4-10-63</a></p>
    <p>Режим работы магазина:<br>
        Пн - Пт: 9:00 - 18:00<br>
        Обед: 14:00 - 15:00<br>
        Сб, Вс: 9:00 - 14:00<br>
    </p>

    <p>Мы на карте:</p>

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
    <p class="small">ООО "КОРЕАЛ ТРЕЙД"<br>
        225611, Брестская обл., г. Дрогичин, ул. Шевченко, дом 93а<br>
        УНП 290924160, ОКПО 302944991000, Р/с BY95 АКВВ 3012 1233 5001 2130 0000 в ЦБУ 108  в г. Дрогичин филиала №802
        ОАО «АСБ Беларусбанк» г. Барановичи, ул. Штоккерау, 8а БИК AKBBBY21802. Свидетельство от 20.09.2013 Дрогичинским РИК.
    </p>
    <h5> Обратная связь</h5>
    <p>Через эту форму Вы можете обратиться к нам, если Вам есть что сказать.</p>
    <div class="contacts__form-wrap">
        <?php $form = $this->beginWidget('CActiveForm', [
            'id' => 'feedback-form',
            'action' => Yii::app()->createUrl('/feedback/contact/index'),
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'afterValidate' => 'js:formCheck',
                'hideErrorMessage'=>true,

            ),
            'htmlOptions'=>array(
                'class'=>'contacts__form',
            ),
        ]); ?>
          <div class="form__errors">
            <?= $form->errorSummary($model); ?></div>
        <?= $form->hiddenField($model, 'theme', ['value' => 'Запрос обратной связи']); ?>
          <div class="contacts__input">
            <?= $form->textField($model, 'name', ['placeholder' => 'Ваше имя: *']); ?>
            <?= $form->error($model, 'name') ?>
          </div>
          <div class="contacts__input">
            <?= $form->textField($model, 'email', ['placeholder' => 'E-mail: *']); ?>
            <?= $form->error($model, 'email') ?>
          </div>
          <div class="contacts__input">
            <?= $form->textField($model, 'phone', ['placeholder' => 'Телефон:']); ?>
            <?= $form->error($model, 'phone') ?>
          </div>
          <div class="contacts__textarea">
            <?= $form->textArea($model, 'text', ['placeholder' => 'Сообщение:']); ?>
            <?= $form->error($model, 'text') ?>
          </div>

        <div id="recaptcha" class="g-recaptcha"
             data-sitekey="6LcKOlcUAAAAANoSfUKW5K1wtZaWpOEi5WQdUDNb"
             data-badge="inline"
             data-callback="formSend"
             data-size="invisible">
        </div>
        <div class="contacts__form-bot">
            <button class="contacts__btn">
                Задать вопрос
            </button>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>