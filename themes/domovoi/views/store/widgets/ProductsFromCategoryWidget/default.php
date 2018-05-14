<?php
/**
 * @var StoreCategory $category
 * @var array $products Product objects array
 * @var Product $product
 */
?>

<?php foreach ($products->getData() as $product): ?>
  <?php $this->render('_item', ['product' => $product]) ?>
<?php endforeach; ?>
