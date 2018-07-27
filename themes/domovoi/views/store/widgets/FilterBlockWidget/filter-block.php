<?php
/* @var $attributes array */
?>

<?php $this->widget(
    'application.modules.store.widgets.filters.AttributesFilterWidget', [
        'attributes' => $attributes,
        'category' => $category,
    ]
) ?>

<?php if (!empty($attributes) || !empty($category)): ?>
    <div class="filter__buttons">
        <button value="Подобрать" class="btn-filter">Подобрать</button>
        <a href="/<?= Yii::app()->getRequest()->getPathInfo() ?>" class="btn-filter clear-filter">Сбросить</a>
    </div>
<?php endif; ?>