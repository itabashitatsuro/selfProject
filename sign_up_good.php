<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

require_once("models/db.php");
require_once('models/user.php');

session_start();

//不正アクセス対策
if(!$_SERVER['REQUEST_METHOD'] === 'POST'){
  header('Location: login.php');
}

try {
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();

  // ユーザー登録
  if(isset($_POST)) {
    $user->add($_POST);
  }
} 
catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" type="text/css" href="css/base.css">
  <link rel="stylesheet" type="text/css" href="css/sign_up.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
</head>

<body>
  <p style="marig:20px;">入力が完了しました！</p>
  <div id="btn">
    <a class="btn" href="login.php">ログインページへ戻る</a>
  </div>

</body>
</html>