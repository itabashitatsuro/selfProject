<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// model読み込み
require_once("../../models/db.php");
require_once('../../models/product.php');

try {
    //出品商品の参照
    $item = new Product($host, $dbname, $user, $pass);
    $item->connectDB();

    if(isset($_POST)) {
      $buy = array('id' => $_POST['id'], 'buyer_id' => $_POST['buyer_id']);
      $item->buy($buy);
    }

} catch(PDOException $e) {
  exit('接続失敗' . $e->getMessage());
}

// クレカ決済
require_once('../../stripe-php/init.php');
\Stripe\Stripe::setApiKey('sk_test_51IM300BQcq8UGFCrDpft3enQasmbCyzJlaY6xSPjI1GHeCtbRuB30nrqQnBI1mwQrPtYneOm2XXKYMFSBMafqyxp00ICNckbyV');

$customerId = $_POST['customer-id'];
$amount     = $_POST['amount'];

$charge = \Stripe\Charge::create([
    'amount'   => $amount,     // 金額
    'currency' => 'jpy',       // 単位
    'customer' => $customerId, // 顧客ID
]);

?>

<script src="https://js.stripe.com/v3/"></script>

<h1>購入しました！</h1>

<div>
    <a href="../../index.php">トップページへ戻る</a>
</div>