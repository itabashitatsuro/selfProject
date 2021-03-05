<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../models/db.php");
require_once('../models/product.php');
require_once('../models/user.php');
require_once('../models/image.php');

session_start();

//ログイン画面を経由しているか確認
if (!isset($_SESSION['login'])) {
  header("Location: /sample_app/users/login.php");
  exit;
}

try {
  $image = new Image($host, $dbname, $user, $pass);
  $image->connectDB();
  
  $sellItem = new Product($host, $dbname, $user, $pass);
  $sellItem->connectDB();

  // 出品と画像の削除
  if(isset($_GET['del'])) {
    $sellItem->delete($_GET['del']);
    $image->deleteImage($_GET['del']);// DBから画像削除
    $deleteImage = $image->imageId($_GET['del']);
    foreach($deleteImage as $file) {
      $a = array();
      array_push($a, $file);
      for($y=0; $y<count($a); $y++) {
        if($file['product_id'] == $_POST['id']) {
          unlink('../images/'.$file['img']);// ディレクトリから画像削除
        }
      }
    } 
  }

  // 編集完了
  if(isset($_POST)){

    // ディレクトリから画像削除
    $deleteImage = $image->imageId($_POST['id']);
    foreach($deleteImage as $file) {
      $a = array();
      array_push($a, $file);
      for($y=0; $y<count($a); $y++) {
        if($file['product_id'] == $_POST['id']) {
          unlink('../images/'.$file['img']);
        }
      }
    } 
    // DBから画像削除
    $image->deleteImage($_POST['id']);
    
    // 商品情報の編集完了
    $sellItem->edit($_POST);

    // 画像の追加完了
    $change = $sellItem->findById($_POST['id']);
    foreach($change as $e) {
      for($i = 0; $i < count($_FILES["image"]["name"]); $i++ ){
        if(is_uploaded_file($_FILES["image"]["tmp_name"][$i])){
          move_uploaded_file($_FILES["image"]["tmp_name"][$i], "../images/" . $_FILES["image"]["name"][$i]);
          $arr = array('product_id' => $e['id'], 'img' => $_FILES['image']['name'][$i]);
          $image->add($arr);
        }
      }
    }
    // header('Location: sellItem.php');//二重登録防止用
  }

  // ユーザー出品商品の参照
  if ($_GET['id']) {
    $show = $sellItem->sellHistory($_GET['id']);
  } elseif($_GET['del']) {
    $show = $sellItem->sellHistory($_GET['del']);
  }

  //ユーザー情報の参照
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();

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
  <link rel="stylesheet" type="text/css" href="../css/sellItem.css">
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
      <h2>過去に出品した商品の一覧</h2>
    </div>

    <?php foreach($show as $list):?>
      <div id="sell_item">
        <div id="box">
          <a id="link" href="showItem.php?item_id=<?= $list['id']?>&user_id=<?= $_GET['id']?>">
            <div id="itemInfo">
              <div id="img">
                <?php
                  try {
                    $img = $image->imageId($list['id']);
                  } catch(PDOException $e) {
                    exit('接続失敗' . $e->getMessage());
                  }
                ?> 
                <?php foreach($img as $i):?>
                  <?php if($i['product_id'] == $list['id']): ?>
                    <?php 
                      $minImgNum = $image->MIN($list['id']);
                    ?>
                      <?php foreach($minImgNum as $num): ?>
                        <?php if($i['id'] == $num[0]): ?>
                          <img class="img" src="../images/<?= $i['img']; ?>">
                        <?php endif; ?>
                      <?php endforeach; ?>
                  <?php endif; ?>
                <?php endforeach;?>
              </div>
              <div class="text">
                <p><?= h($list['name']);?></p>
                <p><?= h($list['sell_at']);?></p>
              </div>
            </div>
          </a>
          <?php if($list['buyer_id'] == NULL):?>
            <div id="EditDelete">
              <!-- 編集 -->
              <form id="form" name="form" action="sellItem_Edit.php?id=<?=$list['id']?>" method="GET">
                <div id="submit_btn"> 
                  <input class="edit" type="submit" value="編集">
                  <input type="hidden" name="id" value="<?= $list['id'] ?>">
                  <input type="hidden" name="user_id" value="<?= $_GET['id']?>">
                </div>
              </form>
              <!-- 削除 -->
              <a class="delete" 
                href="?del=<?= $list['id']?>" 
                onClick="if(!confirm('商品名:<?= $list['name']?>を削除しますがよろしいですか？')) return false;">
                削除
              </a>
            </div>
          <?php else: ?>
            <h5 style="margin-left:10px">SOLD OUT</h5>
          <?php endif; ?>
        </div>
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

    <?php if($_POST):?>
      <script>alert('編集が完了しました');</script>
    <?php endif;?>

    <?php if($_GET['del']):?>
      <script>alert('削除しました');</script>
    <?php endif;?>

  </div>
</body>