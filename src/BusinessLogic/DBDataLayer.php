<?php

namespace BusinessLogic;

use \Domain\Product;
use \Domain\Category;
use \Domain\Manufacturer;
use \Domain\User;
use \Domain\Review;

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
        'SELECT product.id, category, product.name, username, manufacturer.name, COALESCE(AVG(review.rating), 3), COUNT(review.id) FROM product 
           JOIN manufacturer ON (manufacturer.id = manufacturer) 
           JOIN user ON (user.id = product.user)
      LEFT JOIN review ON (product.id = review.product)
          WHERE category = ?
       GROUP BY product.id, category, product.name, username, manufacturer.name',
        function($s) use($categoryId) {$s->bind_param('i', $categoryId);}
      );

    $stat->bind_result($id, $categoryId, $name, $user, $manufacturer, $avg, $count);


    while ($cat = $stat->fetch()) {
      $products[] = new Product($id, $categoryId, $name, $user, $manufacturer, $avg, $count);
    }
    $stat->close();
    $con->close();
    return $products;
  }

  public function getProductsForSearchCriteria($name) {
    $name = "%$name%";
    $products = array();
    $con = $this->getConnection();
    $stat = $this->executeStatement($con,
        'SELECT product.id, category, product.name, username, manufacturer.name, COALESCE(AVG(review.rating), 3), COUNT(review.id) FROM product 
           JOIN manufacturer ON (manufacturer.id = manufacturer) 
           JOIN user ON (user.id = product.user)
      LEFT JOIN review ON (product.id = review.product)
          WHERE product.name LIKE ?
       GROUP BY product.id, category, product.name, username, manufacturer.name',
        function($s) use($name) {$s->bind_param('s', $name);}
      );

    $stat->bind_result($id, $categoryId, $name, $user, $manufacturer, $avg, $count);


    while ($cat = $stat->fetch()) {
      $products[] = new Product($id, $categoryId, $name, $user, $manufacturer, $avg, $count);
    }

    $stat->close();
    $con->close();
    return $products;
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

  public function getReviewForUserId($userId) {
    $reviews = array();
    $con = $this->getConnection();
    $stat = $this->executeStatement($con,
        'SELECT review.id, username, product.name, date, rating, comment
           FROM `review` 
           JOIN product ON (review.product = product.id)
           JOIN user ON (review.user = user.id)
          WHERE review.user = ?',
        function($s) use($userId) {$s->bind_param('i', $userId);}
      );

    $stat->bind_result($id, $user, $product, $date, $rating, $comment);
    while ($cat = $stat->fetch()) {
      $reviews[] = new Review($id, $product, $date, $user, $rating, $comment);
    }
    $stat->close();
    $con->close();
    return $reviews;
  }

  public function createProduct($userId, $category, $name, $manufacturer) {
    $con = $this->getConnection();
    $con->autocommit(false);

    $catId = $this->getCategoryId($con, $category);
    $manuId = $this->getManufacturerId($con, $manufacturer);
    $stat = $this->executeStatement($con,
        'INSERT INTO product (category, name, user, manufacturer) VALUES (?, ?, ?, ?)',
        function($s) use($catId, $name, $userId, $manuId) {$s->bind_param('isii', $catId, $name, $userId, $manuId);}
      );

    $productId = $stat->insert_id;
    $stat->close();

    $con->commit();
    $con->close();

    return $productId;
  }

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


  private function getCategoryId($connection, $name) {
    $stat = $this->executeStatement($connection,
      'SELECT id FROM categories where name = ?',
      function($s) use($name) {$s->bind_param('s', $name);}
    );

    /* Store the result (to get properties) */
    $stat->store_result();
    /* Get the number of rows */
    $numOfRows = $stat->num_rows;

    $stat->bind_result($catId);
    $stat->fetch();
    if ($numOfRows < 1) {
      $insertState = $this->executeStatement($connection, 
        'INSERT INTO categories (name) VALUES (?)', 
        function($s) use($name) {$s->bind_param('s', $name);}
      );
      $catId = $insertState->insert_id;
      $insertState->close();
    }

    $stat->free_result();
    $stat->close();
    return $catId;
  }

  private function getManufacturerId($connection, $name) {
    $stat = $this->executeStatement($connection,
      'SELECT id FROM manufacturer where name = ?',
      function($s) use($name) {$s->bind_param('s', $name);}
    );

    /* Store the result (to get properties) */
    $stat->store_result();
    /* Get the number of rows */
    $numOfRows = $stat->num_rows;

    $stat->bind_result($manuId);
    $stat->fetch();
    if ($numOfRows < 1) {
      $insertState = $this->executeStatement($connection, 
        'INSERT INTO manufacturer (name) VALUES (?)', 
        function($s) use($name) {$s->bind_param('s', $name);}
      );
      $manuId = $insertState->insert_id;
      $insertState->close();
    }

    $stat->free_result();
    $stat->close();
    return $manuId;
  }

}