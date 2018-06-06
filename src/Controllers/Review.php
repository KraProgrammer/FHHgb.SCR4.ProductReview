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
    $review = $this->hasParam(self::REV_ID) ? $this->dataLayer->getReviewForId($this->getParam(self::REV_ID)) : null;

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
      'rating' => $rating,
      'comment' => $comment     
    ));
  } 
  
  public function POST_Create() {
    // TODO 
  }

  public function POST_Modify() {
    // TODO
  }
}
