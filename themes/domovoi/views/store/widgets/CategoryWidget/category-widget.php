<?php

function renderMenu($items, $level = 0)
{
    $menu = '';

    if ($level == 1) {
        $menu .= CHtml::openTag('div', ['class' => 'menu-catalog__submenu']);
    }

    $menu .= CHtml::openTag('ul');

    foreach ($items as $item) {
        $liClass = [];
        if(!empty($item['items']) && $level == 0){
          $liClass = ['class' => 'menu-catalog__item has-submenu'];
        } elseif($level == 0) {
          $liClass = ['class' => 'menu-catalog__item'];
        } else {
          $liClass = ['class' => 'submenu__item'];
        }
        $menu .= CHtml::openTag('li', $liClass);
        $menu .= CHtml::link($item['label'], $item['url']);

        if (!empty($item['items'])) {
            $menu .= renderMenu($item['items'], $level + 1);
        }

        $menu .= CHtml::closeTag('li');
    }

    $menu .= CHtml::closeTag('ul');

    if ($level == 1) {
        $menu .= CHtml::closeTag('div');
    }

    return $menu;
}

?>

<div class="menu-catalog" id="menu-catalog">
    <?= renderMenu($tree); ?>
</div>
