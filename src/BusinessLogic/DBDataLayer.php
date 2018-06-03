<?php

namespace BusinessLogic;

use \Domain\Product;
use \Domain\Category;
use \Domain\Manufacturer;
use \Domain\User;

class DBDataLayer implements DataLayer {
  private $server;
  private $userName;
  private $password;
  private $database;

  public function __construct() {
    // TODO make this nicer
    $this->server = 'localhost';
    $this->userName = 'root';
    $this->password = '';
    $this->database = 'productreview';
  }

  public function getCategories() {
    $categories = array();
    $con = $this->getConnection();
    $res = $this->executeQuery($con, 'SELECT id, name FROM categories');

    while ($cat = $res->fetch_object()) {
      $categories[] = new Category($cat->id, $cat->name);
    }

    $res->close();
    $con->close();
    return $categories;
  }

  public function getManufacturer() {
    $manufacturers = array();
    $con = $this->getConnection();
    $res = $this->executeQuery($con, 'SELECT id, name FROM manufacturer');

    while ($cat = $res->fetch_object()) {
      $manufacturers[] = new Manufacturer($cat->id, $cat->name);
    }

    $res->close();
    $con->close();
    return $manufacturers;
  }

  public function getProductsForCategory($categoryId) {
    $products = array();
    $con = $this->getConnection();
    $stat = $this->executeStatement($con,
        'SELECT id, category, name, user, manufacturer FROM product WHERE category = ?',
        function($s) use($categoryId) {$s->bind_param('i', $categoryId);}
      );

    $stat->bind_result($id, $categoryId, $name, $user, $manufacturer);

    while ($cat = $stat->fetch()) {
      $products[] = new Product($id, $categoryId, $name, $user, $manufacturer);
    }

    $stat->close();
    $con->close();
    return $products;
  }

  public function getProductsForSearchCriteria($name) {
    $name = "%$name%";
    $books = array();
    $con = $this->getConnection();
    $stat = $this->executeStatement($con,
        'SELECT id, category, name, user, manufacturer FROM product WHERE name LIKE ?',
        function($s) use($name) {$s->bind_param('s', $name);}
      );

    $stat->bind_result($id, $categoryId, $name, $user, $manufacturer);

    while ($cat = $stat->fetch()) {
      $books[] = new Product($id, $categoryId, $name, $user, $manufacturer);
    }

    $stat->close();
    $con->close();
    return $books;
  }

  public function getUser($id) {
    $user = null;
    $con = $this->getConnection();
    $stat = $this->executeStatement($con,
        'SELECT id, username FROM user WHERE id = ?',
        function($s) use($id) {$s->bind_param('i', $id);}
      );

    $stat->bind_result($id, $userName);

    if ($cat = $stat->fetch()) {
      $user = new User($id, $userName);
    }

    $stat->close();
    $con->close();

    return $user;
  }

  public function getUserForUserNameAndPassword($userName, $password) {
    $user = null;
    $con = $this->getConnection();
    $stat = $this->executeStatement($con,
        'SELECT id, password FROM user WHERE username = ?',
        function($s) use($userName) {$s->bind_param('s', $userName);}
      );

    $stat->bind_result($id, $passwordHash);

    if ($cat = $stat->fetch() && password_verify($password, $passwordHash)) {
      $user = new User($id, $userName);
    }

    $stat->close();
    $con->close();

    return $user;
  }

  // public function createOrder($userId, $bookIds, $nameOnCard, $cardNumber) {
  //   $con = $this->getConnection();
  //   $con->autocommit(false);
  //   $stat = $this->executeStatement($con,
  //       'INSERT INTO orders (userId, creditCardNumber, creditCardHolder) VALUES (?, ?, ?)',
  //       function($s) use($userId, $nameOnCard, $cardNumber) {$s->bind_param('iss', $userId, $nameOnCard, $cardNumber);}
  //     );

  //   $orderId = $stat->insert_id;
  //   $stat.close();
  //   foreach ($bookIds as $bookId) {
  //     $this->executeStatement($con,
  //       'INSERT INTO orderedBooks (orderId, bookId) VALUES (?, ?)',
  //       function($s) use($orderId, $bookId) {$s->bind_param('ii', $orderId, $bookId);}
  //     );
  //   }
  //   $stat->close();
  //   $con->close();

  //   return $orderId;
  // }

  // ==== private helper functions

  private function getConnection() {
    $con = new \mysqli($this->server, $this->userName, $this->password, $this->database);
    if (!$con) {
      die('Unable to connect to database. Error ' . mysqli_connect_error());
    }
    return $con;
  }

  private function executeQuery($connection, $query) {
    $result = $connection->query($query);
    if (!$result) {
      die('Error in query statement ' . $query . ': ' . $connection->error);
    }
    return $result;
  }

  private function executeStatement($connection, $query, $bindFunc) {
    $statement = $connection->prepare($query);
    if (!$statement) {
      die('Error in prepare statement ' . $query . ': ' . $connection->error);
    }
    $bindFunc($statement);
    if (!$statement->execute()) {
      die('Error in execute prepared statement ' . $query . ': ' . $statement->error);
    }
    return $statement;
  }
}
