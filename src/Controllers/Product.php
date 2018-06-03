<?php

namespace Controllers;

class Product extends \Framework\Controller {
    private $dataLayer;
    private $authenticationManager;
  
    public function __construct(\BusinessLogic\DataLayer $dataLayer, \BusinessLogic\AuthenticationManager $authenticationManager) {
      $this->dataLayer = $dataLayer;
      $this->authenticationManager = $authenticationManager;
    }

    const CAT_ID = 'cid';
  
    public function GET_Index() {
      $this->renderView('ProductList', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'categories' => $this->dataLayer->getCategories(),
        'selectedCategoryId' => $this->getParam(self::CAT_ID),
        'products' => $this->hasParam(self::CAT_ID) ? $this->dataLayer->getProductsForCategory($this->getParam(self::CAT_ID)) : null,
        'context' => $this->buildActionLink('Index', 'Products', array(self::CAT_ID => $this->getParam(self::CAT_ID)))
      ));
    }
  
    public function GET_Search() {
      return $this->renderView('ProductSearch', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'name' => $this->getParam('name'),
        'products' => $this->hasParam('name') ? $this->dataLayer->getProductsForSearchCriteria($this->getParam('name')) : null,
        'context' => $this->buildActionLink('Search', 'Products', array('name' => $this->getParam('name')))
      ));
    }
  }
