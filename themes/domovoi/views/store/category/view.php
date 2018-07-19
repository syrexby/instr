<?php
/* @var $category StoreCategory */
/* @var $dataProvider CActiveDataProvider */

$mainAssets = Yii::app()->getTheme()->getAssetsUrl();
//Yii::app()->getClientScript()->registerScriptFile($mainAssets . '/js/store.js');

$this->title =  $category->getMetaTitle();
$this->description = $category->getMetaDescription();
$this->keywords =  $category->getMetaKeywords();
$this->canonical = $category->getMetaCanonical();

$this->breadcrumbs = [Yii::t("StoreModule.store", "Catalog") => ['/store/product/index']];

$this->breadcrumbs = array_merge(
    $this->breadcrumbs,
    $category->getBreadcrumbs(true)
);

?>
            <div class="entry__title">
                <h1 class="catalog__caption"><?= CHtml::encode($category->getTitle()); ?></h1>
            </div>
            <?php $this->widget(
                'zii.widgets.CListView', [
                    'dataProvider' => $dataProvider,
                    'afterAjaxUpdate' => 'toTopAfterAjaxUpdatePagination',
                    'itemView' => '//store/product/_item',
                    'template' => '
                        {items}
                        {pager}
                    ',
                    'summaryText' => '',
                    'enableHistory' => true,
                    'cssFile' => false,
                    'itemsCssClass' => 'catalog__list',
                    'sortableAttributes' => [
                        'sku',
                        'name',
                        'price',
                        'update_time'
                    ],
                    'htmlOptions' => [
                        'class' => 'catalog__wrap'
                    ],
                    'pagerCssClass' => 'catalog__pagination',
                    'pager' => [
                        'header' => '',
                        'prevPageLabel' => '<i class="fa fa-long-arrow-left"></i>',
                        'nextPageLabel' => '<i class="fa fa-long-arrow-right"></i>',
                        'firstPageLabel' => false,
                        'lastPageLabel' => false,
                        'htmlOptions' => [
                            'class' => 'pagination'
                        ]
                    ]
                ]
            ); ?>
