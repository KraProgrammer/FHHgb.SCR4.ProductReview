<?php

namespace Controllers;

class User extends \Framework\Controller {
    private $authenticationManager;
    private $dataLayer;

    public function __construct(\BusinessLogic\DataLayer $dataLayer, \BusinessLogic\AuthenticationManager $authenticationManager) {
      $this->authenticationManager = $authenticationManager;
      $this->dataLayer = $dataLayer;
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

    public function GET_SignUp() {
      if ($this->authenticationManager->isAuthenticated()) {
        return $this->redirect('Index', 'Home');
      } else {
        return $this->renderView('SignUp', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'username' => '',
          'firstname' => '',
          'lastname' => ''  
        ));
      }
    }
  
    public function POST_SignUp() {
      if (!$this->authenticationManager->isAuthenticated()) {
        $this->authenticationManager->signOut();
      }

      // validate from datalayer
      $errors = array();
      $un = $this->hasParam('un') ? trim($this->getParam('un')) : null;
      if ($un == null || strlen($un) == 0) {
        $errors[] = "Invalid username.";
      }
      $pwd = $this->hasParam('pwd') ? trim($this->getParam('pwd')) : null;
      if ($pwd == null || strlen($pwd) == 0) {
        $errors[] = "Invalid password";
      }
      $fn = $this->hasParam('fn') ? trim($this->getParam('fn')) : null;
      if ($fn == null || strlen($fn) == 0) {
        $errors[] = "Invalid first name";
      }
      $ln = $this->hasParam('ln') ? trim($this->getParam('ln')) : null;
      if ($ln == null || strlen($ln) == 0) {
        $errors[] = "Invalid last name";
      }
      $user = $this->dataLayer->isUsernameUsed($un);
      if ($user) {
        $errors[] = "Invalid username. Try another. ";
      }
      
      if (count($errors) > 0 ) {
        // render error view
        return $this->renderView('SignUp', array(
          'user' => $this->authenticationManager->getAuthenticatedUser(),
          'username' => $un,
          'firstname' => $fn,
          'lastname' => $ln,
          'errors' => $errors
        ));
      } else {
        $userId = $this->dataLayer->createUser($un, $pwd, $fn, $ln);
        
        if ($userId == false) {
          // something went wrong
          return $this->renderView('SignUp', array(
            'user' => $this->authenticationManager->getAuthenticatedUser(),
            'username' => $un,
            'firstname' => $fn,
            'lastname' => $ln,
            'errors' => array('Could not create order. Please try again.')
          ));
        } else {
          if ($this->authenticationManager->authenticate($this->getParam('un'), $this->getParam('pwd'))) {
            return $this->redirect('Index', 'Home');
          } 
        }
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
