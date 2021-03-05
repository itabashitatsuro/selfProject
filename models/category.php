<?php
require_once('connect.php');

class Category extends DB {

  public function findAll() {
    $sql = "SELECT * FROM categories";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }
}