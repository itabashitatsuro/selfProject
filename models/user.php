<?php
require_once('connect.php');

class User extends DB {

  // ログイン
  public function login($arr) {
    $sql = "SELECT * from users 
            WHERE name = :name 
            AND email = :email 
            AND password = :password";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':name'    => $arr['name'],  
      ':email'   => $arr['email'],  
      ':password'=> $arr['password']
    );
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  // ログイン時エラーメッセージ
  public function loginValidate($loginMessage) {
    $err = array();
    
    if (empty($_POST["name"])){
      array_push($err, "正しい氏名を入力してください。");
    }
    if (empty($_POST["email"]) ){
      array_push($err, "メールアドレスを入力してください。");
    } 
    elseif (!filter_var($_POST["email"],FILTER_VALIDATE_EMAIL) ){
      array_push($err, "正しいメールアドレスを入力してください。");
    }
    if (empty($_POST["password"])){
      array_push($err, "パスワードを入力してください。");
    }
    elseif (!preg_match("/^[a-z][a-z0-9_]{7,14}$/i", $_POST["password"])) {
      array_push($err, "パスワードは英数入力です");
    }
    return $err;
  }

  //全ユーザー参照(SELECT)
  public function findAll() {
    $sql = "SELECT * FROM users";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  //個別ユーザー参照(SELECT)
  public function findById($id) {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$id);
    $stmt->execute($params);
    return $stmt;
  }

  //ユーザー新規登録(INSERT)
  public function add($arr) {
    $sql = "INSERT INTO 
              users(name, email, password, role, tel, birthday, post_code, prefecture, city, city_block, building)
            VALUES 
              (:name, :email, :password, :role, :tel, :birthday, :post_code, :prefecture, :city, :city_block, :building)";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':name'      => $arr['name'], 
      ':email'     => $arr['email'], 
      ':password'  => $arr['password'], 
      ':role'      => 0, //一般ユーザー用
      ':tel'       => $arr['tel'],
      ':birthday'  => $arr['birthday'], 
      ':post_code' => $arr['post_code'], 
      ':prefecture'=> $arr['prefecture'], 
      ':city'      => $arr['city'], 
      ':city_block'=> $arr['city_block'],
      ':building'  => $arr['building']
    );
    $stmt->execute($params);
    var_dump($params);
  }

  // 新規登録エラーメッセージ
  public function validate($sign_upMessage) {
    $err = array();
    
    if (empty($_POST["name"])){
      array_push($err, "氏名を入力してください。");
    } 
    elseif (mb_strlen($_POST["name"]) > 20) {
      array_push($err, "氏名は20文字以内で入力してください。");
    }

    if (empty($_POST["email"]) ){
      array_push($err, "メールアドレスを入力してください。");
    } 
    elseif ( !filter_var($_POST["email"],FILTER_VALIDATE_EMAIL) ){
      array_push($err, "正しいメールアドレスを入力してください。");
    }

    if (!empty($_POST["tel"]) && !preg_match("/^[0-9０-９]+$/",$_POST["tel"])){
      array_push($err, "正しい電話番号を入力してください");
    }

    if (empty($_POST["password"])){
      array_push($err, "パスワードを入力してください。");
    }
    elseif (mb_strlen($_POST["password"]) > 16 || mb_strlen($_POST["password"]) < 8 ) {
      array_push($err, "パスワードは8文字以上16文字以内で入力してください。");
    }
    elseif (!preg_match("/^[a-z][a-z0-9_]{8,16}$/i", $_POST["password"])) {
      array_push($err, "パスワードは英数入力です");
    }
    if (empty($_POST["year"])){
      array_push($err, "誕生日を入力してください。");
    }
    if (empty($_POST["month"])){
      array_push($err, "誕生日を入力してください。");
    }
    if (empty($_POST["day"])){
      array_push($err, "誕生日を入力してください。");
    }
    if (empty($_POST["post_code"])){
      array_push($err, "郵便番号を入力してください。");
    }
    if (empty($_POST["prefecture"])){
      array_push($err, "都道府県を入力してください。");
    }
    if (empty($_POST["city"])){
      array_push($err, "市区町村を入力してください。");
    }
    if (empty($_POST["city_block"])){
      array_push($err, "丁目/番地を入力してください。");
    }
    return $err;
  }

   //ユーザープロフィール編集(UPDATE)
   public function profileUpdate($arr) {
    $sql = "UPDATE
              users
            SET 
              name = :name, 
              email = :email, 
              tel = :tel,  
              post_code = :post_code, 
              prefecture = :prefecture, 
              city = :city, 
              city_block = :city_block, 
              building = :building
            WHERE 
              id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':id'        => $arr['id'],
      ':name'      => $arr['name'], 
      ':email'     => $arr['email'], 
      ':tel'       => $arr['tel'],
      ':post_code' => $arr['post_code'], 
      ':prefecture'=> $arr['prefecture'], 
      ':city'      => $arr['city'], 
      ':city_block'=> $arr['city_block'],
      ':building'  => $arr['building']
    );
    $stmt->execute($params);
  }

  // ユーザープロフィール変更エラーメッセージ
  public function validateProfile($editMessage) {
    $err = array();
    
    if (empty($_POST["name"])){
      array_push($err, "氏名を入力してください。");
    } 
    elseif (mb_strlen($_POST["name"]) > 20) {
      array_push($err, "氏名は20文字以内で入力してください。");
    }

    if (empty($_POST["email"]) ){
      array_push($err, "メールアドレスを入力してください。");
    } 
    elseif ( !filter_var($_POST["email"],FILTER_VALIDATE_EMAIL) ){
      array_push($err, "正しいメールアドレスを入力してください。");
    }

    if (!empty($_POST["tel"]) && !preg_match("/^[0-9０-９]+$/",$_POST["tel"])){
      array_push($err, "正しい電話番号を入力してください");
    }
    return $err;
  }


} 

?>