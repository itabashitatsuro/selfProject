<?php
ini_set('display_errors', 'On');
ini_set("memory_limit", "200M");
error_reporting(E_ALL ^ E_NOTICE);

require_once("../models/db.php");
require_once('../models/user.php');

//不正アクセス対策
if(!$_SERVER['REQUEST_METHOD'] == 'POST'){
  header('Location: user.php');
  exit;
}

try {
  $update = new User($host, $dbname, $user, $pass);
  $update->connectDB();

  //バリデーションチェック
  $message = $update->validateProfile($_POST);
  
  //更新完了
  if(empty($message)) {
    $update->profileUpdate($_POST);
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
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/profileEdit.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
</head>
<body>

<!-- エラーメッセージがある時 -->
  <?php if(!empty($message)): ?>

    <?php foreach($message as $error): ?>
      <?= '<div style="color:red;">', $error; ?>
    <?php endforeach; ?>
    
    <div class="submit">
      <input type="button" onClick="history.back();" value="修正ページに戻る" id="submit_btn">
    </div>

  <!-- データ更新が成功した時 -->
  <?php else: ?>

    <h2>ユーザー登録情報の変更が完了しました</h2>
    <a href="user.php" style="text-align:center;">ユーザーマイページへ戻る</a>

  <?php endif; ?>
</body>