<?php

/* @var $product Product */

$this->title = $product->getMetaTitle();
$this->description = $product->getMetaDescription();
$this->keywords = $product->getMetaKeywords();
$this->canonical = $product->getMetaCanonical();

//Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/store.js', CClientScript::POS_END);

$this->breadcrumbs = array_merge(
    [Yii::t("StoreModule.store", 'Catalog') => ['/store/product/index']],
    $product->category ? $product->category->getBreadcrumbs(true) : [],
    [CHtml::encode($product->name)]
);

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
<div class = "order__container-add">
    <div class = "order">
        <div class = "order__good">
            <div class="order__good__view">
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
                <a class="product__img-wrap" href="<?= StoreImage::product($product); ?>" data-fancybox>
                    <img src="<?= StoreImage::product($product); ?>"
                         alt="<?= CHtml::encode($product->getImageAlt()); ?>"
                         title="<?= CHtml::encode($product->getImageTitle()); ?>">
                </a>
                <div class = "view__search">
                    <img src="<?= $this->mainAssets ?>/img/search-rev.png" />
                </div>

            </div>
            <div class="order__good__options">
                <h1 class = "options-capture"><?= CHtml::encode($product->getTitle()); ?></h1>
                <div class="options-price">
                  <?php if ($product->hasDiscount()): ?>
                      <p class = "catalog__list__item-old-price"><span><?= round($product->getBasePrice(), 2) ?> руб./шт.</span></p>
                  <?php endif; ?>
                    <p class = "options-price-volume"><span><?= round($product->getResultPrice(), 2); ?> руб./шт.</span></p>
                    <p class = "catalog__list__item-price-add">
                        Цены — ориентировочные.<br/> Уточняйте их у продавца.
                    </p>
<!--                    <p class = "options-price-opt">от 5 шт. - 30,35 руб.</p>-->
                </div>
                <p>Заказать можно по телефонам:</p>
                <button class = "options-button">
                    <a href="tel:+375164441063">8 (01644) 4-10-63</a>
                </button>
                <button class = "options-button">
                    <a href="tel:+375164441063">8 (029) 329-80-38</a>
                </button>
            </div>
        </div>
        <div class = "order__description">
            <div class = "order__tabs">
                <div class="tab-1"><a href = "">Описание товара</a></div>
<!--                <div class="tab-2"><a href=""><span>Совет мастера</span></a></div>-->
            </div>
            <div class = "product__description">
                <?php if (!empty($product->description)): ?>
                    <div class="wysiwyg">
                        <?= $product->description ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php $this->widget('application.modules.store.widgets.LinkedProductsWidget', ['product' => $product, 'code' => null,]); ?>

</div>



