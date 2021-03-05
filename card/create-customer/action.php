<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../../models/db.php");
require_once('../../models/card.php');

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

//クレジットカード読み込み
require_once('../../stripe-php/init.php');
\Stripe\Stripe::setApiKey('sk_test_51IM300BQcq8UGFCrDpft3enQasmbCyzJlaY6xSPjI1GHeCtbRuB30nrqQnBI1mwQrPtYneOm2XXKYMFSBMafqyxp00ICNckbyV');

$token = $_POST['stripeToken'];
$email = $_POST['email'];
$name  = $_POST['name'];

// 顧客情報(Customer)を作成
$customer = \Stripe\Customer::create([
  'source' => $token, // クレジットカードトークン
  'email'  => $email, // メールアドレス
  'name'   => $name,  // 顧客の名前
]);
$stripe_id = $customer->id;

try {
  //ユーザークレカ登録
  $card = new Card($host, $dbname, $user, $pass);
  $card->connectDB();
  if(isset($_POST)) {
    $cardInfo = array('stripe_id' => $stripe_id, 'user_id' => $_POST['user_id']);
    $card->add($cardInfo);
  }

} catch(PDOException $e) {
  exit('接続失敗' . $e->getMessage());
}

?>

<h2>クレジットカード登録が完了しました</h2>

<div>
  <a href="../../user/user.php?id=<?= $result['login']['id'] ?>">マイページへ戻る</a>
</div>
