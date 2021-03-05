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

//ログイン画面を経由しているか確認
if(!isset($_SESSION['login'])) {
  header("Location: /selfProject/login.php");
  exit;
}

//管理者の場合
if($_SESSION['login']['role'] == 1) {
  $result['login'] = $_SESSION['login'];
} else {
  header("Location: /selfProject/index.php");
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
  <link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>

<body>
  <p>（管理者）ログイン中</p>
  <header>
    <div id="head">
      <a id="top" href="../index.php">フリマアプリサイト</a>
      <a id="admin" href="?logout=0">ログアウト</a>
    </div>
  </header>

  <div id="menu">
    <ul>
      <a href="userSearch.php?id=<?= $result['login']['id'] ?>"><li>ユーザー一覧</li></a>
      <a href="itemSearch.php?id=<?= $result['login']['id'] ?>"><li>商品一覧</li></a>
      <a href="../news.php?id=<?= $result['login']['id'] ?>"><li>お知らせ</li></a>
      <a href="../question.php?id=<?= $result['login']['id'] ?>"><li>Q＆A</li></a>
    </ul>
  </div>

</body>