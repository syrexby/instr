<?php
/**
 * @var Product $data
 */
$sale = in_array(416, $data->getCategoriesId());
$new = in_array(3, $data->getCategoriesId());

$label = false;
$label_sale = false;
$label_new = false;

if($sale || $new) {
  $label = true;
  $label_sale = $sale ?: false;
  $label_new = $new ?: false;
}
?>
<div class="catalog__list__item">
  <? if ($label) : ?>
      <div class="labels">
        <? if ($label_sale) : ?>
            <div class="label label-hit">Акция</div>
        <? endif; ?>
        <? if ($label_new) : ?>
            <div class="label label-new">Новинка</div>
        <? endif; ?>
      </div>
  <? endif; ?>

    <a href="<?= ProductHelper::getUrl($data); ?>" class = "catalog__img">
        <img src="<?= StoreImage::product($data, 190, 150, false) ?>"
             alt="<?= CHtml::encode($data->getImageAlt()); ?>"
             title="<?= CHtml::encode($data->getImageTitle()); ?>">
    </a>
    <a href="<?= ProductHelper::getUrl($data); ?>" class = "catalog__list__item-capture">
      <?= CHtml::encode($data->getName()); ?>
    </a>
    <div class="catalog__list__item-bottom">
        <?php if ($data->hasDiscount()): ?>
            <p class = "catalog__list__item-old-price"><span><?= round($data->getBasePrice(), 2) ?> руб./шт.</span></p>
        <?php endif; ?>
        <p class = "catalog__list__item-price">
            <span><?= $data->getResultPrice() ?></span> руб./шт.
        </p>
        <p class = "catalog__list__item-price-add">
            Цены — ориентировочные. Уточняйте их у продавца.
        </p>
        <div class = "catalog__button">
            <a href = "<?= ProductHelper::getUrl($data); ?>">Подробнее</a>
        </div>
    </div>
</div>
