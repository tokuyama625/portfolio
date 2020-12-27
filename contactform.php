<?php
$kind     = array();
$kind[1]  = '質問';
$kind[2]  = 'ご意見';
$kind[3]  = '資料請求';
session_start();
$mode = 'input';
$errmessage = array();
if( isset($_POST['back']) && $_POST['back'] ){
  // 何もしない
} else if( isset($_POST['confirm']) && $_POST['confirm'] ){
  // 確認画面
  if( !$_POST['fullname'] ) {
    $errmessage[] = "名前を入力してください";
  } else if( mb_strlen($_POST['fullname']) > 100 ){
    $errmessage[] = "名前は100文字以内にしてください";
  }
  $_SESSION['fullname']	= htmlspecialchars($_POST['fullname'], ENT_QUOTES);

  if( !$_POST['email'] ) {
    $errmessage[] = "Eメールを入力してください";
  } else if( mb_strlen($_POST['email']) > 200 ){
    $errmessage[] = "Eメールは200文字以内にしてください";
  } else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
    $errmessage[] = "メールアドレスが不正です";
  }
  $_SESSION['email']	= htmlspecialchars($_POST['email'], ENT_QUOTES);

  if( !$_POST['mkind'] ) {
    $errmessage[] = "種別を入力してください";
  } else if( $_POST['mkind'] <= 0 || $_POST['mkind'] >= 4 ){
    $errmessage[] = "種別が不正です";
  }
  $_SESSION['mkind']	= htmlspecialchars($_POST['mkind'], ENT_QUOTES);

  if( !$_POST['message'] ){
    $errmessage[] = "お問い合わせ内容を入力してください";
  } else if( mb_strlen($_POST['message']) > 500 ){
    $errmessage[] = "お問い合わせ内容は500文字以内にしてください";
  }
  $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

  if( $errmessage ){
    $mode = 'input';
  } else {

    $token = bin2hex(random_bytes(32));  // php7以降
    $_SESSION['token']  = $token;
    $mode = 'confirm';
  }
} else if( isset($_POST['send']) && $_POST['send'] ){
  // 送信ボタンを押したとき
  if( !$_POST['token'] || !$_SESSION['token'] || !$_SESSION['email'] ){
    $errmessage[] = '不正な処理が行われました';
    $_SESSION     = array();
    $mode         = 'input';
  } else if( $_POST['token'] != $_SESSION['token'] ){
    $errmessage[] = '不正な処理が行われました';
    $_SESSION     = array();
    $mode         = 'input';
  } else {
    $message = "お問い合わせを受け付けました \r\n"
               . "名前: " . $_SESSION['fullname'] . "\r\n"
               . "email: " . $_SESSION['email'] . "\r\n"
               . "種別: " . $kind[ $_SESSION['mkind'] ] . "\r\n"
               . "お問い合わせ内容:\r\n"
               . preg_replace( "/\r\n|\r|\n/", "\r\n", $_SESSION['message'] );
    mail( $_SESSION['email'], 'お問い合わせありがとうございます', $message );
    mail( 'tokuyama625@yahoo.co.jp', 'お問い合わせありがとうございます', $message );
    $_SESSION = array();
    $mode     = 'send';
  }
} else {
  $_SESSION['fullname'] = "";
  $_SESSION['email']    = "";
  $_SESSION['mkind']    = "";
  $_SESSION['message']  = "";
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="contactform.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <title>徳山のポートフォリオサイト</title>
</head>
<body>
    <header>
    <div class="mask" id="mask"></div>
    <div id="page_top"><a href="#"></a></div>
        <div class="main_rogo">
            <img src="img/rogo.png" alt="ロゴ">
        </div>
        <h1>ポートフォリオサイト</h1>
          <a class="menubutton" id="menubutton">
            <div></div>
            <div></div>
            <div></div>
        </a>
    </header>
    <nav id="nav">
        <ul>
            <li><a href="index.html" class="btn-square">HOME</a></li>
            <li><a href="seo.html" class="btn-square">SEO</a></li>
            <li><a href="JavaScript.html" class="btn-square">JavaScript・jQuery</a></li>
            <li><a href="php.php" class="btn-square">PHP</a></li>
        </ul>
    </nav>
    <main>
<?php if( $mode == 'input' ){ ?>
  <!-- 入力画面 -->
  <?php
  if( $errmessage ){
    echo '<div class="alert alert-danger" role="alert">';
    echo implode('<br>', $errmessage );
    echo '</div>';
  }
  ?>
  <div class="Form">
    <form action="./contactform.php" method="post">
      <div class="Form-Item">
        <p class="Form-Item-Label">
          <span class="Form-Item-Label-Required">必須</span>名前
        </p>
        <input type="text"name="fullname" value="<?php echo $_SESSION['fullname'] ?>"class="Form-Item-Input" placeholder="例）徳山　太郎">
      </div>
      <div class="Form-Item">
        <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>メールアドレス</p>
        <input type="email"name="email"value="<?php echo $_SESSION['email'] ?>"class="Form-Item-Input" placeholder="例）tokuyama@gmail.com">
      </div>
      <div class="Form-Item">
        <p class="Form-Item-Label">
          <span class="Form-Item-Label-Required">必須</span>種別
        </p>
        <select name="mkind" required class="Form-Item-Input">
          <?php foreach( $kind as $i => $v ){ ?>
            <?php if( $_SESSION['mkind'] == $i ) { ?>
              <option value="<?php echo $i ?>" selected><?php echo $v ?></option>
            <?php } else { ?>
              <option value="<?php echo $i ?>" ><?php echo $v ?></option>
            <?php } ?>
          <?php } ?>
        </select><br>
      </div>
      <div class="Form-Item">
        <p class="Form-Item-Label isMsg"><span class="Form-Item-Label-Required">必須</span>お問い合わせ内容</p>
        <textarea cols="40" rows="8" name="message"class="Form-Item-Textarea"><?php echo $_SESSION['message'] ?></textarea>
      </div>
          <input type="submit" name="confirm" value="確認" class="Form-Btn"/>
    </form>
  </div>
   <p>※開発環境がXAMPPの為、SMTPサーバーとphp.iniの設定をしていなのでメールは送れません。</p>
<?php } else if( $mode == 'confirm' ){ ?>
  <!-- 確認画面 -->
<div class="Form">
  <form action="./contactform.php" method="post">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
      <div class="Form-Item">
        <p class="Form-Item-Label">
          名前
        </p>
        <?php echo $_SESSION['fullname'] ?>
      </div>
      <div class="Form-Item">
        <p class="Form-Item-Label">
          Eメール
        </p>
        <?php echo $_SESSION['email'] ?>
      </div>
      <div class="Form-Item">
        <p class="Form-Item-Label">
          種別
        </p>
        <?php echo $kind[ $_SESSION['mkind'] ] ?>
      </div>
      <div class="Form-Item">
        <p class="Form-Item-Label">
          お問い合わせ内容
        </p>
        <?php echo nl2br($_SESSION['message']) ?>
      </div>
    <div class="button_box">
      <input type="submit" name="back" value="戻る" class="Form-Btn"/>
      <input type="submit" name="send" value="送信" class="Form-Btn"/>
    </div>
  </form>
</div>
<?php } else { ?>
  <!-- 完了画面 -->
  <p class="typing_text">送信しました。お問い合わせありがとうございました。</p>
<?php } ?>
    </main>
    <footer>
        <p>Copyright(c)2020 tokuyama.all Rights Reserved.</p>
    </footer>
    <script src="style.js"></script>
</body>
</html>
