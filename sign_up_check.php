<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
require_once("models/db.php");
require_once('models/user.php');

session_start();

//不正アクセス対策
if(!$_SERVER['REQUEST_METHOD'] === 'POST'){
  header('Location: login.php');
}

// csrf対策(クロス・サイト・リクエスト・フォージェリ)
if (isset($_POST["token"]) && $_POST["token"] === $_SESSION['token']) {
  $_SESSION = $_POST;
} else {
  echo "不正なリクエストです";
}

// XSS対策
function h($str){
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

try {
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();

  if(isset($_POST)) {
    $message = $user->validate($_POST);
  }

} 
catch(PDOException $e) {
  echo $e->getMessage();
  exit;
}
  
$name = h($_POST['name']);
$email = h($_POST['email']);
$password = h($_POST['password']);
$tel = h($_POST['tel']);
$year = h($_POST['year']);
$month = h($_POST['month']);
$day = h($_POST['day']);
$birthday = $year.'/'.$month.'/'.$day;
$post_code = h($_POST['post_code']);
$prefecture = h($_POST['prefecture']);
$city = h($_POST['city']);
$city_block = h($_POST['city_block']);
$building = h($_POST['building']);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" type="text/css" href="css/base.css">
  <link rel="stylesheet" type="text/css" href="css/sign_up_check.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
</head>

<body>
  <!-- エラーメッセージがあるとき -->
  <?php if(!empty($message)): ?> 

    <?php foreach($message as $error): ?>
      <?= '<div style="color:red;">', $error; ?>
    <?php endforeach; ?>

    <div id="btn">
      <a class="btn" href="sign_up.php">戻る</a>
    </div>

  <!-- エラーメッセージがないとき -->
  <?php else: ?>
    <div id="wrapper">
      <div id="head-line">
        <h3>登録する内容はこちらでよろしいですか？</h3>
      </div>
        
      <table>
        <tr>
          <th>
            パスワード：
          </th>
          <td>
            <?= $password; ?>
          </td>
        </tr>
        <tr>
          <th>
            ユーザーネーム：
          </th>
          <td>
            <?= $name; ?>
          </td>
        </tr>
        <tr>
          <th>
            メールアドレス：
          </th>
          <td>
            <?= $email; ?>
          </td>
        </tr>
        <tr>
          <th>
            連絡先（電話番号）：
          </th>
          <td>
            <?= $tel; ?>
          </td>
        </tr>
        <tr>
          <th>
            生年月日：
          </th>
          <td>
            <?= $birthday; ?>
          </td>
        </tr>
        <tr>
          <th>
            郵便番号：
          </th>
          <td>
            <?= $post_code; ?>
          </td>
        </tr>
        <tr>
          <th>
          都道府県：
          </th>
          <td>
            <?= $prefecture; ?>
          </td>
        </tr>
        <tr>
          <th>
            市区町村：
          </th>
          <td>
            <?= $city; ?>
          </td>
        </tr>
        <tr>
          <th>
            丁目/番地：
          </th>
          <td>
            <?= $city_block; ?>
          </td>
        </tr>
        <tr>
          <th>
            建物/屋号：
          </th>
          <td>
            <?= $building; ?>
          </td>
        </tr>
      </table>

      <form id="form" name="form" action="sign_up_good.php" method="POST">

        <!-- データ送信用のinputタグ -->
        <input type="hidden" name="name" value="<?= $name; ?>">
        <input type="hidden" name="email" value="<?= $email; ?>">
        <input type="hidden" name="tel" value="<?= $tel; ?>">
        <input type="hidden" name="password" value="<?= $password; ?>">
        <input type="hidden" name="birthday" value="<?= $birthday; ?>">
        <input type="hidden" name="post_code" value="<?= $post_code; ?>">
        <input type="hidden" name="prefecture" value="<?= $prefecture; ?>">
        <input type="hidden" name="city" value="<?= $city; ?>">
        <input type="hidden" name="city_block" value="<?= $city_block; ?>">
        <input type="hidden" name="building" value="<?= $building; ?>">

        <div id="btn">
          <input 
            type="submit"
            name="submit"
            class="submit"
            value="登録する" >
        </div>
        <div id="btn">
          <a class="btn" href="sign_up.php">戻る</a>
        </div>
      </form>
    </div>
  <?php endif; ?>
</body>
</html>