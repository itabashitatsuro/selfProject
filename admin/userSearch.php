<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once('../models/db.php');
require_once('../models/user.php');

session_start();
//ログイン画面を経由しているか確認
if(!isset($_SESSION['login'])) {
  header("Location: /selfProject/login.php");
  exit;
}

try {
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDB();
  
  $result = $user->findAll();

} catch (PDOException $e) {
  exit('データベース接続失敗。' . $e->getMessage());
}

?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
<link rel="stylesheet" type="text/css" href="../css/userSearch.css">
</head>
<body>
  
  <header>
    <div id="menu">
      <a id="top" href="../index.php">フリマアプリサイト</a>
      <a id="admin" href="admin.php?id=<?= $_SESSION['login']['id']?>">マイページ</a>
    </div>
  </header>

  <div id="main">

    <table>
      <tr>
        <th>ID</th>
        <th>ユーザーネーム</th>
        <th>メールアドレス</th>
      </tr>
      <?php foreach($result as $all):?>
        <tr>
          <td><a href="user.php?id=<?= $all['id'] ?>"><?= $all['id'] ?></a></td>
          <td><?= $all['name'] ?></td>
          <td><?= $all['email'] ?></td>
       </tr>
      <?php endforeach;?>
    </table>

  </div>
</body>