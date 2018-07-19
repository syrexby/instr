<?php
/* @var $model Page */
/* @var $this PageController */

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
    <div class = "catalog__list">
      <?php $this->widget('application.modules.store.widgets.ProductsFromCategoryWidget', ['slug' => 'sale']); ?>
    </div>
</div>
