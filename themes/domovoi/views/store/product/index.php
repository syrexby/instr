<?php
$mainAssets = Yii::app()->getTheme()->getAssetsUrl();
//Yii::app()->getClientScript()->registerScriptFile($mainAssets . '/js/store.js');

/* @var $category StoreCategory */
$this->title = Yii::app()->getModule('store')->metaTitle ?: Yii::t('StoreModule.store', 'Catalog');
$this->description = Yii::app()->getModule('store')->metaDescription;
$this->keywords = Yii::app()->getModule('store')->metaKeyWords;

$this->breadcrumbs = [Yii::t("StoreModule.store", "Catalog")];

?>
<div class="entry__title">
    <h1 class="catalog__caption"><?= Yii::t("StoreModule.store", "Product catalog"); ?></h1>
</div>
<?php $this->widget('application.modules.store.widgets.CategoryWidget'); ?>
