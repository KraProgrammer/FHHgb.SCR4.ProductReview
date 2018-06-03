<?php

namespace Controllers;

class User extends \Framework\Controller {
    private $authenticationManager;

    public function __construct(\BusinessLogic\AuthenticationManager $authenticationManager) {
      $this->authenticationManager = $authenticationManager;
    }

    public function GET_LogIn() {
        // loginpage
        if ($this->authenticationManager->isAuthenticated()) {
          return $this->redirect('Index', 'Home');
        } else {
          return $this->renderView('Login', array(
            'user' => $this->authenticationManager->getAuthenticatedUser(),
            'userName'=> ''
          ));
        }

    }

    public function POST_LogIn() {
        // authenticate user and redirect on succcess or show login page again
        if ($this->authenticationManager->authenticate($this->getParam('un'), $this->getParam('pwd'))) {
          return $this->redirect('Index', 'Home');
        } else {
          // ERROR
          return $this->renderView('Login', array(
            'user' => $this->authenticationManager->getAuthenticatedUser(),
            'userName'=> $this->getParam('un'),
            'errors' => array('Invalid user name or password')
          ));
        }
    }

    public function POST_LogOut() {
        // sign out current user and redirect to main page
        $this->authenticationManager->signOut();
        return $this->redirect('Index', 'Home'); // TODO Location (e.g. again with context)
    }

}
