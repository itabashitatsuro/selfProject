<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

// modelsファイル読み込み
require_once("../../models/db.php");
require_once('../../models/card.php');

session_start();

//ログイン画面を経由しているか確認
if(!isset($_SESSION['login'])) {
  header("Location: /selfProject/login.php");
  exit;
}

//セッションIDをセットする
if($_SESSION['login']) {
  $result['login'] = $_SESSION['login'];
}

//クレジットカードファイル読み込み
require_once('../../stripe-php/init.php');
//アカウントにアクセス
$stripe = new \Stripe\StripeClient(
  'sk_test_51IM300BQcq8UGFCrDpft3enQasmbCyzJlaY6xSPjI1GHeCtbRuB30nrqQnBI1mwQrPtYneOm2XXKYMFSBMafqyxp00ICNckbyV'
);

try {

  //cardテーブル(Stripeに登録しているID)の参照
  $card = new Card($host, $dbname, $user, $pass);
  $card->connectDB();
  $cardInfo = $card->cardInfo($_GET['id']);
  foreach($cardInfo as $key) {
    $customer_id = $key['stripe_id'];
  }

  //カード情報取得
  if ($customer_id != null) {
    $cards = $stripe->customers->allSources(
      $customer_id,
      [
        'object' => 'card',
        'limit' => 3
      ]
    );
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
  <link rel="stylesheet" type="text/css" href="../../css/base.css">
  <link rel="stylesheet" type="text/css" href="../../css/create.css">
  <script src="https://js.stripe.com/v3/"></script>
</head>

<body>

<?php if($_SESSION['login']['role'] != 0): ?>
  <header>
    <div id="menu">
      <a id="top" href="../index.php">フリマアプリサイト</a>
      <a id="user" href="../../admin/user.php?id=<?= $_GET['id']?>">ユーザーページ</a>
    </div>
  </header>
<?php endif;?>

<div id="wrapper">
  <?php if($customer_id != null): ?>
    <h2>クレジットカード情報</h2>
    <table rules="all" border="1">
      <tr>
          <th>ブランド</th>
          <th>番号</th>
      </tr>
      <?php foreach ($cards as $card): ?>
        <tr>
          <td><?= $card->brand ?></td>
          <td>****<?= $card->last4 ?></td>
        </tr>
      <?php endforeach; ?>
    </table>

    <form action="delete.php?user_id=<?= $result['login']['id'] ?>" method="POST" id="delete" style="margin:10px auto;">
      <?php if($_SESSION['login']['role'] == 0): ?>
        <input type="submit" value="削除する" onClick="if(!confirm('削除します。よろしいですか？')) return false;" 
        style="
          width:270px;
          margin:10px auto;
          padding:10px 40px;
        ">
      <?php endif; ?>
      <input type="hidden" value="<?= $result['login']['id']?>" >
    </form>

  <?php else:?>
    <h2>クレジットカード登録</h2>

    <form action="action.php" method="post" id="payment-form">
      <div class="form-row">

        <input type="hidden" name="user_id" value="<?= $_GET['id'] ?>">

        <div id="input-form">
          <h3>[氏名とメールアドレスを入力してください]</h3>
          <div>
            <label for="name" style="margin-right:4px;">名 前:</label>
            <input type="text" id="name" name="name" placeholder="田中　太郎" />
          </div>
          
          <div>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="email@example.com" />
          </div>
        </div>

        <label for="card-element" id="inp">
          [クレジットカード入力]
        </label>
        
        <div id="card-element"  class="card-element">
          <!-- ここにクレジットカード情報入力欄が挿入される -->
        </div>
          <!-- ここにエラーメッセージが表示される -->
        <div id="card-errors" role="alert"></div>

      </div>

      <div id="btn">
        <button id="button">クレジットカード情報を登録する</button>
      </div>
    </form>
  <?php endif;?>

  <?php if($_SESSION['login']['role'] != 0): ?>
    <div id="btn">
      <a class="btn" href="../../admin/user.php?id=<?= $_GET['id']?>">戻る</a>
    </div>
  <?php else: ?>
    <div id="btn">
    <a class="btn" href="../../user/user.php">戻る</a>
    </div>
  <?php endif;?>
</div>
</body>

<script>
    const publicKey = 'pk_test_51IM300BQcq8UGFCr6McSc0YbwCjfbw4Ugkmg0KNkQNoWUZFRiOeV99kdaAYBdh51H7VbCtg6wXqBpb9wwfOHSMkM00E6BZnbYu';

    var stripe = Stripe(publicKey);
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    var style = {
        base: {
            // Add your base input styles here. For example:
            fontSize: '16px',
            color: "#32325d",
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // 入力変更時のリスナー
    // バリデーションメッセージを表示する
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // submit時のリスナー
    // stripeサーバでトークンに変換してからアプリのサーバにポストする
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the customer that there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server.
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }
</script>