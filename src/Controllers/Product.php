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
    const PROD_ID = 'pid';
    const CTX = 'ctx';
  
    public function GET_Index() {
      $this->renderView('ProductList', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'categories' => $this->dataLayer->getCategories(),
        'selectedCategoryId' => $this->getParam(self::CAT_ID),
        'products' => $this->hasParam(self::CAT_ID) ? $this->dataLayer->getProductsForCategory($this->getParam(self::CAT_ID)) : null,
        'context' => $this->buildActionLink('Index', 'Product', array(self::CAT_ID => $this->getParam(self::CAT_ID)))
      ));
    }

    public function GET_Details() {
      $this->renderView('ProductDetails', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'selectedProductId' => $this->getParam(self::PROD_ID),
        'product' => $this->hasParam(self::PROD_ID) ? $this->dataLayer->getProductsForId($this->getParam(self::PROD_ID)) : null,
        'reviews' => $this->hasParam(self::PROD_ID) ? $this->dataLayer->getReviewForProductId($this->getParam(self::PROD_ID)) : null,
        'context' => $this->buildActionLink('Details', 'Product', array(self::PROD_ID => $this->getParam(self::PROD_ID)))
      ));
    }

    public function GET_Create() {
      if (!$this->authenticationManager->isAuthenticated()) {
        if ($this->hasParam(self::PROD_ID)) {
          return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Product', array(self::PROD_ID => $this->getParam(self::PROD_ID)))));
        } else {
          return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Product', array())));
        }
      }
      $product = $this->hasParam(self::PROD_ID) ? $this->dataLayer->getProductsForId($this->getParam(self::PROD_ID)) : null;

      if ($product !== null) {
        $category = $product->getCategory();
        $name = $product->getName();
        $manufacturer = $product->getManufacturer();
      } else {
        $category = '';
        $name= '';
        $manufacturer= '';
      }

      $this->renderView('CreateProduct', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'product' => $product,
        'category' => $category,
        'name' => $name,
        'manufacturer' => $manufacturer,
        'context' => $this->buildActionLink('Index', 'Product', array())     
      ));
    }

    public function POST_Create() {
      if (!$this->authenticationManager->isAuthenticated()) {
        if ($this->hasParam(self::PROD_ID)) {
          return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Product', array(self::PROD_ID => $this->getParam(self::PROD_ID)))));
        } else {
          return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Product', array())));
        }
      }

      // validate from datalayer
      $errors = array();
      $category = $this->hasParam('cat') ? trim($this->getParam('cat')) : null;
      if ($category == null || strlen($category) == 0) {
        $errors[] = "Invalid category.";
      }
      $name = $this->hasParam('name') ? trim($this->getParam('name')) : null;
      if ($name == null || strlen($name) == 0) {
        $errors[] = "Invalid product name";
      }
      $manufacturer = $this->hasParam('manu') ? trim($this->getParam('manu')) : null;
      if ($manufacturer == null || strlen($manufacturer) == 0) {
        $errors[] = "Invalid manufacturer";
      }

      if (count($errors) > 0 ) {
        // render error view
        return $this->renderView('CreateProduct', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'category' => $category,
          'name' => $name,
          'manufacturer' => $manufacturer,
          'errors' => $errors,
          'context' => $this->buildActionLink('Index', 'Product', array())  
        ));
      } else {
        $user = $this->authenticationManager->getAuthenticatedUser();
        $productId = $this->dataLayer->createProduct($user->getId(), $category, $name, $manufacturer);

        if ($productId == false) {
          // something went wrong
          return $this->renderView('CreateProduct', array(
            'user' => $this->authenticationManager->getAuthenticatedUser(),
            'category' => $category,
            'name' => $name,
            'manufacturer' => $manufacturer,
            'errors' => array('Could not create product. Please try again.'),
            'context' => $this->buildActionLink('Index', 'Product', array())  
          ));
        } else {
          return $this->redirect('Details', 'Product', array(
            self::PROD_ID => $productId
          ));
        }
      }
    }

    public function POST_Modify() {
      if (!$this->authenticationManager->isAuthenticated()) {
        return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Product', array(self::PROD_ID => $this->getParam(self::PROD_ID)))));
      }
      
      // validate from datalayer
      $errors = array();
      $category = $this->hasParam('cat') ? trim($this->getParam('cat')) : null;
      if ($category == null || strlen($category) == 0) {
        $errors[] = "Invalid category.";
      }
      $name = $this->hasParam('name') ? trim($this->getParam('name')) : null;
      if ($name == null || strlen($name) == 0) {
        $errors[] = "Invalid product name";
      }
      $manufacturer = $this->hasParam('manu') ? trim($this->getParam('manu')) : null;
      if ($manufacturer == null || strlen($manufacturer) == 0) {
        $errors[] = "Invalid manufacturer";
      }
      $productId = $this->hasParam(self::PROD_ID) ? trim($this->getParam(self::PROD_ID)) : null;
      if ($productId == null || !ctype_digit($productId)) {
        $errors[] = "Invalid product id";
      }

      $user = $this->authenticationManager->getAuthenticatedUser();
      $product = $this->dataLayer->getProductsForId($productId);
      if (count($errors) > 0 ) {
        // render error view
        return $this->renderView('CreateProduct', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'product' => $product,
          'category' => $category,
          'name' => $name,
          'manufacturer' => $manufacturer,
          'errors' => $errors,
          'context' => $this->buildActionLink('Index', 'Product', array())  
        ));
      } else {
        if ($product->getUser() !== $user->getUsername()) {
          // trying to change product from other user
          return $this->renderView('CreateProduct', array(
            'user' => $this->authenticationManager->getAuthenticatedUser(),
            'product' => $product,
            'category' => $category,
            'name' => $name,
            'manufacturer' => $manufacturer,
            'errors' => array('Cannot change products of other users. '),
            'context' => $this->buildActionLink('Index', 'Product', array())  
          ));
        }

        $productId = $this->dataLayer->updateProduct($productId, $category, $name, $manufacturer);

        if ($productId == false) {
          // something went wrong
          return $this->renderView('CreateProduct', array(
            'user' => $this->authenticationManager->getAuthenticatedUser(),
            'product' => $product,
            'category' => $category,
            'name' => $name,
            'manufacturer' => $manufacturer,
            'errors' => array('Could not modify product. Please try again.'),
            'context' => $this->buildActionLink('Index', 'Product', array())  
          ));
        } else {
          return $this->redirect('Details', 'Product', array(
            self::PROD_ID => $productId
          ));
        }
      }
    }
  
    public function GET_Search() {
      return $this->renderView('ProductSearch', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'name' => $this->getParam('name'),
        'products' => $this->hasParam('name') ? $this->dataLayer->getProductsForSearchCriteria($this->getParam('name')) : null,
        'context' => $this->buildActionLink('Search', 'Product', array('name' => $this->getParam('name')))
      ));
    }
  }
