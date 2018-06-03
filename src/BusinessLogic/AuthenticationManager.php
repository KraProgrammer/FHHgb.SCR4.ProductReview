<?php
namespace BusinessLogic;

final class AuthenticationManager {
    private $dataLayer;
    private $session;
    const SESSION_USER = 'user';

    public function __construct(Datalayer $dataLayer, Session $session) {
        $this->dataLayer = $dataLayer;
        $this->session = $session;
    }

    public function authenticate($userName, $password) {
        $user = $this->dataLayer->getUserForUsernameAndPassword($userName, $password);
        if ($user != null) {
            $this->session->storeValue(self::SESSION_USER, $user->getId());
            return true;
        } else {
          self::signOut();
          return false;
        }

    }

    public function signOut() {
      $this->session->deleteValue(self::SESSION_USER);
    }

    public function isAuthenticated() {
      return $this->session->hasValue(self::SESSION_USER);
    }

    public function getAuthenticatedUser() {
      return $this->dataLayer->getUser($this->session->getValue(self::SESSION_USER), null);
    }
}
