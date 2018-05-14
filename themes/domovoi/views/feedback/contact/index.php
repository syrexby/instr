<?php
/**
 * @var CActiveForm $form
 */
Yii::app()->getClientScript()->registerScriptFile('https://www.google.com/recaptcha/api.js', CClientScript::POS_HEAD, ['async' => true, 'defer' => true]);

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

    <div class="contacts__map">
        <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A0e8a53629727bfe3fd42f750716a849b2ac4b1b9262ff9cebd4d5fc5c7115e5a&amp;width=700&amp;height=500&amp;lang=ru_RU&amp;scroll=true"></script>
    </div>
    <p class="small">ООО "КОРЕАЛ ТРЕЙД"<br> УНП 192701933. 224000, г. Дрогичин, ул. Шевченко 93А, р/с BY40 МТВК 3012 0001 0933 0007 2877 в ОАО "Белаграпромбанк" БИК МТВКВY22, г. Минск, ул. Толстого, 10.</p>
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