<?php

use yupe\components\controllers\FrontController;

/**
 * Class ProductController
 */
class ProductController extends FrontController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var AttributeFilter
     */
    protected $attributeFilter;

    /**
     *
     */
    public function init()
    {
        $this->productRepository = Yii::app()->getComponent('productRepository');
        $this->attributeFilter = Yii::app()->getComponent('attributesFilter');

        parent::init();
    }

    /**
     *
     */
    public function actionIndex()
    {
        $view = 'index';
        $typesSearchParam = $this->attributeFilter->getTypeAttributesForSearchFromQuery(Yii::app()->getRequest());

        $mainSearchParam = $this->attributeFilter->getMainAttributesForSearchFromQuery(
            Yii::app()->getRequest(),
            [
                AttributeFilter::MAIN_SEARCH_PARAM_NAME => Yii::app()->getRequest()->getQuery(AttributeFilter::MAIN_SEARCH_QUERY_NAME)
            ]
        );

        if (!empty($mainSearchParam) || !empty($typesSearchParam)) {
            $data = $this->productRepository->getByFilter($mainSearchParam, $typesSearchParam);
            $view = 'search';
        } else {
            $data = false;
        }
        $this->render(
            $view,
            [
                'dataProvider' => $data,
                'searchString' => $view == 'search' ? $mainSearchParam[AttributeFilter::MAIN_SEARCH_PARAM_NAME] : false,
            ]
        );
    }
  /**
   *
   */
  public function actionSearch()
  {
    if (Yii::app()->getRequest()->getIsAjaxRequest()) {

      $typesSearchParam = $this->attributeFilter->getTypeAttributesForSearchFromQuery(Yii::app()->getRequest());
      $mainSearchParam = $this->attributeFilter->getMainAttributesForSearchFromQuery(
          Yii::app()->getRequest(),
          [
              AttributeFilter::MAIN_SEARCH_PARAM_NAME => Yii::app()->getRequest()->getQuery(AttributeFilter::MAIN_SEARCH_QUERY_NAME)
          ]
      );

      if (!empty($mainSearchParam) || !empty($typesSearchParam)) {
        $data = $this->productRepository->getByFilter($mainSearchParam, $typesSearchParam, 15);
      } else {
        $data = false;
      }
      $result = [];
      $i = 0;
      foreach ($data->getData() as $item){
        /**
         * @var $item Product
         */
        $result[$i]['name'] = $item->name;
        $result[$i]['url'] = ProductHelper::getUrl($item);
        $result[$i]['img'] = StoreImage::product($item, 45, 45, false);
        $i++;
      }

      echo CJSON::encode($result);

      return;
    }
      throw new CHttpException(404);
  }

    /**
     * @param string $name Product slug
     * @param string $category Product category path
     * @throws CHttpException
     */
    public function actionView($name, $category = null)
    {
        $product = $this->productRepository->getBySlug($name);

        if (
            null === $product ||
            (isset($product->category) && $product->category->path !== $category) ||
            (!isset($product->category) && !is_null($category))
        ) {
            throw new CHttpException(404, Yii::t('StoreModule.catalog', 'Product was not found!'));
        }

        Yii::app()->eventManager->fire(StoreEvents::PRODUCT_OPEN, new ProductOpenEvent($product));

        $this->render($product->view ?:'view', ['product' => $product]);
    }
}