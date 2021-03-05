<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

require_once("../models/db.php");
require_once('../models/user.php');

session_start();

//ログイン画面を経由しているか確認
if (!isset($_SESSION['login'])) {
  header("Location: /selfProject/login.php");
  exit;
}

try {
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();
  $result = $user->findById($_GET['edit']);

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
  <link rel="stylesheet" type="text/css" href="../css/profileEdit.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
</head>

<body>
  <div id="wrapper">
    <div id="head-line">
      <h2>変更・修正内容確認してください</h2>
    </div>

    <form id="form" name="form" action="profileUpdate.php" method="POST">
      <?php foreach($result as $key => $info): ?>
        <input class="input" type="hidden" name="id" value="<?= h($info['id']);?>">
        <table>
          <tr>
            <th>
              ユーザーネーム：
            </th>
            <td>
              <input class="name" type="text" name="name" value="<?= h($info['name']);?>">
            </td>
          </tr>
          <tr>
            <th>
              メールアドレス：
            </th>
            <td>
              <input class="email" type="text" name="email" value="<?= h($info['email']);?>">
            </td>
          </tr>
          <tr>
            <th>
              連絡先（電話番号）：
            </th>
            <td>
              <input id="tel" type="text" name="tel" value="<?= h($info['tel']);?>">
            </td>
          </tr>
          <tr>
            <th>
              郵便番号：
            </th>
            <td>
              <input id="post_code" type="text" name="post_code" value="<?= h($info['post_code']);?>">
            </td>
          </tr>
          <tr>
            <th>
              都道府県：
            </th>
            <td>
              <select id="prefecture" name="prefecture" style="margin-left:10px;">
                <option value="<?= h($info['prefecture'])?>"><?= h($info['prefecture'])?></option>
                <option value="北海道">北海道</option>
                <option value="青森県">青森県</option>
                <option value="岩手県">岩手県</option>
                <option value="宮城県">宮城県</option>
                <option value="秋田県">秋田県</option>
                <option value="山形県">山形県</option>
                <option value="福島県">福島県</option>
                <option value="茨城県">茨城県</option>
                <option value="栃木県">栃木県</option>
                <option value="群馬県">群馬県</option>
                <option value="埼玉県">埼玉県</option>
                <option value="千葉県">千葉県</option>
                <option value="東京都">東京都</option>
                <option value="神奈川県">神奈川県</option>
                <option value="新潟県">新潟県</option>
                <option value="富山県">富山県</option>
                <option value="石川県">石川県</option>
                <option value="福井県">福井県</option>
                <option value="山梨県">山梨県</option>
                <option value="長野県">長野県</option>
                <option value="岐阜県">岐阜県</option>
                <option value="静岡県">静岡県</option>
                <option value="愛知県">愛知県</option>
                <option value="三重県">三重県</option>
                <option value="滋賀県">滋賀県</option>
                <option value="京都府">京都府</option>
                <option value="大阪府">大阪府</option>
                <option value="兵庫県">兵庫県</option>
                <option value="奈良県">奈良県</option>
                <option value="和歌山県">和歌山県</option>
                <option value="鳥取県">鳥取県</option>
                <option value="島根県">島根県</option>
                <option value="岡山県">岡山県</option>
                <option value="広島県">広島県</option>
                <option value="山口県">山口県</option>
                <option value="徳島県">徳島県</option>
                <option value="香川県">香川県</option>
                <option value="愛媛県">愛媛県</option>
                <option value="高知県">高知県</option>
                <option value="福岡県">福岡県</option>
                <option value="佐賀県">佐賀県</option>
                <option value="長崎県">長崎県</option>
                <option value="熊本県">熊本県</option>
                <option value="大分県">大分県</option>
                <option value="宮崎県">宮崎県</option>
                <option value="鹿児島県">鹿児島県</option>
                <option value="沖縄県">沖縄県</option>
              </select>
            </td>
          </tr>
          <tr>
            <th>
              市区町村：
            </th>
            <td>
              <input id="city" type="text" name="city" value="<?= h($info['city']);?>">
            </td>
          </tr>
          <tr>
            <th>
              丁目/番地：
            </th>
            <td>
              <input id="city_block" type="text" name="city_block" value="<?= h($info['city_block']);?>">
            </td>
          </tr>
          <tr>
            <th>
              建物/屋号：
            </th>
            <td>
              <input id="building" type="text" name="building" value="<?= h($info['building']);?>">
            </td>
          </tr>
        </table>

        <div id="submit">
          <input class="edit_btn" type="submit" value="変更完了">
          <input class="submit_btn" type="button" onClick="history.back();" value="前のページへ戻る" style="background: #FFCCCC;">
        </div>
      <?php endforeach; ?>
    </form>

  </div>
</body>
</html>