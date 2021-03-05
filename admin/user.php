<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

session_start();

//ログアウト
if (isset($_GET['logout'])) {
  $_SESSION = array();
  session_destroy();
  header("Location: /selfProject/login.php");
  exit;
}

// //ログイン画面を経由しているか確認
if (!isset($_SESSION['login'])) {
  header("Location: /selfProject/login.php");
  exit;
}

//セッションIDをセットする
if($_SESSION['login']) {
  $result['login'] = $_SESSION['login'];
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/user.css">
</head>

<body>

  <header>
    <div id="menu">
      <a id="top" href="../index.php">フリマアプリサイト</a>
      <a id="logout" href="admin.php?id=<?= $_SESSION['login']['id']?>">管理者ページ</a>
    </div>
  </header>

  <div id="link">
    <ul>
      <a href="../user/profile.php?id=<?= $_GET['id'] ?>"><li>個人情報（プロフィール）</li></a>
      <a href="../card/create-customer/create.php?id=<?= $_GET['id'] ?>"><li>決済情報</li></a>
      <a href="../item/sellItem.php?id=<?= $_GET['id'] ?>"><li>出品商品一覧</li></a>
      <a href="../item/purchasedItem.php?id=<?= $_GET['id'] ?>"><li>購入商品一覧</li></a>
    </ul>
  </div>
  
</body>