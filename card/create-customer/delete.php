<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../../models/db.php");
require_once('../../models/card.php');

session_start();

//ログイン画面を経由しているか確認
if(!isset($_GET)) {
  header("Location: create.php");
  exit;
}

//セッションIDをセットする
if($_SESSION['login']) {
  $result['login'] = $_SESSION['login'];
}

//クレジットカードファイル読み込み
require_once('../../stripe-php/init.php');
// Stripeアカウントに接続
\Stripe\Stripe::setApiKey('sk_test_51IM300BQcq8UGFCrDpft3enQasmbCyzJlaY6xSPjI1GHeCtbRuB30nrqQnBI1mwQrPtYneOm2XXKYMFSBMafqyxp00ICNckbyV');

try {
  $card = new Card($host, $dbname, $user, $pass);
  $card->connectDB();

  if(isset($_GET)) {
    //カード情報の取得
    $cardInfo = $card->cardInfo($_GET['user_id']);
    foreach($cardInfo as $key) {
      $stripe_id = $key['stripe_id'];
    }

    //顧客情報の削除
    $customer = \Stripe\Customer::retrieve($stripe_id);
 
    if(isset($card) || isset($customer)) {
      \Stripe\Customer::deleteSource(
        $stripe_id,
        $customer['default_source']
      );
    }

    //データベースから削除
    $card->delete($_GET['user_id']);
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
  <link rel="stylesheet" type="text/css" href="../../css/base.css">
  <link rel="stylesheet" type="text/css" href="../../css/delete.css">
  <script src="https://js.stripe.com/v3/"></script>
</head>

<body>

<div id="wrapper">

  <h2>削除が完了しました</h2>

  <div id="btn">
    <a class="btn" href="../../user/user.php">マイページへ戻る</a>
  </div>

</div>
</body>