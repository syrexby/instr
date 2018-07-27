<?php $filter = Yii::app()->getComponent('attributesFilter'); ?>
<div class="filter-block">
    <div class="filter-block__list-item">
        <label class="filter-block__checkbox-item">
            <span class="i-checkbox">
                <?= CHtml::checkBox(
                    $filter->getFieldName($attribute),
                    $filter->isFieldChecked($attribute, 1),
                    ['value' => 1, 'class' => 'i-checkbox__real']
                ) ?>
                <span class="i-checkbox__faux"></span>
            </span>
            <span class="filter-block__checkbox-text"><?= $attribute->title ?></span>
        </label>
    </div>
</div>