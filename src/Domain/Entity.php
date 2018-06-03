<?php

namespace Domain;

class Entity
{
  private $id;

  public function getId() {
    return $this->id;
  }

  function __construct($id) {
    $this->id = $id;
  }
}
