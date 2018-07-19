<?php
/**
 * Theme bootstrap file.
 */
Yii::app()->getClientScript()->registerScript('baseUrl', "var baseUrl = '" . Yii::app()->getBaseUrl(true) . "';", CClientScript::POS_HEAD);

// Favicon
Yii::app()->getClientScript()->registerLinkTag('shortcut icon', null, '/favicon-16x16.png');

Yii::import('themes.'.Yii::app()->theme->name.'.DomovoiThemeEvents');
