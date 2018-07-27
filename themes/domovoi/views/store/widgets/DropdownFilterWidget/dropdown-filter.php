<?php $filter = Yii::app()->getComponent('attributesFilter'); ?>
<div data-collapse="persist" id="filter-radio" class="filter-block filter-dropdown">
    <div class="filter-block__title"><?= $attribute->unit ? $attribute->title . ', ' . $attribute->unit : $attribute->title ?></div>
    <div class="filter-block__body">
        <div class="filter-block__list">
            <?php foreach ($attribute->options as $option): ?>
                <div class="filter-block__list-item">
                    <label class="filter-block__checkbox-item">
                        <span class="i-checkbox">
                            <?= CHtml::checkBox(
                                $filter->getDropdownOptionName($option),
                                $filter->getIsDropdownOptionChecked($option, $option->id),
                                [
                                    'value' => $option->id,
                                    'class' => 'i-checkbox__real',
                                    'id' => 'filter-attribute-' . $option->id
                                ]) ?>
                            <span class="i-checkbox__faux"></span>
                        </span>
                        <span class="filter-block__checkbox-text"><?= $option->value ?></span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
