<?php
/**
 * @var Product $data
 */
$sale = in_array(486, $data->getCategoriesId());
$new = in_array(487, $data->getCategoriesId());

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
    <div class="catalog__list__item-bottom <?= $data->in_stock != $data::STATUS_IN_STOCK ? 'not-in-stock' : '' ?>">
      <?php if($data->in_stock == $data::STATUS_IN_STOCK): ?>
          <div class="in-stock <?= $data->hasDiscount() ? 'right' : '' ?>"><?= Yii::t("StoreModule.store", "In stock");?></div>
      <?php else: ?>
          <div class="not-in-stock"><?= Yii::t("StoreModule.store", "Not in stock");?></div>
      <?php endif; ?>
        <?php if ($data->hasDiscount() && $data->in_stock == $data::STATUS_IN_STOCK): ?>
            <p class = "catalog__list__item-old-price"><span><?= round($data->getBasePrice(), 2) ?> руб./шт.</span></p>
        <?php endif; ?>

        <?= $data->in_stock != $data::STATUS_IN_STOCK ? '<p class="last-price">Последняя цена:</p>' : '' ?>
        <p class = "catalog__list__item-price">
            <span><?= $data->getResultPrice() ?></span> руб./шт.
        </p>
        <? if($data->in_stock == $data::STATUS_IN_STOCK) : ?>
        <p class = "catalog__list__item-price-add">
            Цены — ориентировочные. Уточняйте их у продавца.
        </p>
        <? endif; ?>
        <div class = "catalog__button">
            <a href = "<?= ProductHelper::getUrl($data); ?>">Подробнее</a>
        </div>
    </div>
</div>
