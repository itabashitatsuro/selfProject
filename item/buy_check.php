<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../models/db.php");
require_once('../models/product.php');
require_once('../models/category.php');
require_once('../models/image.php');
require_once('../models/card.php');

// クレカ決済
require_once('../stripe-php/init.php');
\Stripe\Stripe::setApiKey('sk_test_51IM300BQcq8UGFCrDpft3enQasmbCyzJlaY6xSPjI1GHeCtbRuB30nrqQnBI1mwQrPtYneOm2XXKYMFSBMafqyxp00ICNckbyV');

session_start();

//ログイン画面を経由しているか確認
if(!isset($_SESSION['login'])) {
  header("Location: /selfProject/login.php");
  exit;
}

//セッションIDをセットする
if($_SESSION['login']) {
  $result['login'] = $_SESSION['login'];
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

  //ユーザークレカID(Stripeに登録しているID)の参照
  $card = new Card($host, $dbname, $user, $pass);
  $card->connectDB();
  $cardInfo = $card->cardInfo($_GET['user_id']);
  foreach($cardInfo as $key) {
    $stripe_id = $key['stripe_id'];
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
  <link rel="stylesheet" type="text/css" href="../css/buy_check.css">
  <script src="https://js.stripe.com/v3/"></script>
</head>

<body>

  <div id="wrapper">
    <div id="head-line">
      <h2>購入内容を確認してください</h2>
    </div>

    <?php foreach($show as $list):?>
      <div id="sold_info">
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
    
      <p>本当に購入しますか？</p>

      <form id="payment-form" name="form" action="../card/charge-customer/action.php" method="POST">
        <input type="hidden" id="id" name="id" value="<?= $list['id'];?>">
        <input type="hidden" id="id" name="buyer_id" value="<?= $_GET['user_id'];?>">
        <input type="hidden" id="customer-id" name="customer-id" value="<?= $stripe_id?>">
        <input type="hidden" id="amount" name="amount" value="<?= $list['sellPrice'];?>">
        <div id="btn"> 
          <input id="submit" type="submit" value="購入する" onClick="if(!confirm('購入します。よろしいですか？')) return false;" style="background: #FF6600;">
          <input id="submit" type="button" onClick="history.back();" value="戻る" style="background: #FFCCCC;">
        </div>
      </form>
    <?php endforeach;?>
   
  </div>

</body>
</html>