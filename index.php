<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("models/db.php");
require_once('models/product.php');
require_once('models/image.php');
require_once('models/category.php');

session_start();

//ログアウト
if (isset($_GET['logout'])) {
  $_SESSION = array();
  session_destroy();
  header("Location: /selfProject/login.php");
  exit;
}

//ログイン画面を経由しているか確認
if(!isset($_SESSION['login'])) {
  header("Location: /selfProject/login.php");
  exit;
}

//一般ユーザーの場合
if($_SESSION['login']['role'] == 0) {
  $result['login'] = $_SESSION['login'];
} else {
  header("Location: admin/admin.php");
}

try {
  // カテゴリーデータベース接続&参照
  $category = new Category($host, $dbname, $user, $pass);
  $category->connectDB();
  $kinds = $category->findAll();

  $sellItem = new Product($host, $dbname, $user, $pass);
  $sellItem->connectDB();

  $image = new Image($host, $dbname, $user, $pass);
  $image->connectDB();

  // 出品商品の参照
  $show = $sellItem->findAll();
  
  // 画像参照（商品IDが同じものの中から最小のものを抽出）
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
  <link rel="stylesheet" type="text/css" href="css/base.css">
  <link rel="stylesheet" type="text/css" href="css/index.css">
</head>

<body>
  <p>ログインしています</p>
  <header>
    <div id="logo">フリマアプリサイト</div>
    <div id="menu">  
      <ul>
        <li><a href="user/user.php?id=<?= $result['login']['id'] ?>">マイページ</a></li>
        <li><a href="question.php?id<?= $result['login']['id'] ?>">Q & A</a></li>
        <li><a href="?logout=0">ログアウト</a></li>
      </ul>
    </div>
  </header>

  <div id="main">
    <p>検索したいカテゴリを選んでください</p>
    <div>
      <form id="form" name="form" action=""  method="POST">
        <select id="search" name="categories_id">
          <option value="">検索タグ▼</option>
          <?php foreach($kinds as $row): ?>
            <option value="<?= $row['id'];?>"><?= $row['kinds'];?></option>
          <?php endforeach; ?>
        </select>
        <button>検索</button>
      </form>
    </div>

    <?php if(!($_POST)): ?>
      <div class="cate">
        <span style="font-size:24px;font-wight:bold;">全ての出品商品一覧</span>
      </div>
    <?php endif; ?>

    <?php foreach($kinds as $row): ?>
      <?php if($_POST['categories_id'] == $row['id']):?>
        <a id="top" href="index.php">全ての商品を表示する</a>
        <div class="cate">
          検索結果一覧:
          <span style="font-size:24px;font-wight:bold;"><?= $row['kinds'];?></span>
        </div>
      <?php endif;?>
    <?php endforeach; ?>
    
    <div id="item_images">
      <?php foreach($show as $list):?>
        <!-- カテゴリ検索結果 -->
        <?php if($_POST['categories_id'] == $list['categories_id']):?>

          <div class="item_image"> 
            <?php if($list['purchased_at'] != null):?>
              <h6>SOLD OUT</h6>
            <?php endif;?>
            <a class="size" href="item/showItem.php?item_id=<?= $list['id']?>&user_id=<?= $_SESSION['login']['id']?>"> 
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
                      <img src="images/<?= $menu['img']; ?>" style="width:100%; height:100%">
                    <?php endif; ?>
                  <?php endfor;?>
                <?php endif; ?>
              <?php endforeach;?>
            </a>
          </div>
        <?php endif; ?>

        <!-- 全商品一覧 -->
        <?php if(!($_POST)): ?>
          <div class="item_image"> 
            
            <?php if($list['purchased_at'] != null):?>
              <h6>SOLD OUT</h6>
            <?php endif;?>
            
            <a href="item/showItem.php?item_id=<?= $list['id']?>&user_id=<?= $_SESSION['login']['id']?>"> 
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
                      <img src="images/<?= $menu['img']; ?>" style="width:100%; height:100%">
                    <?php endif; ?>
                  <?php endfor;?>
                <?php endif; ?>
              <?php endforeach;?>
              

            </a>

          </div>
        <?php endif;?>
      <?php endforeach;?>
    </div>
  </div>

</body>
</html>