<?php
/* @var $category StoreCategory */
/* @var $dataProvider CActiveDataProvider */
/* @var $searchString string */

$mainAssets = Yii::app()->getTheme()->getAssetsUrl();
//Yii::app()->getClientScript()->registerScriptFile($mainAssets . '/js/store.js');

$this->title =  Yii::t("StoreModule.store", "Search results") . ($searchString ? ': "' . $searchString . '"' : '' ) ;

$this->breadcrumbs = [Yii::t("StoreModule.store", "Search results") => ['/store/product/index']];

$this->breadcrumbs = array_merge(
    $this->breadcrumbs
);

?>
            <div class="entry__title">
                <h1 class="catalog__caption">
                  <?
                    echo Yii::t("StoreModule.store", "Search results");
                    if ($searchString) {
                        echo ' по запросу: "' . $searchString . '"';
                    }
                    ?>
                </h1>
            </div>
            <?php $this->widget(
                'zii.widgets.CListView', [
                    'dataProvider' => $dataProvider,
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
