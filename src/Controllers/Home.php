<?php

namespace Controllers;

class Home extends \Framework\Controller  {
  private $authenticationManager;

  public function __construct(\BusinessLogic\AuthenticationManager $authenticationManager) {
    $this->authenticationManager = $authenticationManager;
  }

  public function GET_Index() {
    $this->renderView('home', array(
      'user' => $this->authenticationManager->getAuthenticatedUser(),
      'context' => $this->buildActionLink('Index', 'Home', array())    
    ));
  }
}
