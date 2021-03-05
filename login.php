<?php
require_once("models/db.php");
require_once('models/user.php');

session_start();

//ログイン状態の場合ログイン後のページにリダイレクト
if (isset($_SESSION["login"])) {
  session_regenerate_id(TRUE);
  header("Location: index.php");
  exit();
}

try {
  if($_POST) {
    $login = new User($host, $dbname, $user, $pass);
    $login->connectDB();
  
    $message = $login->loginValidate($_POST);
     
    if(empty($message)) {
      session_regenerate_id(TRUE); //セッションidを再発行
      $result = $login->login($_POST);      
      $_SESSION['login'] = $result;
      header("Location: /selfProject/index.php");
      exit;
      
    } 
  }

} catch (PDOException $e) {
  exit('データベース接続失敗。' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/base.css">
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
  <section>
    <header>
      <div id="title">フリマアプリサイト</div>
    </header>

    <div id="wrapper">
    
      <!-- データ登録失敗の時 -->
      <?php if(!empty($message)): ?>

        <?php foreach($message as $error): ?>
          <h2 style="color:red"><?= $error; ?></h2>
        <?php endforeach; ?>
      
      <?php endif; ?>
      
      <form id="form" action="" method="POST">
     
        <div id="textbox">
          <ul>
            <li>
              ユーザーネーム
            </li>
            <li>
              <input class="name" type="text" name="name" value=""  autocomplete="on">
            </li>
          </ul>
          <ul>
            <li>
              メールアドレス
            </li>
            <li>
              <input class="email" type="email" name="email" value=""  autocomplete="on">
            </li>
          </ul>
          <ul>
            <li>
              パスワード
            </li>
            <li>
              <input id="password" type="password" name="password" value="" inputmode="verbatim" autocomplete="on">
            </li>
          </ul>
        </div>

        <div id="btn">
          <ul>
            <li>
              <input id="login" type="submit" value="ログインする">
            </li>
            <li>
              <a id="sign_up" href="sign_up.php">新規登録</a>
            </li>
          </ul>
        </div>
      
      </form>
    </div>
  </section>  
</body>
</html>