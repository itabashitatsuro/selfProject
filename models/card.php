<?php
require_once('connect.php');

class Card extends DB {
  
  //ユーザー新規登録(INSERT)
  public function add($arr) {
    $sql = "INSERT INTO 
              card(stripe_id, user_id)
            VALUES 
              (:stripe_id, :user_id)";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':stripe_id' => $arr['stripe_id'], 
      ':user_id'     => $arr['user_id'] 
    );
    $stmt->execute($params);
  }

  //個別ユーザー参照(SELECT)
  public function cardInfo($id) {
    $sql = "SELECT * FROM card WHERE user_id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$id);
    $stmt->execute($params);
    // $result = $stmt->fetch();結果セットに返された際のカラム名で添字を付けた配列を返すとエラー発生
    return $stmt;
  }

  //DB削除(DELETE) ※idを受け取る
  public function delete($id) {
    if(isset($id)) {
      $sql = "DELETE FROM card WHERE user_id = :id";
      $stmt = $this->connect->prepare($sql);
      $params = array(':id'=>$id);
      $stmt->execute($params);
    }
  }

} 

?>