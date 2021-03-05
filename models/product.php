<?php
require_once('connect.php');

class Product extends DB {
  
  // データ参照
  public function findAll() {
    $sql = "SELECT * FROM products";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function findById($id) {
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }


  public function sellItemId($id) {
    $sql = "SELECT
              * 
            FROM 
              products 
            WHERE id = (
              SELECT MAX(id) FROM products WHERE seller_id = :id)
           ";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }

  //商品購入(INSERT)
  public function buy($arr) {
    $sql = "UPDATE
              products
            SET
              id = :id, 
              buyer_id = :buyer_id, 
              purchased_at = :purchased_at
            WHERE
              id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(  
      ':id' => $arr['id'],
      ':buyer_id' => $arr['buyer_id'],
      ':purchased_at'  => date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
  }

  //ユーザー出品商品の参照(SELECT)
  public function sellHistory($id) {
    $sql = "SELECT * FROM products WHERE $id = seller_id";
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  //購入商品の参照(SELECT)
  public function purchaseHistory($id) {
    $sql = "SELECT * FROM products WHERE $id = buyer_id";
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  //商品出品(INSERT)
  public function add($arr) {
    $sql = "INSERT INTO
              products(name, introduce, status, delivery, userPrice, sellPrice, categories_id, seller_id, sell_at)
            VALUES 
              (:name, :introduce, :status, :delivery, :userPrice, :sellPrice, :categories_id, :seller_id, :sell_at)";
    $stmt = $this->connect->prepare($sql);
    $params = array( 
      ':name'          => $arr['name'],
      ':categories_id' => $arr['categories_id'], 
      ':status'        => $arr['status'], 
      ':introduce'     => $arr['introduce'], 
      ':delivery'      => $arr['delivery'],
      ':userPrice'     => $arr['userPrice'],
      ':sellPrice'     => $arr['sellPrice'],  
      ':seller_id'     => $arr['seller_id'],
      ':sell_at'       => $arr['sell_at']
    );
    $stmt->execute($params);
  }

  // 出品時のエラーメッセージ
  public function validate($message) {
    $err = array();
    
    if (empty($_POST["name"])){
      array_push($err, "商品名は必須です");
    }
    if (empty($_POST["introduce"]) ){
      array_push($err, "商品紹介の入力は必須です。");
    } 
    if (empty($_POST["status"])){
      array_push($err, "商品状態の選択は必須です");
    }
    if (empty($_POST["delivery"])){
      array_push($err, "配達方法の選択は必須です");
    }
    if (empty($_POST["userPrice"])){
      array_push($err, "ユーザー設定価格の入力は必須です");
    }
    if (empty($_POST["categories_id"])){
      array_push($err, "カテゴリーの選択は必須です");
    }
    return $err;
  }

  //編集(UPDATE)
  public function edit($arr) {
    $sql = "UPDATE
              products
            SET
             name = :name,
             categories_id = :categories_id,
             status = :status,
             introduce = :introduce,
             delivery = :delivery, 
             userPrice = :userPrice, 
             sellPrice = :sellPrice
            WHERE
              id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':id'            => $arr['id'],
      ':name'          => $arr['name'], 
      ':categories_id' => $arr['categories_id'], 
      ':status'        => $arr['status'],
      ':introduce'     => $arr['introduce'],  
      ':delivery'      => $arr['delivery'],
      ':userPrice'     => $arr['userPrice'],
      ':sellPrice'     => $arr['sellPrice']
    );
    $stmt->execute($params);
  }

  //削除(DELETE) ※idを受け取る
  public function delete($id) {
    if(isset($id)) {
      $sql = "DELETE FROM products WHERE id = :id";
      $stmt = $this->connect->prepare($sql);
      $params = array(':id'=>$id);
      $stmt->execute($params);
    }
  }

}

?>