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
  const CTX = 'ctx';

  public function GET_Index() {
    if (!$this->authenticationManager->isAuthenticated()) {
      return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Index', 'Review', array())));
    }
    $this->renderView('ReviewList', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'reviews' => $this->authenticationManager->isAuthenticated() ?  $this->dataLayer->getReviewForUserId($this->authenticationManager->getAuthenticatedUser()->getId()) : null,
        'context' => $this->buildActionLink('Index', 'Review', array())
      ));
  }

  public function GET_Create() {
    if (!$this->authenticationManager->isAuthenticated()) {
      if ($this->hasParam(self::REV_ID)) {
        return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Review', array(self::REV_ID => $this->getParam(self::REV_ID)))));
      } else {
        return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Review', array(self::PROD_ID => $this->getParam(self::PROD_ID)))));
      }
    }

    $review = $this->hasParam(self::REV_ID) ? $this->dataLayer->getReviewForId($this->getParam(self::REV_ID)) : null;
    $productId = $this->hasParam(self::PROD_ID) ? $this->getParam(self::PROD_ID) : null;

    if ($productId === null) {
       $productId = $review !== null ? $review->getProductId() : null;
    }
    if ($productId === null) {
       return $this->redirect('Index', 'Product'); // error!
    } else {
      $product = $this->dataLayer->getProductsForId($productId);
    }

    if ($review !== null) {
      $rating = $review->getRating();
      $comment = $review->getComment();
    } else {
      $rating = '';
      $comment = '';
    }

    $actionLink = $this->getActionLink();

    $this->renderView('CreateReview', array(
      'user' => $this->authenticationManager->getAuthenticatedUser(),
      'review' => $review, 
      'product' => $product,
      'rating' => $rating,
      'comment' => $comment,
      'context' => $actionLink 
    ));
  } 
  
  public function POST_Create() {
    if (!$this->authenticationManager->isAuthenticated()) {
      if ($this->hasParam(self::REV_ID)) {
        return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Review', array(self::REV_ID => $this->getParam(self::REV_ID)))));
      } else {
        return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Review', array())));
      }
    }

    $errors = array();
    $product = $this->hasParam(self::PROD_ID) ? $this->dataLayer->getProductsForId($this->getParam(self::PROD_ID)) : null;
    if ($product == null) {
      $errors[] = "Invalid product.";
    }

    // validate from datalayer
    $rating = $this->hasParam('rating') ? $this->getParam('rating') : null;
    if ($rating == null || !ctype_digit($rating)) {
      $errors[] = "Invalid rating.";
    }
    $comment = $this->hasParam('comment') ? trim($this->getParam('comment')) : null;
    $actionLink = $this->getActionLink();


    if (count($errors) > 0 ) {
      // render error view
      return $this->renderView('CreateReview', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'rating' => $rating,
        'product' => $product,
        'comment' => $comment,
        'errors' => $errors,
        'context' => $actionLink 
      ));
    } else {
      $user = $this->authenticationManager->getAuthenticatedUser();
      $reviewId = $this->dataLayer->createReview($user->getId(), $product->getId(), $rating, $comment);

      if ($reviewId == false) {
        // something went wrong
        return $this->renderView('CreateReview', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'rating' => $rating,
          'product' => $product,
          'comment' => $comment,
          'errors' => array('Could not create review. Please try again.'),
          'context' => $actionLink 
        ));
      } else {
        return $this->redirect('Details', 'Product', array(
          self::PROD_ID => $product->getId()
        ));
      }
    }
  }

  public function POST_Modify() {
    if (!$this->authenticationManager->isAuthenticated()) {
      if ($this->hasParam(self::REV_ID)) {
        return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Review', array(self::REV_ID => $this->getParam(self::REV_ID)))));
      } else {
        return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Review', array())));
      }
    }

    $review = $this->hasParam(self::REV_ID) ? $this->dataLayer->getReviewForId($this->getParam(self::REV_ID)) : null;

    $errors = array();
    if ($review === null) {
      $errors[] = "Something went wrong.";
    }

    // validate from datalayer
    $rating = $this->hasParam('rating') ? $this->getParam('rating') : null;
    if ($rating == null || !ctype_digit($rating)) {
      $errors[] = "Invalid rating.";
    }
    $comment = $this->hasParam('comment') ? trim($this->getParam('comment')) : null;
    $actionLink = $this->getActionLink();
    
    if (count($errors) > 0 ) {
      // render error view
      return $this->renderView('CreateReview', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'review' => $review, 
        'rating' => $rating,
        'product' => null,
        'comment' => $comment,
        'errors' => $errors,
        'context' => $actionLink 
      ));
    } else {
      $user = $this->authenticationManager->getAuthenticatedUser();

      if ($review->getUser() !== $user->getUsername()) {
        // trying to change product from other user
        return $this->renderView('CreateReview', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'review' => $review, 
          'rating' => $rating,
          'product' => null,
          'comment' => $comment,
          'errors' => array('Cannot change review of other users. '),
          'context' => $actionLink 
        ));
      }


      $reviewId = $this->dataLayer->updateReview($review->getId(), $rating, $comment);
      if ($reviewId == false) {
        // something went wrong
        return $this->renderView('CreateReview', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'review' => $review, 
          'rating' => $rating,
          'product' => null,
          'comment' => $comment,
          'errors' => array('Could not modify review. Please try again.'),
          'context' => $actionLink 
        ));
      } else {
        return $this->redirect('Details', 'Product', array(
          self::PROD_ID => $review->getProductId()
        ));
      }
    }

  }

  public function GET_Delete() {
    if (!$this->authenticationManager->isAuthenticated()) {
      if ($this->hasParam(self::REV_ID)) {
        return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Review', array(self::REV_ID => $this->getParam(self::REV_ID)))));
      } else {
        return $this->redirect('LogIn', 'User', array('ctx' => $this->buildActionLink('Create', 'Review', array())));
      }
    }

    $errors = array();
    $review = $this->hasParam(self::REV_ID) ? $this->dataLayer->getReviewForId($this->getParam(self::REV_ID)) : null;
    $actionLink = $this->getActionLink();

    $errors = array();
    if ($review === null) {
      $errors[] = "Something went wrong.";
    }
    
    if (count($errors) > 0 ) {
      // render error view
      return $this->renderView('CreateReview', array(
        'user' => $this->authenticationManager->getAuthenticatedUser(),
        'errors' => $errors,
        'context' => $actionLink 
      ));
    } else {
      $user = $this->authenticationManager->getAuthenticatedUser();

      if ($review->getUser() !== $user->getUsername()) {
        // trying to change product from other user
        return $this->renderView('CreateReview', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'review' => $review, 
          'errors' => array('Cannot delete review of other users. '),
          'context' => $actionLink 
        ));
      }


      $reviewId = $this->dataLayer->deleteReview($review->getId());
      if ($reviewId == false) {
        // something went wrong
        return $this->renderView('CreateReview', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'review' => $review, 
          'errors' => array('Could not delete review. Please try again.'),
          'context' => $actionLink 
        ));
      } else {
        return $this->redirect('Details', 'Product', array(
          self::PROD_ID => $review->getProductId()
        ));
      }
    }
  }

  private function getActionLink() {
    if ($this->hasParam(self::REV_ID)) {
      return $this->buildActionLink('Create', 'Review', array(self::REV_ID => $this->getParam(self::REV_ID)));
    } else {
      return $this->buildActionLink('Create', 'Review', array(self::PROD_ID => $this->getParam(self::PROD_ID)));
    }
  }

}
