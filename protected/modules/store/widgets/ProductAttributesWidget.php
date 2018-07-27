<?php
/**
 * Виджет для отображения атрибутов в _item товара в категориях.
 */
Yii::import('application.modules.store.models.Product');

class ProductAttributesWidget extends \yupe\widgets\YWidget
{
    /**
     * @var string
     */
    public $view = 'default';
    /**
     * @var
     */
    public $attributes;
    /**
     * @var Product
     */
    public $product;
    /**
     * @var string
     */
    public $limit;
    /**
     * @param Product $product
     * @return string
     *
     */
    public function getAttributes($product)
    {
        $result = '';
        $i = 0;
        if (!empty($product->getTypeAttributes())) {
            foreach ($product->getTypeAttributes() as $attribute) {
                if ($attribute['is_filter'] == '1') {
                    $attr = AttributeRender::renderValueInProductItem($attribute, $product->attribute($attribute));
                    if(!empty($attr)){
                      $result .= $attr.', ';
                      if(++$i >= $this->limit) break;
                    }
                }
            }
            $result = substr($result, 0, -2);
        }
        return $result;
    }
    /**
     * @return bool
     * @throws CException
     */
    public function run()
    {
        $this->render(
          $this->view,
          [
            'attributes' => $this->getAttributes($this->product),
          ]
        );
    }
}