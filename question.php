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
<link rel="stylesheet" type="text/css" href="css/question.css">
</head>

<body>
  <div id="wrapper">
    <h2>Q & A</h2>
    <div id="text">
      <p>
        Q:
      </p>
      <p>
        A:
      </p>
    </div>
    <div id="text">
      <p>
        Q:
      </p>
      <p>
        A:
      </p>
    </div>
    <div id="text">
      <p>
        Q:
      </p>
      <p>
        A:
      </p>
    </div>
    <div id="text">
      <p>
        Q:
      </p>
      <p>
        A:
      </p>
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