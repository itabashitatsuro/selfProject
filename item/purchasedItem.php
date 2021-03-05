<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../models/db.php");
require_once('../models/product.php');
require_once('../models/image.php');

session_start();

//ログイン画面を経由しているか確認
if (!isset($_SESSION['login'])) {
  header("Location: /sample_app/users/login.php");
  exit;
}

try {
  $purchaseItem = new Product($host, $dbname, $user, $pass);
  $purchaseItem->connectDB();

  // ユーザー出品商品の参照
  $show = $purchaseItem->purchaseHistory($_GET['id']);

  // 画像参照（商品IDが同じものの中から最小のものを抽出）
  $image = new Image($host, $dbname, $user, $pass);
  $image->connectDB();
  
  $minImg = array();
  for($i=0; $i<count($show); $i++) {
    $minImgNum = $image->MIN($show[$i]['id']);
    foreach($minImgNum as $num) {
      $minImg[] = $num[0];
    }
  }

} catch(PDOException $e) {
  exit('接続失敗' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/purchasedItem.css">
</head>

<body>

  <?php if($_SESSION['login']['role'] != 0): ?>
    <header>
      <div id="menu">
        <a id="top" href="../index.php">管理者ページ</a>
        <a id="user" href="../admin/user.php?id=<?= $_GET['id']?>">ユーザーページ</a>
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
    <div id="head-line">
      <h2>購入した商品の一覧</h2>
    </div>
      
    <div id="purchased_item">
      <?php foreach($show as $list):?>
        <div id="box">
          <a href="purchasedItem_show.php?item_id=<?= $list['id']?>&user_id=<?= $_SESSION['login']['id']?>">
            <div id="itemInfo">
              <div id="img">
                <?php
                  try {
                    $img = $image->imageId($list['id']);
                  } catch(PDOException $e) {
                    exit('接続失敗' . $e->getMessage());
                  }
                ?> 
                <?php foreach($img as $menu):?>
                  <?php if($menu['product_id'] == $list['id']): ?>
                    <?php for($i=0; $i<count($menu); $i++): ?>
                      <?php if($minImg[$i] == $menu['id']): ?>
                        <img class="img" src="../images/<?= $menu['img']; ?>">
                      <?php endif; ?>
                    <?php endfor;?>
                  <?php endif; ?>
                <?php endforeach;?>
              </div>
              <div class="text">
                <p><?= $list['name'];?></p>
                <p><?= $list['purchased_at'];?></p>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach;?>

      <?php if($_SESSION['login']['role'] != 0): ?>
        <div id="btn">
          <a class="btn" href="../admin/user.php?id=<?= $_GET['id']?>">戻る</a>
        </div>
      <?php else: ?>
        <div id="btn">
          <a class="btn" href="../user/user.php">戻る</a>
        </div>
      <?php endif;?>
      
    </div>
  </div>
</body>