<?php
require_once('connect.php');

class Image extends DB {
  public function add($arr) {
    $sql = "INSERT INTO 
              images(product_id, img) 
            VALUES 
              (:product_id, :img)";
    $stmt = $this->connect->prepare($sql);
    $params = array(  
      ':product_id' => $arr['product_id'],
      ':img'        => $arr['img'],
    );
    $stmt->execute($params);
  }

  public function imageId($id) {
    $sql = "SELECT * FROM images WHERE product_id = $id";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    return $stmt;
  }

  public function MIN($id) {
    $sql = "SELECT MIN(id) FROM images WHERE product_id = $id";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    return $stmt;
  }

  //削除(DELETE) ※idを受け取る
  public function deleteImage($id) {
    if(isset($id)) {
      $sql = "DELETE FROM images WHERE product_id = :id";
      $stmt = $this->connect->prepare($sql);
      $params = array(':id'=>$id);
      $stmt->execute($params);
    }
  }

}