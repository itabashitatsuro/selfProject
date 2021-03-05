<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../models/db.php");
require_once('../models/product.php');
require_once('../models/category.php');
require_once('../models/image.php');

session_start();

if (!$_SERVER['REQUEST_METHOD'] == 'GET') {
  header("Location: sellItem.php");
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

  // 画像データベース接続
  $image = new Image($host, $dbname, $user, $pass);
  $image->connectDB();

  if(isset($_GET)) {
    $show = $product->findById($_GET['id']);
    $img = $image->imageId($_GET['id']);
  }

} catch (PDOException $e) {
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
  <link rel="stylesheet" type="text/css" href="../css/sellItem_Edit.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script src="../js/validation.js" type="text/javascript"></script>
  <script src="../js/sell.js" type="text/javascript"></script>
  <script src="../js/img.js" type="text/javascript"></script>
</head>

<body>

  <div id="wrapper">
  <h2>内容を編集してください</h2>

  <?php foreach($show as $list):?>

    <p>現在の商品画像</p>
      <div id="img">
        <?php foreach($img as $menu):?>
          <?php if($menu['product_id'] == $list['id']): ?>
            <input type="hidden" name="deletefile" id="sendFile" value="<?=$menu['img']?>">
            <div>
              <img class="img" src="../images/<?= $menu['img']; ?>">
            </div>
          <?php endif;?>
        <?php endforeach;?>
      </div>
    
    <form id="form" name="form" action="sellItem.php?id=<?= $_GET['user_id'];?>" method="POST" enctype="multipart/form-data">
      <div id="sell_inputForm">

        <input type="hidden" name="id" value="<?= h($list['id']);?>">

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
          <input id="name" type="text" name="name" value="<?= h($list['name']);?>">
        </div>

        <div class="input">
          <p>カテゴリー選択:</p>
          <select id="categories_id" name="categories_id">
            <option  value="">選択</option>
            <?php foreach($kinds as $row): ?>
              <option value="<?= $row['id'];?>"><?= $row['kinds'];?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input">
          <p>商品の状態:</p>
          <select id="status" name="status">
            <option><?= $list['status'];?></option>
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
          <textarea id="introduce" name="introduce" wrap="hard" cols="60" rows="5"><?= nl2br(h($list['introduce']));?></textarea>
        </div>

        <div class="input">
          <p>配送方法:</p>
          <select id="delivery" name="delivery">
            <option><?= $list['delivery'];?></option>
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
          <input id="userPrice" class="jsPrice" type="text" name="userPrice" value="<?= h($list['userPrice']);?>">
        </div>

        <div class="input">
          <p>出品価格:　<span style="color:red;">※自動計算で入力されます</span></p>
          <input id="sellPrice" class="jsSumPrice" type="text" name="sellPrice" value="" readonly>
        </div>
      </div>
    <?php endforeach;?>

      <div id="btn"> 
        <input id="submit" type="submit" value="編集完了" onClick="if(!confirm('編集を完了します。よろしいですか？')) return false;" style="background: #FF6600;">
        <input id="submit" type="button" onClick="history.back();" value="戻る" style="background: #FFCCCC;">
      </div>
    </form>

  </div>
</body>