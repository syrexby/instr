<?php if(!empty($producers)):?>
<div data-collapse="persist" id="filter-producer" class="filter-block filter-dropdown">
    <div class="filter-block__title"><?= Yii::t('StoreModule.store', 'Producers');?></div>
    <div class="filter-block__body">
        <div class="filter-block__list filter-block__list_column_2">
            <?php foreach($producers as $producer):?>
                <div class="filter-block__list-item">
                    <label class="filter-block__checkbox-item">
                        <span class="i-checkbox">
                            <?= CHtml::checkBox('brand[]', Yii::app()->attributesFilter->isMainSearchParamChecked(
                                AttributeFilter::MAIN_SEARCH_PARAM_PRODUCER,
                                $producer->id,
                                Yii::app()->getRequest()
                            ), ['value' => $producer->id, 'id' => 'brand_' . $producer->id, 'class' => 'i-checkbox__real']); ?>

                            <span class="i-checkbox__faux"></span>
                        </span>
                        <span class="filter-block__checkbox-text"><?= $producer->name ?></span>
                    </label>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<?php endif;?>
