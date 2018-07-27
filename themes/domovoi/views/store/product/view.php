<?php

/* @var $product Product */
Yii::app()->getClientScript()->registerCssFile($this->mainAssets . '/styles/owl.carousel.min.css');
Yii::app()->getClientScript()->registerCssFile($this->mainAssets . '/styles/owl.theme.default.min.css');
Yii::app()->getClientScript()->registerScriptFile($this->mainAssets . '/js/owl.carousel.min.js', CClientScript::POS_END);

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

$sale = in_array(486, $product->getCategoriesId());
$new = in_array(487, $product->getCategoriesId());

Yii::app()->clientScript->registerScript('microdata', /** @lang JSON */
    '
    
    {"@context":"http://schema.org",
     "@type":"Product",
     "name":"'. $product->getProducerName() . ' ' . $product->getName() .'",
     "image": [
       "'.$product->getImageUrl().'"
     ],
     "description": "'. strip_tags($product->description) .'",
     "brand": {
       "@type": "Brand",
       "name": "'.$product->getProducerName().'"
      }
    }

    ', CClientScript::POS_HEAD, ['type' => 'application/ld+json']);

Yii::app()->clientScript->registerScript('owlInit1', /** @lang JavaScript */
    '
$(document).ready(function(){
        $(\'.order__good__view\').productGallery();
        $(\'.owl-carousel\').owlCarousel({
                margin:5,
                nav:true,
                items:5,
                dots: false,
                slideBy: 2,
            });
        });
        ', CClientScript::POS_END);
$label = false;
$label_sale = false;
$label_new = false;

if($sale || $new) {
  $label = true;
  $label_sale = $sale ?: false;
  $label_new = $new ?: false;
}
?>
<main class = "order__container-add">
    <div class = "order"  itemscope itemtype="https://schema.org/Product">
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
               <figure>
                	<div class="product__img-wrap" >
                        <a href="<?= StoreImage::product($product); ?>" class="product__img-block" data-product-image>
                            <img src="<?= StoreImage::product($product) ?>"
                                 alt="<?= CHtml::encode($product->getImageAlt()); ?>"
                                 title="<?= CHtml::encode($product->getImageTitle()); ?>"
                                 class="product__img">
                        </a>
                	</div>
                </figure>
                <div class="product__img-nav owl-carousel">
                    <a href="<?= StoreImage::product($product); ?>" rel="group" data-product-thumbnail
                       class="product__nav-item">
                        <img src="<?= StoreImage::product($product, 100, 100, false); ?>" alt="<?= CHtml::encode($product->getImageAlt()); ?>"
                             class="product__nav-img">
                    </a>
              <? if(count($product->getImages()) > 0 ) : ?>
                    <? foreach ($product->getImages() as $key => $image): ?>
                        <a href="<?= $image->getImageUrl(); ?>" rel="group" data-product-thumbnail
                           class="product__nav-item">
                            <img src="<?= $image->getImageUrl(100, 100, false); ?>" alt="<?= CHtml::encode($product->getImageAlt()); ?>"
                                 class="product__nav-img">
                        </a>
                    <? endforeach; ?>
              <? endif; ?>
                </div>
            </div>

            <form class="order__good__options" action="<?= Yii::app()->createUrl('cart/cart/add'); ?>" method="post">
                <input type="hidden" name="Product[id]" value="<?= $product->id; ?>"/>
                <?= CHtml::hiddenField(
                  Yii::app()->getRequest()->csrfTokenName,
                  Yii::app()->getRequest()->csrfToken
                ); ?>
                <input type="hidden" name="Product[quantity]" value="1" class="spinput__value" id="product-quantity-input"/>
                <h1 class = "options-capture" itemprop="name"><?= CHtml::encode($product->getTitle()); ?></h1>
                <div  itemprop="offers" itemscope itemtype="http://schema.org/Offer"
                      class="options-price <?= $product->in_stock != $product::STATUS_IN_STOCK ? 'not-in-stock' : '' ?>" >

                  <?php if($product->in_stock == $product::STATUS_IN_STOCK): ?>
                      <div class="in-stock"><link itemprop="availability" href="https://schema.org/InStock"/><?= Yii::t("StoreModule.store", "In stock");?></div>
                  <?php else: ?>
                      <div class="not-in-stock"><link itemprop="availability" href="https://schema.org/OutOfStock"/><?= Yii::t("StoreModule.store", "Not in stock");?></div>
                  <?php endif; ?>

                  <?php if ($product->hasDiscount() && $product->in_stock == $product::STATUS_IN_STOCK): ?>
                      <p class = "catalog__list__item-old-price"><span><?= round($product->getBasePrice(), 2) ?> руб./шт.</span></p>
                  <?php endif; ?>

                  <?= $product->in_stock != $product::STATUS_IN_STOCK ? '<p class="last-price">Последняя цена:</p>' : '' ?>
                    <p class = "options-price-volume" itemprop="price" content="<?= round($product->getResultPrice(), 2); ?>">
                        <span itemprop="priceCurrency" content="BYN"><?= round($product->getResultPrice(), 2); ?> руб./шт.</span>
                    </p>
                   <!-- <p class = "catalog__list__item-price-add">
                        Цены — ориентировочные.<br/> Уточняйте их у продавца.
                    </p>-->
<!--                    <p class = "options-price-opt">от 5 шт. - 30,35 руб.</p>-->
                    <div class="product__cart-button">
                        <?php if(!Yii::app()->cart->itemAt($product->id)):?>
                            <button class="btn btn__cart" id="add-product-to-cart" data-loading-text="<?= Yii::t("StoreModule.store", "Adding"); ?>">
                                <div class="glyph-icon flaticon-003-shopping-basket"></div>
                                В корзину
                            </button>
                        <?php endif;?>
                    </div>
                </div>

                <p>Или закажите по телефонам:</p>
                <button class = "btn btn__options">
                    <a href="tel:+375298098352">+375 (29) 809-83-52</a>
                </button>
                <button class = "btn btn__options">
                    <a href="tel:+375296553664">+375 (29) 655-36-64</a>
                </button>
<!--                <button class = "options-button">-->
<!--                    <a href="tel:+375164441063">8 (029) 329-80-38</a>-->
<!--                </button>-->
            </form>
        </div>
        <div class = "order__description product-tabs">
            <div class = "order__tabs" data-nav="data-nav">
                <div class="tab"><a href="#desc">Описание товара</a></div>
              <?php if (!empty($product->data)): ?>
                <div class="tab"><a href="#data">Комплектация</a></div>
              <?php endif; ?>
            </div>
            <div class="js-tabs-bodies">
                <div id="desc" class = "product__description js-tab">
                  <?php if (!empty($product->description)): ?>
                      <div class="wysiwyg" itemprop="description">
                        <?= $product->description ?>
                      </div>
                  <?php endif; ?>
                    <div class="features">
                        <h2 class="attr__title">Технические характеристики</h2>
                      <?php foreach ($product->getAttributeGroups() as $groupName => $items): ?>
                        <? if($groupName &&
                            $groupName != 'Технические характеристики' /*&&
                            $product->attribute($items[0])*/) :
                        ?>
                              <h2 class="attr-group__title"><?= CHtml::encode($groupName); ?></h2>
                        <? endif; ?>
                          <div class="attr-group__items">
                            <?php foreach ($items as $attribute): ?>
                              <? if($product->attribute($attribute)) : ?>
                                    <div class="_row">
                                        <div class="col_6">
                                            <div class="attr-name">
                                              <?= CHtml::encode($attribute->title); ?>
                                            </div>
                                        </div>
                                        <div class="col_6">
                                            <div class="attr-val">
                                              <?= AttributeRender::renderValue($attribute, $product->attribute($attribute)); ?>
                                            </div>
                                        </div>
                                    </div>
                              <? endif; ?>
                            <?php endforeach; ?>
                          </div>
                      <?php endforeach; ?>
                    </div>
                </div>

              <?php if (!empty($product->data)): ?>
                <div id="data" class = "product__description js-tab">
                      <div class="wysiwyg">
                          <ol>
                            <?= $product->data ?>
                          </ol>
                      </div>
                </div>
                  <?php endif; ?>

            </div>



        </div>
    </div>

    <?php $this->widget('application.modules.store.widgets.LinkedProductsWidget', ['product' => $product, 'code' => null,]); ?>

</main>



