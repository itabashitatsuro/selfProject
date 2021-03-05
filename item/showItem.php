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
  <link rel="stylesheet" type="text/css" href="../css/showItem.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
</head>

<body>

  <?php if($_SESSION['login']['role'] != 0): ?>
    <header>
      <div id="menu">
        <a id="top" href="../admin/itemSearch.php">フリマアプリサイト</a>
        <a id="user" href="../admin/user.php?id=<?= $_GET['user_id']?>">ユーザーページ</a>
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

  <div id="wrapper">

    <?php foreach($show as $list):?>
      <div id="sold_info">
        <?php if($list['purchased_at'] != null):?>
          <h1 style="text-align:center;font-size:30px;margin-bottom:20px;">
            SOLD OUT
          </h1>
        <?php endif;?>
        
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

      <?php if($_SESSION['login']['role'] == 0): ?>
        <form id="form" name="form" action="../item/buy_check.php" method="GET">
          <div id="btn"> 
            <?php if($_GET['user_id'] != $list['seller_id']): ?>
              <?php if($list['buyer_id'] == NULL):?>
                <input id= "submit" type="submit" value="購入画面へ" style="background: #FF6600;">
                <input type="hidden" name="item_id" value="<?= $list['id']?>">
                <input type="hidden" name="user_id" value="<?= $_GET['user_id']?>">
              <?php endif;?>
            <?php endif;?>
            <input id="submit" type="button" onClick="history.back();" value="戻る" style="background: #FFCCCC;">
          </div>
        </form>
      <?php endif;?>
    <?php endforeach;?>
  </div>
 
</body>
</html>