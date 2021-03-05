<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

session_start();

// csrf対策(クロス・サイト・リクエスト・フォージェリ)
$toke_byte = openssl_random_pseudo_bytes(16);
$token = bin2hex($toke_byte);
$_SESSION['token'] = $token;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>フリマアプリ</title>
  <link rel="stylesheet" type="text/css" href="css/base.css">
  <link rel="stylesheet" type="text/css" href="css/sign_up.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
</head>

<body>
  <div id="wrapper">
    <div id="head-line">
      
      <h2>新 規 登 録</h2>
      <h3>登録する情報を入力してください</h3>
      <p style="color:red">※数字や英字は全て半角で入力してください</p>
    </div>

    <form id="form" name="form" action="sign_up_check.php" method="POST">

      <!-- csrf対策用のinputタグ -->
      <input type="hidden" name="token" value="<?= $_SESSION['token']?>">
      
      <table>
        <tr>
          <th>
            パスワード：
          </th>
          <td>
            <input id="password" type="password" name="password" placeholder="8字以上16字以内・英数字含む" size="16">
          </td>
        </tr>
        <tr>
          <th>
            ユーザーネーム：
          </th>
          <td>
            <input class="name" type="text" name="name" placeholder="20字以内で入力してください">
          </td>
        </tr>
        <tr>
          <th>
            メールアドレス：
          </th>
          <td>
            <input class="email" type="text" name="email" placeholder="正しい形式で入力してください">
          </td>
        </tr>
        <tr>
          <th>
            連絡先（電話番号）：
          </th>
          <td>
            <input id="tel" type="text" name="tel" placeholder="ハイフン(-)を除く 例) 09056781234">
          </td>
        </tr>
        <tr>
          <th>
            生年月日：
          </th>
          <td>
            <select id="year" name="year" style="margin-left:10px;">
              <option value="0">----</option>
              <option value="1950">1950</option>
              <option value="1951">1951</option>
              <option value="1952">1952</option>
              <option value="1953">1953</option>
              <option value="1954">1954</option>
              <option value="1955">1955</option>
              <option value="1956">1956</option>
              <option value="1957">1957</option>
              <option value="1958">1958</option>
              <option value="1959">1959</option>
              <option value="1960">1960</option>
              <option value="1961">1961</option>
              <option value="1962">1962</option>
              <option value="1963">1963</option>
              <option value="1964">1964</option>
              <option value="1965">1965</option>
              <option value="1966">1966</option>
              <option value="1967">1967</option>
              <option value="1968">1968</option>
              <option value="1969">1969</option>
              <option value="1970">1970</option>
              <option value="1971">1971</option>
              <option value="1972">1972</option>
              <option value="1973">1973</option>
              <option value="1974">1974</option>
              <option value="1975">1975</option>
              <option value="1976">1976</option>
              <option value="1977">1977</option>
              <option value="1978">1978</option>
              <option value="1979">1979</option>
              <option value="1980">1980</option>
              <option value="1981">1981</option>
              <option value="1982">1982</option>
              <option value="1983">1983</option>
              <option value="1984">1984</option>
              <option value="1985">1985</option>
              <option value="1986">1986</option>
              <option value="1987">1987</option>
              <option value="1988">1988</option>
              <option value="1989">1989</option>
              <option value="1990">1990</option>
              <option value="1991">1991</option>
              <option value="1992">1992</option>
              <option value="1993">1993</option>
              <option value="1994">1994</option>
              <option value="1995">1995</option>
              <option value="1996">1996</option>
              <option value="1997">1997</option>
              <option value="1998">1998</option>
              <option value="1999">1999</option>
              <option value="2000">2000</option>
              <option value="2001">2001</option>
              <option value="2002">2002</option>
              <option value="2003">2003</option>
              <option value="2004">2004</option>
              <option value="2005">2005</option>
              <option value="2006">2006</option>
              <option value="2007">2007</option>
              <option value="2008">2008</option>
              <option value="2009">2009</option>
              <option value="2010">2010</option>
              <option value="2011">2011</option>
              <option value="2012">2012</option>
              <option value="2013">2013</option>
              <option value="2014">2014</option>
              <option value="2015">2015</option>
            </select> 年
            <select id="month" name="month">
              <option value="0">--</option>
              <option value="1">01</option>
              <option value="2">02</option>
              <option value="3">03</option>
              <option value="4">04</option>
              <option value="5">05</option>
              <option value="6">06</option>
              <option value="7">07</option>
              <option value="8">08</option>
              <option value="9">09</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
            </select> 月
            <select id="day" name="day">
              <option value="0">--</option>
              <option value="1">01</option>
              <option value="2">02</option>
              <option value="3">03</option>
              <option value="4">04</option>
              <option value="5">05</option>
              <option value="6">06</option>
              <option value="7">07</option>
              <option value="8">08</option>
              <option value="9">09</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
              <option value="20">20</option>
              <option value="21">21</option>
              <option value="22">22</option>
              <option value="23">23</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28">28</option>
              <option value="29">29</option>
              <option value="30">30</option>
              <option value="31">31</option>
            </select> 日
          </td>
        </tr>
        <tr>
          <th>
            郵便番号：
          </th>
          <td>
            <input id="post_code" type="text" name="post_code" placeholder="ハイフン(-)を除く 例) 0001111">
          </td>
        </tr>
        <tr>
          <th>
            都道府県：
          </th>
          <td>
            <select id="prefecture" name="prefecture" style="margin-left:10px;">
              <option value="">選択</option>
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
            <input id="city" type="text" name="city" value="">
          </td>
        </tr>
        <tr>
          <th>
            丁目/番地：
          </th>
          <td>
            <input id="city_block" type="text" name="city_block" placeholder="例) 3丁目2番地1 又は 3-2-1">
          </td>
        </tr>
        <tr>
          <th>
            建物/屋号：
          </th>
          <td>
            <input id="building" type="text" name="building" placeholder="例) フリマビルディング101">
          </td>
        </tr>
      </table>

      <div id="btn">
        <input class="submit" type="submit" value="登録情報の確認へ">
      </div>
      <div id="btn">
        <a href="login.php" class="btn">戻る</a>
      </div>
    </form>
  </div>
</body>
</html>