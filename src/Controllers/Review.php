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
  const PROD_ID = 'pid';

  public function GET_Index() {
    $this->renderView('ReviewList', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'reviews' => $this->authenticationManager->isAuthenticated() ?  $this->dataLayer->getReviewForUserId($this->authenticationManager->getAuthenticatedUser()->getId()) : null
      ));
  }

  public function GET_Create() {
    if (!$this->authenticationManager->isAuthenticated()) {
      return $this->redirect('LogIn', 'User'); // TODO add context so that user acan return here after loggging in
    }

    $review = $this->hasParam(self::REV_ID) ? $this->dataLayer->getReviewForId($this->getParam(self::REV_ID)) : null;
    $productId = $this->hasParam(self::PROD_ID) ? trim($this->getParam(self::PROD_ID)) : null;

    if ($productId == null) {
      return $this->redirect('Index', 'Product');
    }

    if ($review !== null) {
      $rating = $review->getRating();
      $comment = $product->getComment();
    } else {
      $rating = '';
      $comment = '';
    }

    $this->renderView('CreateReview', array(
      'user' => $this->authenticationManager->getAuthenticatedUser(),
      'review' => $review, 
      'productId' => $productId,
      'rating' => $rating,
      'comment' => $comment     
    ));
  } 
  
  public function POST_Create() {
    if (!$this->authenticationManager->isAuthenticated()) {
      return $this->redirect('LogIn', 'User'); // TODO add context so that user acan return here after loggging in
    }

    $productId = $this->hasParam(self::PROD_ID) ? trim($this->getParam(self::PROD_ID)) : null;

    if ($productId == null) {
      return $this->redirect('Index', 'Product');
    }

    // validate from datalayer
    $errors = array();
    $rating = $this->hasParam('rating') ? trim($this->getParam('rating')) : null;
    if ($rating == null || strlen($rating) == 0) {
      $errors[] = "Invalid rating.";
    }
    $comment = $this->hasParam('comment') ? trim($this->getParam('comment')) : null;
    
    if (count($errors) > 0 ) {
      // render error view
      return $this->renderView('CreateReview', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'rating' => $rating,
        'productId' => $productId,
        'comment' => $comment,
        'errors' => $errors
      ));
    } else {
      $user = $this->authenticationManager->getAuthenticatedUser(); //productId?
      $reviewId = $this->dataLayer->createReview($user->getId(), $productId, $rating, $comment);

      if ($reviewId == false) {
        // something went wrong
        return $this->renderView('CreateReview', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'rating' => $rating,
          'productId' => $productId,
          'comment' => $comment,
          'errors' => array('Could not create order. Please try again.')
        ));
      } else {
        return $this->redirect('Index', 'Review');
      }
    }
  }

  public function POST_Modify() {
    if (!$this->authenticationManager->isAuthenticated()) {
      return $this->redirect('LogIn', 'User'); // TODO add context so that user acan return here after loggging in
    }
    // TODO
  }
}
