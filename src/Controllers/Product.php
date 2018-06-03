<?php

namespace Controllers;

class Product extends \Framework\Controller {
    private $authenticationManager;

    public function __construct(\BusinessLogic\AuthenticationManager $authenticationManager) {
      $this->authenticationManager = $authenticationManager;
    }
  
    public function GET_Index() {
      $this->renderView('home', array(
        'user' => $this->authenticationManager->getAuthenticatedUser()
      ));
    }
  }
