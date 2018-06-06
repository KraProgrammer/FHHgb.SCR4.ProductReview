<?php
namespace Controllers;

class Review extends \Framework\Controller {
  private $dataLayer;
  private $authenticationManager;

  public function __construct(\BusinessLogic\DataLayer $dataLayer, \BusinessLogic\AuthenticationManager $authenticationManager) {
    $this->dataLayer = $dataLayer;
    $this->authenticationManager = $authenticationManager;
  }

  const REV_ID = 'rid';

  public function GET_Index() {
    if (!$this->authenticationManager->isAuthenticated()) {
      return $this->redirect('LogIn', 'User'); // TODO add context so that user acan return here after loggging in
    }

    $this->renderView('ReviewList', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'reviews' => $this->authenticationManager->isAuthenticated() ?  $this->dataLayer->getReviewForUserId($this->authenticationManager->getAuthenticatedUser()->getId()) : null
      ));
  }

  public function GET_Create() {
    // $product = $this->hasParam(self::REV_ID) ? $this->dataLayer->getProductsForId($this->getParam(self::REV_ID)) : null;

    // if ($product !== null) {
    //   $category = $product->getCategory();
    //   $name = $product->getName();
    //   $manufacturer = $product->getManufacturer();
    // } else {
    //   $category = '';
    //   $name= '';
    //   $manufacturer= '';
    }

    $this->renderView('CreateProduct', array(
      'user' => $this->authenticationManager->getAuthenticatedUser(),
      'product' => $product,
      'category' => $category,
      'name' => $name,
      'manufacturer' => $manufacturer     
    ));
  }  
}
