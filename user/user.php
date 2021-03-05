<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../models/db.php");
require_once('../models/product.php');
require_once('../models/image.php');

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

try {
  // 商品データベース接続
  $product = new Product($host, $dbname, $user, $pass);
  $product->connectDB();
  $sell = new Product($host, $dbname, $user, $pass);
  $sell->connectDB();

  // 画像データベース接続
  $img = new Image($host, $dbname, $user, $pass);
  $img->connectDB();

  if($_POST) {
    $message = $product->validate($_POST);
    
    if(!empty($message)) {
      $error = $message;
    } else {

      // 画像不正チェック
      if (!isset($_FILES['image']['error'])) {
        
        // ①$_FILES['image']['error'] の値を確認
        switch ($_FILES['image']['error']) {
          case UPLOAD_ERR_OK: // OK
            break;
          case UPLOAD_ERR_NO_FILE:   // ファイル未選択
            throw new RuntimeException('ファイルが選択されていません');
          case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過 (設定した場合のみ)
            throw new RuntimeException('ファイルサイズが大きすぎます');
          default:
            throw new RuntimeException('その他のエラーが発生しました');
        }
        
        // ②ここで定義するサイズ上限のオーバーチェック
        if ($_FILES['image']['size'] > 1000000) {
          throw new RuntimeException('ファイルサイズが大きすぎます');
        }

        // ③$_FILES['image']['mime']の偽装を防ぐ
        if (!$ext = array_search(
          mime_content_type($_FILES['image']['tmp_name']),
          array(
              'gif' => 'image/gif',
              'jpg' => 'image/jpeg',
              'png' => 'image/png',
          ),
          true
        )) {
          throw new RuntimeException('ファイル形式が不正です');
        }
      }

      // 画像保存
      else {
        // 商品出品
        $product->add($_POST);
        if($_POST) {
          $sold = $sell->sellItemId($_POST['seller_id']);
          foreach($sold as $s) {
            for($i= 0; $i < count($_FILES["image"]["name"]); $i++ ){
              if(is_uploaded_file($_FILES["image"]["tmp_name"][$i])){
                move_uploaded_file($_FILES["image"]["tmp_name"][$i], "../images/" . $_FILES["image"]["name"][$i]);
                $arr = array('product_id' => $s['id'], 'img' => $_FILES['image']['name'][$i]);
                $img->add($arr);
              }
            }
          }
        }
      }
    }
  }
} 
catch (PDOException $e) {
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
  <link rel="stylesheet" type="text/css" href="../css/user.css">
</head>

<body>
  <?php if($_POST):?>
    <script>alert('商品を出品しました！');</script>
  <?php endif;?>

  <header>
    <div id="menu">
      <a id="top" href="../index.php">フリマアプリサイト</a>
      <a id="logout" href="?logout=0">ログアウト</a>
    </div>
  </header>

  <div id="link">
    <ul>
      <a href="profile.php?id=<?= $result['login']['id'] ?>"><li>個人情報（プロフィール）</li></a>
      <a href="../card/create-customer/create.php?id=<?= $result['login']['id'] ?>"><li>決済情報</li></a>
      <a href="../item/sellItem.php?id=<?= $result['login']['id'] ?>"><li>出品商品一覧</li></a>
      <a href="../news.php?id=<?= $result['login']['id'] ?>"><li>お知らせ</li></a>
      <a href="../item/purchasedItem.php?id=<?= $result['login']['id'] ?>"><li>購入商品一覧</li></a>
      <a href="../question.php?id=<?= $result['login']['id'] ?>"><li>Q＆A</li></a>
    </ul>
  </div>

  <div id="btn"> 
    <a class="btn" href="../item/sell.php?id=<?= $result['login']['id'] ?>">出品する</a>
  </div>
  
</body>