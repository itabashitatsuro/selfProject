<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../models/db.php");
require_once('../models/category.php');

session_start();

// //ログイン画面を経由しているか確認
if (!isset($_SESSION['login'])) {
  header("Location: /sample_app/users/login.php");
  exit;
}

//セッションIDをセットする
if($_SESSION['login']) {
  $result['login'] = $_SESSION['login'];
}

$toke_byte = openssl_random_pseudo_bytes(16);
$token = bin2hex($toke_byte);
$_SESSION['token'] = $token;

try {
  // カテゴリーデータベース接続&参照
  $category = new Category($host, $dbname, $user, $pass);
  $category->connectDB();
  $kinds = $category->findAll();

} 
catch (PDOException $e) {
  exit('データベース接続失敗。' . $e->getMessage());
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
  <link rel="stylesheet" type="text/css" href="../css/sell.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script src="../js/validation.js" type="text/javascript"></script>
  <script src="../js/img.js" type="text/javascript"></script>
  <script src="../js/sell.js" type="text/javascript"></script>
</head>

<body>
  <div id="wrapper">
  
  <h2>出品する商品の情報を入力してください</h2>

    <!-- エラーメッセージ -->
    <?php if(!empty($message)): ?>
      <?php foreach($error as $err): ?>
        <h3 style="color:red;"><?= $err; ?></h3>
      <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- フォームタグ -->
    <form id="form" name="form" action="../user/user.php" method="POST" enctype="multipart/form-data">
      <div id="sell_inputForm">
        
        <!-- csrf対策用のinputタグ -->
        <input type="hidden" name="token" value="<?= $_SESSION['token'];?>">

        <input id="seller_id" type="hidden" name="seller_id" value="<?= $_SESSION['login']['id'];?>">

        <div class="input" style="background:#EEEEEE">
          <p>新しい画像をアップロードしてください</p>
          <p>最大4枚までアップロードできます</p>
          <p style="color:red;">＜複数の画像をアップロードする時の注意点＞ </p>
          <p style="font-size:12px;">※　MacOSをお使いの方「command」を押しながら画像を選択してください。 </p>
          <p style="font-size:12px;">※　Windowsをお使いの方「Ctrl」キーを押しながら画像を選択してください。 </p>
          <input id="img" type="file" name="image[]" multiple>
            <ul id="uplist">
            </ul>
        </div>

        <div class="input">
          <p>商品名を入力:</p>
          <input id="name" type="text" name="name" placeholder="40字以内で入力してください">
        </div>

        <div class="input">
          <p>カテゴリー選択:</p>
          <select id="categories_id" name="categories_id">
            <option value="">選択</option>
            <?php foreach($kinds as $row): ?>
              <option value="<?= $row['id'];?>"><?= $row['kinds'];?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input">
          <p>商品の状態:</p>
          <select id="status" name="status">
            <option value="">選択</option>
            <option value="新品、未使用">新品、未使用</option>
            <option value="未使用に近い">未使用に近い</option>
            <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
            <option value="やや傷や汚れあり">やや傷や汚れあり</option>
            <option value="傷や汚れあり">傷や汚れあり</option>
            <option value="全体的に状態が悪い">全体的に状態が悪い</option>
          </select>
        </div>

        <div class="input">
          <p>商品の説明:</p>
          <textarea id="introduce" name="introduce" wrap="hard" cols="60" rows="5" placeholder="300字以内"></textarea>
        </div>

        <div class="input">
          <p>配送方法:</p>
          <select id="delivery" name="delivery">
            <option value="">選択</option>
            <option value="フリマ特別便">フリマ特別便</option>
            <option value="クロネコヤマト">クロネコヤマト</option>
            <option value="佐川急便">佐川急便</option>
            <option value="普通郵便">普通郵便</option>
            <option value="ゆうパック">ゆうパック</option>
          </select>
        </div>

        <div class="input">
          <p>配送料の負担:200円</p>
        </div>

        <div class="input">
          <p>値段を入力:　<span style="color:red;">※半角数字で入力してください</span></p>
          <input id="userPrice" class="jsPrice" type="text" name="userPrice" placeholder="半角入力してください">
        </div>

        <div class="input">
          <p>出品価格:　<span style="color:red;">※自動計算で入力されます</span></p>
          <input id="sellPrice" class="jsSumPrice" type="text" name="sellPrice" value="" readonly>
        </div>
      </div>

      <div id="btn"> 
        <input id="submit" type="submit" value="出品する" onClick="if(!confirm('出品します。よろしいですか？')) return false;" style="background: #FF6600;">
      </div>
      <div id="btn"> 
        <a class="btn" href="../user/user.php?id=<?= $info['id']?> ?>">マイページへ戻る</a>
      </div>
    </form>

  </div>
</body>