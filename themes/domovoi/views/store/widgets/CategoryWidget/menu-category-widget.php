<?php

function renderMenu_($items, $level = 0)
{
    $menu = '';

    if ($level == 1) {
        $menu .= CHtml::openTag('ul', ['class' => 'sub']);
    } else{
      $menu .= CHtml::openTag('ul');
    }


    foreach ($items as $item) {
        $liClass = [];
        if(!empty($item['items']) && $level == 0){
          $liClass = ['class' => 'left-menu__item has-sub'];
        } elseif($level == 0) {
          $liClass = [];
        } else {
          $liClass = [];
        }
        $menu .= CHtml::openTag('li', $liClass);
        $menu .= CHtml::link($item['label'], $item['url']);

        if (!empty($item['items'])) {
            $menu .= renderMenu_($item['items'], $level + 1);
        }

        $menu .= CHtml::closeTag('li');
    }

    $menu .= CHtml::closeTag('ul');


    return $menu;
}

?>

<nav class = "catalog__nav">
    <?= renderMenu_($tree); ?>
</nav>
