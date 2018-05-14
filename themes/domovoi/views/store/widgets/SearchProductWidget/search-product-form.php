<div class="search-block">
    <?php $form = $this->beginWidget(
        'CActiveForm',
        [
            'id' => 'catalog-search',
            'action' => ['/store/product/index'],
            'method' => 'GET',
            'htmlOptions' => ['class' => 'header__search-form']
        ]
    ) ?>
    <?= CHtml::searchField(
        AttributeFilter::MAIN_SEARCH_QUERY_NAME,
        CHtml::encode(Yii::app()->getRequest()->getQuery(AttributeFilter::MAIN_SEARCH_QUERY_NAME)),
        ['placeholder' => 'Поиск по каталогу',
            'autocomplete' => 'off']
    ); ?>
    <?= CHtml::submitButton(''); ?>
    <?php $this->endWidget(); ?>
    <ul class="quick-search">
    </ul>
</div>