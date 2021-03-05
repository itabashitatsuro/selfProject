<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../models/db.php");
require_once('../models/product.php');
require_once('../models/category.php');
require_once('../models/image.php');

session_start();

//ログイン画面を経由しているか確認
if(!isset($_SESSION['login'])) {
  header("Location: /selfProject/login.php");
  exit;
}

try {
  // カテゴリーの参照
  $category = new Category($host, $dbname, $user, $pass);
  $category->connectDB();
  $kinds = $category->findAll();

  //出品商品の参照
  $product = new Product($host, $dbname, $user, $pass);
  $product->connectDB();

  //出品画像の参照
  $image = new Image($host, $dbname, $user, $pass);
  $image->connectDB();

  if(isset($_GET)) {
    $show = $product->findById($_GET['item_id']);
    $img = $image->imageId($_GET['item_id']);
  }

} catch(PDOException $e) {
  exit('接続失敗' . $e->getMessage());
}

function h($str){
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/purchasedItem_show.css">
</head>

<body>

  <header>
    <div id="menu">
      <a id="top" href="../index.php">フリマアプリサイト</a>
      <a id="user" href="../user/user.php?id=<?= $_SESSION['login']['id']?>">マイページ</a>
    </div>
  </header>

  <div id="wrapper">
    <?php if($list['purchased_at'] != null):?>
      <h1 style="text-align:center;font-size:30px;margin-bottom:20px;">
        SOLD OUT
      </h1>
    <?php endif;?>

    <?php foreach($show as $list):?>
      <div id="purchased_info">
        <div class="show">
          <p>出品された日:</p>
          <h5><?= $list['sell_at'];?></h5>
        </div>

        <div id="img">
          <?php foreach($img as $menu):?>
            <?php if($menu['product_id'] == $list['id']): ?>
              <img class="img" src="../images/<?= $menu['img']; ?>">
            <?php endif;?>
          <?php endforeach;?>
        </div>
        
        <div class="show">
          <p>商品名:</p>
          <h5><?= h($list['name']);?></h5>
        </div>

        <div class="show">
          <p>カテゴリー:</p>
          <?php foreach($kinds as $row): ?>
            <?php if($row['id'] == $list['categories_id']): ?>
              <h5><?= $row['kinds'];?></h5>
            <?php endif;?>
          <?php endforeach; ?>
        </div>

        <div class="show">
          <p>商品の状態:</p>
          <h5><?= h($list['status']);?></h5>
        </div>

        <p>商品の説明</p>
        <div id="text">
          <div class="text"><?= nl2br(h($list['introduce']));?></div>
        </div>

        <div class="show">
          <p>配送方法:</p>
          <h5><?= h($list['delivery']);?></h5>
        </div>

        <div class="show">
          <p>出品価格:</p>
          <h5><?= h($list['sellPrice']);?></h5>
        </div>
      </div>

      <div id="btn"> 
        <input id="submit" type="button" onClick="history.back();" value="戻る" style="background: #FFCCCC;">
      </div>

    <?php endforeach;?>
  </div>

</body>
</html>