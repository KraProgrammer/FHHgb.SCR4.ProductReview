<?php

namespace Domain;

class Manufacturer extends Entity
{
  private $name;

  public function getName() {
    return $this->name;
  }

  function __construct($id, $name) {
    parent::__construct($id);
    $this->name = $name;
  }
}
