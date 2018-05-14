<?php
/**
 * @var Product $product
 */
$sale = in_array(416, $product->getCategoriesId());
$new = in_array(3, $product->getCategoriesId());

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
    <a href="<?= ProductHelper::getUrl($product); ?>" class = "catalog__img">
        <img src="<?= StoreImage::product($product, 190, 150, false) ?>"
             alt="<?= CHtml::encode($product->getImageAlt()); ?>"
             title="<?= CHtml::encode($product->getImageTitle()); ?>">
    </a>
    <a href="<?= ProductHelper::getUrl($product); ?>" class = "catalog__list__item-capture">
      <?= CHtml::encode($product->getName()); ?>
    </a>
    <div class="catalog__list__item-bottom">
      <?php if ($product->hasDiscount()): ?>
        <p class = "catalog__list__item-old-price"><span><?= round($product->getBasePrice(), 2) ?> руб./шт.</span></p>
      <?php endif; ?>
        <p class = "catalog__list__item-price">
            <span><?= $product->getResultPrice() ?></span> руб./шт.
        </p>

        <p class = "catalog__list__item-price-add">
            Цены — ориентировочные. Уточняйте их у продавца.
        </p>
        <div class = "catalog__button">
            <a href = "<?= ProductHelper::getUrl($product); ?>">Подробнее</a>
        </div>
    </div>
</div>