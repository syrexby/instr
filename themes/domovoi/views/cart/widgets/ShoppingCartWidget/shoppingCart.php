<?php
$currency = Yii::app()->getModule('store')->currency;
?>
<div class="header__item header-cart js-cart" id="cart-widget" data-cart-widget-url="<?= Yii::app()->createUrl('/cart/cart/widget'); ?>">

    <div class="header-cart__icon">
        <a href="<?= Yii::app()->createUrl('/cart/cart/index'); ?>" class="glyph-icon flaticon-003-shopping-basket" title="Перейти в корзину">
            <span><?= Yii::app()->cart->getItemsCount(); ?></span>
        </a>
    </div>
    <div class="header-cart__text-wrap">
        <div class="header-cart__cost">
<!--            <div class="header-cart__cost-title">--><?//= Yii::t('CartModule.cart', 'Sum'); ?><!--:</div>-->
            <div class="header-cart__cost-price">
                <span class="js-cart__subtotal"><?= number_format(Yii::app()->cart->getCost(), 2, ',', ' '); ?></span>
                <span class="ruble"> <?= Yii::t('CartModule.cart', $currency); ?></span>
            </div>
        </div>
        <div class="header-cart__label">
            <? //= CHtml::link(Yii::t('CartModule.cart', 'Cart'), ['/cart/cart/index']); ?>
            <a href="javascript:void(0);" class="header-cart__cart-toggle" id="cart-toggle-link">Корзина</a>
            <div class="cart-mini" id="cart-mini">
                <?php if (Yii::app()->cart->isEmpty()): ?>
                    <p><?= Yii::t('CartModule.cart', 'There are no products in cart'); ?></p>
                <?php else: ?>
                    <div class="cart-mini__items">
                        <?php foreach (Yii::app()->cart->getPositions() as $product): ?>
                            <?php $price = number_format($product->getResultPrice(), 2, ',', ' '); ?>
                            <div class="cart-mini__item js-cart__item">
                                <a class="cart-mini__thumbnail" href="<?= ProductHelper::getUrl($product)?>">
                                    <img src="<?= $product->getImageUrl(60, 60, false); ?>" class="cart-mini__img"/>
                                </a>
                                <div class="cart-mini__info">
                                    <div class="cart-mini__title">
                                        <?= CHtml::link($product->name, ProductHelper::getUrl($product),
                                          ['class' => 'cart-mini__link']); ?>
                                    </div>
                                    <div class="cart-mini__base-price">
                                        <?= $price ?>
                                        <span class="ruble"><?= Yii::t('CartModule.cart', $currency); ?></span>
                                        <br/>
                                        x <?= $product->getQuantity(); ?> <?= Yii::t('CartModule.cart', 'pcs'); ?>
                                        =
                                        <div class="product-price">
                                            <?= number_format($product->getSumPrice(), 2, ',', ' '); ?>
                                            <span class="ruble"><?= Yii::t('CartModule.cart', $currency); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-mini__delete-btn js-cart__delete mini-cart-delete-product" data-position-id="<?= $product->getId(); ?>">
                                    <i class="fa fa-trash-o"></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="cart-mini__bottom">
                        <div class="cart-mini__sum-block">
                            <p>Кол-во: <span class="cart-mini__total-items"><?= Yii::app()->cart->getItemsCount(); ?></span> шт.</p>
                            <p>Сумма: <span class="cart-mini__total-sum">
                                <?= number_format(Yii::app()->cart->getCost(), 2, ',', ' '); ?></span>
                                <?= Yii::t('CartModule.cart', $currency); ?>
                            </p>
                        </div>
                        <a href="<?= Yii::app()->createUrl('cart/cart/index'); ?>" class="btn btn_success">
                            <?= Yii::t('CartModule.cart', 'Make an order'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>