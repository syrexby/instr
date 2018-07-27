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
                        <div class="loader list-loader">
                            <div></div><div></div><div></div><div></div><div></div>
                        </div>
                        {summary}
                        {sorter}
                        {pager}
                        {items}
                        {pager}
                    ',
                    'summaryText' => 'Товары {start}-{end} из {count}',
                    'enableHistory' => true,
                    'cssFile' => false,
                    'itemsCssClass' => 'catalog__list',
                    'sorterHeader' => 'Сортировать',
                    'sortableAttributes' => [
                        'popularnye',
                        'deshevie',
                        'dorogie',
                        'nazvanie',
                    ],
                    'htmlOptions' => [
                        'class' => 'catalog__wrap'
                    ],
                    'pagerCssClass' => 'catalog__pagination',
                    'pager' => [
                        'header' => '',
                        'prevPageLabel' => '',
                        'nextPageLabel' => '',
                        'firstPageLabel' => '',
                        'lastPageLabel' => '',
                        'maxButtonCount'=> '4',
                        'htmlOptions' => [
                            'class' => 'pagination'
                        ]
                    ]
                ]
            ); ?>
