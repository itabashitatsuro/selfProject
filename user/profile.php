<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

//modelsの読み込み
require_once("../models/db.php");
require_once('../models/user.php');
session_start();

//ログイン画面を経由しているか確認
if (!isset($_SESSION['login'])) {
  header("Location: /selfProject/login.php");
  exit;
}

try {
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();
  
  if($_GET) {
    $result = $user->findById($_GET['id']);
  }

} catch (PDOException $e) {
  exit('データベース接続失敗。' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/profile.css">
</head>

<body>
  <div id="wrapper">
    <?php if($_SESSION['login']['role'] != 0): ?>
      <header>
        <div id="menu">
          <a id="top" href="../index.php">フリマアプリサイト</a>
          <a id="user" href="../admin/user.php?id=<?= $_SESSION['login']['id']?>">ユーザーページ</a>
        </div>
      </header>
    <?php else: ?>
      <header>
        <div id="menu">
          <a id="top" href="../index.php">フリマアプリサイト</a>
          <a id="user" href="../user/user.php?id=<?= $_SESSION['login']['id']?>">マイページ</a>
        </div>
      </header>
    <?php endif;?>
    
    <div id="head-line">
      <h2>登録情報</h2>
    </div>

    <?php foreach($result as $key => $info): ?>
      <table class="row">
      
        <tr>
          <th>パスワード：</th>
          <td><?= $info['password']?></td>
        </td>
        <tr>
          <th>ユーザーネーム：</th>
          <td><?= $info['name']?></td>
        </tr>
          <th>メールアドレス：</th>
          <td><?= $info['email']?></td>
        <tr>
          <th>連絡先（電話番号）：</th>
          <td><?= $info['tel']?></td>
        </tr>
        <tr>
          <th>生年月日：</th>
          <td><?= $info['birthday']?></td>
        </tr>
        <tr>
          <th>郵便番号：</th>
          <td><?= $info['post_code']?></td>
        </tr>
        <tr>
          <th>都道府県：</th>
          <td><?= $info['prefecture']?></td>
        </tr>
        <tr>
          <th>市区町村：</th>
          <td><?= $info['city']?></td>
        </tr>
        <tr>
          <th>丁目/番地：</th>
          <td><?= $info['city_block']?></td>
        </tr>
        <tr>
          <th>建物/屋号：</th>
          <td><?= $info['building']?></td>
        </tr>
      </table>

      <div id="btn">
        <!-- ユーザーがログインしているのなら 編集できるようにする-->

        <?php if($_SESSION['login']['role'] != 0): ?>
          <a class="btn" href="../admin/user.php?id=<?= $_GET['id']?>">戻る</a>
        <?php else: ?>
          <a class="edit_btn" href="profileEdit.php?edit=<?= $info['id']?>">登録情報の編集はこちら</a>
          <a class="btn" href="user.php">戻る</a>
        <?php endif;?>
      </div>
    <?php endforeach; ?>

  </div>
</body>
</html>