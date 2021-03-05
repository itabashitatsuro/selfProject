<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

session_start();

//セッションIDをセットする
if($_SESSION['login']['role'] == 0) {
  $result['login'] = $_SESSION['login'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
<link rel="stylesheet" type="text/css" href="css/base.css">
<link rel="stylesheet" type="text/css" href="css/news.css">
</head>

<body>
  <div id="wrapper">
    <h2>運営からのお知らせ</h2>

    <div id="message">
      <h5>
        2021/××/△△ : 〜について
      </h5>
      <div id="text">
        <p>
          テストテストテスト</br>
          テストテストテスト</br>
          テストテストテスト</br>
          テストテストテスト
        </p>
      </div>
    </div>
    <div id="message">
      <h5>
        2021/××/△△ : キャンペーン実施！！！
      </h5>
      <div id="text">
        <p>
          テストテストテスト</br>
          テストテストテスト</br>
          テストテストテスト</br>
          テストテストテスト
        </p>
      </div>
    </div>
    <div id="message">
      <h5>
        2021/××/△△ : 配送料金改定のお知らせ
      </h5>
      <div id="text">
        <p>
          テストテストテスト</br>
          テストテストテスト</br>
          テストテストテスト</br>
          テストテストテスト
        </p>
      </div>
    </div>
    <div id="message">
      <h5>
        2021/××/△△ : お得な情報
      </h5>
      <div id="text">
        <p>
          テストテストテスト</br>
          テストテストテスト</br>
          テストテストテスト</br>
          テストテストテスト
        </p>
      </div>
    </div>
    
    <div id="btn"> 
      <!-- 管理者かユーザーによって分ける -->
      <?php if($_SESSION['login']['role'] == 0): ?>
        <a class="btn" href="user/user.php">戻る</a>
      <?php else: ?>
        <a class="btn" href="admin/admin.php">戻る</a>
      <?php endif;?>
    </div>
  </div>

</body>