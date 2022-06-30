<?php header("Content-Type:text/html;charset=utf-8"); ?>
<?php

// フォームページ内の「名前」と「メール」項目のname属性の値は特に理由がなければ以下が最適です。
// 名前 <input size="20" type="text" name="名前" />　メールアドレス <input size="30" type="text" name="Email" />


//-----------------必須設定　必ず設定してください。-------------

//サイトのトップページのURL　※送信完了後に「トップページへ戻る」ボタンが表示されますので
$site_top = "https://legalsifter.jp";

// 管理者メールアドレス ※メールを受け取るメールアドレス(複数指定する場合は「,」で区切ってください)
$to = "ono@tandemsprint.com,a.katayama@tandemsprint.com,first-tandemsprint@cast-er.com";

//-----------------必須設定　ここまで--------------------------


//------------ 任意設定　以下は必要に応じて設定してください --------------

// このPHPファイルの名前 ※ファイル名を変更した場合は必ずここも変更してください。
$file_name ="mail.php";

// 管理者宛のメールで差出人を送信者のメールアドレスにする(する=1, しない=0)
// する場合は、メール入力欄のname属性の値を「Email」にしてください。例 <input size="30" type="text" name="Email" />
//メーラーなどで返信する場合に便利なので「する」がおすすめです。
$fromAdd = 1;

// 管理者宛に送信されるメールのタイトル（件名）
$sbj = "LegalSifterWEBサイトからのお問い合わせ";

// 送信確認画面の表示(する=1, しない=0)
$confirmDsp = 1;

// 送信完了後に自動的に指定のページ(サンクスページなど)に移動する(する=1, しない=0)
// CV率を解析したい場合などはサンクスページを別途用意し、URLをこの下の項目で指定してください。
// 0にすると、デフォルトの送信完了画面が表示されます。
$jumpPage = 1;

// 送信完了後に表示するページURL（上記で1を設定した場合のみ）※httpから始まるURLで指定ください。
$thanksPage = "https://legalsifter.jp/thanks.html";

// 差出人に送信内容確認メール（自動返信メール）を送る(送る=1, 送らない=0)
// 送る場合は、メール入力欄のname属性の値を「Email」にしてください。例 <input size="30" type="text" name="Email" />
// また差出人に送るメール本文の文頭に「○○様」と表示さたい場合は名前入力欄のname属性を name="名前"としてください
$remail = 1;

// 差出人に送信確認メールを送る場合のメールのタイトル（上記で1を設定した場合のみ）
$resbj = "LegalSifterお問い合わせ確認メール（自動送信）";

//自動返信メールに署名を表示(する=1, しない=0)※管理者宛にも表示されます。
$mailFooterDsp = 0;

//上記で「1」を選択時に表示する署名（FOOTER～FOOTER;の間に記述してください）
$mailSignature = <<< FOOTER

───────────────────────



───────────────────────

FOOTER;

// 必須入力項目を設定する(する=1, しない=0)
$esse = 1;

/* 必須入力項目(入力フォームで指定したname属性の値を指定してください。（上記で1を設定した場合のみ）
値はシングルクォーテーションで囲んで下さい。複数指定する場合は「,」で区切ってください)*/
$eles = array('貴社名','ご担当者名','Email');

//--------------------- 任意設定ここまで -----------------------------------

// 以下の変更は知識のある方のみ自己責任でお願いします。

$sendmail = 0;
foreach($_POST as $key=>$val) {
  if($val == "submit") $sendmail = 1;
}
// 文字の置き換え
$string_from = "＼";
$string_to = "ー";
// 未入力項目のチェック
if($esse == 1) {
  $empty_flag = 0;
  $length = count($eles) - 1;
  foreach($_POST as $key=>$val) {
    $key = strtr($key, $string_from, $string_to);
    if($val == "submit") ;
    else {
      for($i=0; $i<=$length; $i++) {
        if($key == $eles[$i] && empty($val)) {
          $errm .= "<FONT color=#ff0000>「".$key."」は必須入力項目です。</FONT><br>\n";
          $empty_flag = 1;
        }
      }
    }
  }
  foreach($_POST as $key=>$val) {
    $key = strtr($key, $string_from, $string_to);
    for($i=0; $i<=$length; $i++) {
      if($key == $eles[$i]) {
        $eles[$i] = "check_ok";
      }
    }
  }
  for($i=0; $i<=$length; $i++) {
    if($eles[$i] != "check_ok") {
      $errm .= "<FONT color=#ff0000>「".$eles[$i]."」が未選択です。</FONT><br>\n";
      $eles[$i] = "check_ok";
      $empty_flag = 1;
    }
  }
}
// 管理者宛に届くメールのレイアウトの編集
$body="「".$sbj."」\nWEBサイトに以下のお問い合わせがありました。\nご対応・ご返信をお願い致します。\n\n";
$body.="＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
foreach($_POST as $key=>$val) {
  $key = strtr($key, $string_from, $string_to);
  //※追記　チェックボックス（配列）の場合は以下の処理で複数の値を取得するように変更
  $out = '';
  if(is_array($val)){
  foreach($val as $item){ 
  $out .= $item . ','; 
  }
  if(substr($out,strlen($out) - 1,1) == ',') { 
  $out = substr($out, 0 ,strlen($out) - 1); 
  }
 }else { $out = $val;} //チェックボックス（配列）追記ここまで
  if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
  if($out == "submit" or $key == "httpReferer") ;
  else $body.="【 ".$key." 】\n ".$out."\n\n";
}
$body.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
$body.="送信された日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
$body.="送信者のIPアドレス：".$_SERVER["REMOTE_ADDR"]."\n";
$body.="送信者のホスト名：".getHostByAddr(getenv('REMOTE_ADDR'))."\n";
$body.="問い合わせのページURL：".$_POST['httpReferer']."\n";
if($mailFooterDsp == 1) $body.= $mailSignature;
//--- レイアウトの編集終了 --->
if($remail == 1) {
//--- 差出人への送信確認メールのレイアウト
if(isset($_POST['名前'])){ $rebody = "{$_POST['名前']} 様\n\n";}
$rebody.="LegalSifterへのお申し込み・お問い合わせありがとうございました。\n";
$rebody.="確認次第、担当者より返信差し上げますので、\n今しばらくお待ちください。\n\n";
$rebody.="ご送信頂きました内容は以下の通りです。\n\n";
$rebody.="＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
foreach($_POST as $key=>$val) {
  $key = strtr($key, $string_from, $string_to);
  //追記　チェックボックス（配列）の場合は以下の処理で複数の値を取得するように変更
  $out = '';
  if(is_array($val)){
  foreach($val as $item){ 
  $out .= $item . ','; 
  }
  if(substr($out,strlen($out) - 1,1) == ',') { 
  $out = substr($out, 0 ,strlen($out) - 1); 
  }
 }else { $out = $val; }//チェックボックス（配列）追記ここまで
  if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
  if($out == "submit" or $key == "httpReferer") ;
  else $rebody.="【 ".$key." 】\n ".$out."\n\n";
}
$rebody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
$rebody.="送信日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
if($mailFooterDsp == 1) $rebody.= $mailSignature;
$reto = $_POST['Email'];
$rebody=mb_convert_encoding($rebody,"JIS","utf-8");
$resbj="=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($resbj,"JIS","utf-8"))."?=";
$reheader="From: $to\nReply-To: ".$to."\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
}
$body=mb_convert_encoding($body,"JIS","utf-8");
$sbj="=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($sbj,"JIS","utf-8"))."?=";
if($fromAdd == 1) {
  $from = $_POST['Email'];
  $header="From: $from\nReply-To: ".$_POST['Email']."\nContent-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
} else {
  $header="Reply-To: ".$to."\nContent-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
}
if(($confirmDsp == 0 || $sendmail == 1) && $empty_flag != 1){
  mail($to,$sbj,$body,$header);
  if($remail == 1) { mail($reto,$resbj,$rebody,$reheader); }
}
else if($confirmDsp == 1){ 
/*　▼▼▼送信確認画面のレイアウト※編集可　オリジナルのデザインも適用可能▼▼▼　*/
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>LegalSifter</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="Description" content="LegalSifterは英文契約書をレビューするAIシステムです。">
<meta name="Keywords" content="LegalSifter,英文契約書,レビュー,AI">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.18.1/build/cssreset/cssreset-min.css">
<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/legalsifter.css">
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
	$(function(){
		$('.menu').on('click', function() {
		$(this).toggleClass('active');
		$("#nav").toggleClass('active');
		})
	})
	$(function() {
		$('#nav a').on('click', function() {
		$('#nav').toggleClass('active');
		$(".menu").toggleClass('active');
		})
	});
</script>
<script>
	$(document).ready(function() {
	  var pagetop = $('.pagetop');
	    $(window).scroll(function () {
	       if ($(this).scrollTop() > 70) {
	            pagetop.fadeIn();
	       } else {
	            pagetop.fadeOut();
	            }
	       });
	       pagetop.click(function () {
	           $('body, html').animate({ scrollTop: 0 }, 500);
	              return false;
	   });
	});
</script>
</head>

<body>

<div class="PCmenu">
    <a href="index.html"><h1>
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="197.285px" height="32px" viewBox="276.558 143.96 197.285 32" enable-background="new 276.558 143.96 197.285 32" xml:space="preserve">
<g>
	<path fill="#595194" d="M307.493,169.288v-24.827h2.271v22.791h12.453v2.036H307.493z"/>
	<path fill="#595194" d="M326.133,161.145c0,3.681,1.723,6.812,5.952,6.812c2.428-0.078,4.464-1.725,4.934-4.15h2.193
		c-0.626,3.76-4.072,6.344-7.832,6.03c-5.169,0-7.597-4.465-7.597-9.164c0-4.699,2.584-9.163,7.753-9.163
		c5.796,0,7.989,4.229,7.989,9.635H326.133L326.133,161.145z M337.254,159.264c-0.235-3.368-2.036-5.874-5.639-5.874
		c-3.603,0-5.169,2.819-5.404,5.874H337.254z"/>
	<path fill="#595194" d="M355.738,151.979h2.115c0,1.175-0.157,2.585-0.157,3.917v10.417c0,3.445,0.157,7.049-3.681,8.851
		c-1.253,0.627-2.584,0.86-3.916,0.783c-3.211,0-6.814-1.097-6.814-4.621h2.271c0.235,2.036,2.663,2.741,4.855,2.741
		c2.741,0.078,5.091-2.036,5.248-4.777v-3.211c-1.175,2.036-3.29,3.211-5.639,3.211c-5.169,0-7.44-3.994-7.44-8.693
		c0-4.699,2.115-9.007,7.362-9.007c2.35,0,4.464,1.175,5.717,3.211v-0.157L355.738,151.979L355.738,151.979z M350.099,153.311
		c-3.759,0-5.326,3.759-5.326,6.971c0,3.212,1.566,6.97,5.404,6.97c3.838,0,5.482-3.682,5.482-7.049c0-3.368-1.88-6.813-5.561-6.813
		L350.099,153.311L350.099,153.311z"/>
	<path fill="#595194" d="M372.263,157.149c-0.235-2.82-1.566-3.759-4.073-3.759c-2.193,0-3.994,0.626-4.229,2.976h-2.193
		c0.548-3.524,3.211-4.856,6.5-4.856c3.837,0,6.187,1.723,6.109,5.717v8.224c0,1.331,0.078,2.74,0.157,3.915h-2.115v-2.584
		l-0.079,0.233c-1.253,1.803-3.211,2.82-5.404,2.82c-2.898,0.234-5.561-1.88-5.795-4.855c-0.235-2.429,1.253-4.7,3.524-5.482
		c2.271-1.018,5.326-0.626,7.832-0.783L372.263,157.149z M367.094,167.879c4.464,0,5.326-3.76,5.169-7.361
		c-2.898,0.078-9.085-0.471-9.085,3.916c0,1.879,1.566,3.445,3.446,3.445H367.094z"/>
	<path fill="#595194" d="M381.427,169.288h-2.036v-24.827h2.036V169.288z"/>
	<path fill="#595194" d="M390.512,161.378c-0.392,2.192,1.098,4.308,3.289,4.699c0.393,0.078,0.783,0.078,1.254,0
		c2.897,0,4.776-1.331,4.776-3.446c0-2.113-1.566-2.584-4.776-3.367c-5.953-1.488-9.164-3.211-9.164-7.519
		c0-4.307,2.897-7.753,9.868-7.753c2.819-0.235,5.562,0.862,7.52,2.898c1.019,1.332,1.487,2.976,1.41,4.621h-5.17
		c0.078-2.036-1.565-3.759-3.604-3.837h-0.47c-2.506,0-4.072,1.331-4.072,3.368c0,2.271,1.801,2.741,5.404,3.603
		c4.307,1.018,8.536,2.193,8.536,7.048c0,4.465-3.76,8.066-10.573,8.066c-9.555,0-9.555-6.107-9.633-8.535L390.512,161.378
		L390.512,161.378z"/>
	<path fill="#595194" d="M408.916,144.461h5.404v4.464h-5.404V144.461z M409.073,151.979h5.091v17.309h-5.091V151.979z"/>
	<path fill="#595194" d="M419.02,155.583h-2.584v-3.603h2.584v-2.428c0-3.054,1.02-5.561,5.952-5.561
		c0.939,0,1.803,0.078,2.741,0.157v3.524c-0.312-0.079-0.705-0.079-1.097-0.079c-2.584,0-2.584,1.332-2.584,2.271v2.036h3.211v3.603
		h-3.211v13.784h-5.014L419.02,155.583L419.02,155.583z"/>
	<path fill="#595194" d="M441.653,169.055c-1.253,0.391-2.584,0.547-3.994,0.547c-5.091,0-5.091-3.211-5.091-4.699v-9.477h-3.367
		v-3.446h3.289v-3.368l5.091-1.879v5.169h4.151v3.446h-4.151v7.832c0,1.566,0,2.584,2.271,2.584c0.549,0,1.176,0,1.724-0.078
		L441.653,169.055z"/>
	<path fill="#595194" d="M448.232,161.927c0,1.097,0,4.62,3.523,4.62c1.488,0.078,2.819-0.939,3.055-2.428h4.62
		c-0.078,1.41-0.626,2.74-1.564,3.76c-1.646,1.487-3.838,2.191-6.109,2.036c-2.271,0.078-4.464-0.705-6.107-2.192
		c-1.566-1.881-2.43-4.309-2.271-6.813c-0.235-3.211,1.097-6.266,3.603-8.224c1.41-0.94,3.134-1.488,4.855-1.41
		c2.977-0.157,5.718,1.566,7.049,4.229c0.861,2.037,1.253,4.151,1.02,6.344L448.232,161.927z M454.654,158.715
		c0-0.783,0-3.916-3.055-3.916c-2.271,0-3.211,1.879-3.29,3.916H454.654L454.654,158.715z"/>
	<path fill="#595194" d="M463.27,156.209c0-0.861-0.155-3.368-0.233-4.308h4.776l0.078,3.368c0.627-1.488,1.802-3.603,5.952-3.368
		v4.621c-5.169-0.47-5.481,2.193-5.481,4.308v8.536h-5.092V156.209L463.27,156.209z"/>
	<path fill="#595194" d="M302.716,157.07c0,7.206-5.874,13.079-13.079,13.079c-7.205,0-13.079-5.795-13.079-13.079
		c0-7.205,5.874-13.079,13.079-13.079C296.842,143.991,302.716,149.865,302.716,157.07z M292.3,160.673h3.837
		c0.313,0.862,1.175,1.254,2.036,0.94c0.862-0.313,1.253-1.175,0.94-2.037c-0.313-0.862-1.175-1.253-2.036-0.94
		c-0.47,0.157-0.783,0.548-0.94,0.94h-4.308l-3.837,4.464c-0.783-0.313-1.723,0.078-2.037,0.938
		c-0.313,0.783,0.079,1.725,0.94,2.037c0.783,0.312,1.723-0.078,2.037-0.939c0.156-0.471,0.156-0.939-0.079-1.331
		C288.854,164.746,292.299,160.673,292.3,160.673z M290.968,156.366l-6.109-7.127c0.392-0.783,0.078-1.723-0.705-2.115
		c-0.783-0.392-1.723-0.078-2.115,0.705c-0.392,0.783-0.078,1.723,0.705,2.114c0.392,0.235,0.94,0.235,1.332,0l5.482,6.344
		l-6.188,7.283c-0.861-0.312-1.723,0.156-2.036,0.939c-0.313,0.861,0.157,1.723,0.94,2.036c0.861,0.313,1.723-0.155,2.036-0.938
		c0.157-0.471,0.078-0.94-0.157-1.332L290.968,156.366z M286.112,156.366l-3.916-4.542c0.235-0.548-0.078-1.097-0.548-1.332
		c-0.548-0.157-1.096,0.079-1.331,0.626c-0.235,0.548,0.078,1.097,0.548,1.332c0.156,0.078,0.313,0.078,0.47,0.078l3.368,3.916
		l-4.542,5.326c-0.548-0.078-1.097,0.313-1.175,0.861c-0.078,0.549,0.313,1.098,0.861,1.176c0.548,0.078,1.097-0.312,1.175-0.861
		v-0.471L286.112,156.366z M298.722,155.817h-3.211l-4.542-5.247c0.392-0.783,0.157-1.723-0.626-2.193
		c-0.783-0.392-1.723-0.156-2.193,0.626c-0.47,0.784,0,1.88,0.705,2.271c0.392,0.235,0.94,0.235,1.332,0.078l4.777,5.561h3.681
		c0.313,0.861,1.175,1.253,2.036,0.939c0.861-0.313,1.253-1.175,0.94-2.036s-1.175-1.253-2.037-0.94
		C299.27,155.034,298.878,155.426,298.722,155.817"/>
</g>
</svg>
    </h1></a>
    <div id="PCnav">
        <ul>
            <li><a href="index.html#anker1">対応契約書</a></li>
            <li>|</li>
            <li><a href="index.html#anker2">料金プラン</a></li>
            <li>|</li>
            <li><a href="index.html#anker3">専門家</a></li>
            <li>|</li>
            <li><a href="policy.html">情報セキュリティポリシー</a></li>
            <li>|</li>
            <li><a href="index.html#anker4">お申し込み・お問い合わせ</a></li>
        </ul>
    </div>
</div>
<div class="fullscreenmenu">
    <div id="nav">
        <ul>
            <li><a href="index.html"><img src="img/overlayMenuHeader.png" alt="LegalSifter" width="100%"></a></li>
  			<li><a href="index.html#anker1">対応契約書</a></li>
            <li><a href="index.html#anker2">料金プラン</a></li>
            <li><a href="index.html#anker3">専門家</a></li>
            <li><a href="policy.html">情報セキュリティポリシー</a></li>
            <li><a href="index.html#anker4">お申し込み・お問い合わせ</a></li>
        </ul>
    </div>
</div>
<div class="menu">
    <span></span>
    <span></span>
    <span></span>
</div>
	<div id="kakunin_main">
<!-- ▲ Headerやその他コンテンツなど　※編集可 ▲-->

<!-- ▼************ 送信内容表示部　※編集は自己責任で ************ ▼-->
<?php if($empty_flag == 1){ ?>
<div align="center">
<h3>入力エラー</h3>
<?php echo $errm; ?><br><br>
<div id="thanks_btn">
<input type="button" value=" 前画面に戻る " onclick="history.back()" id="thanks_btn_style"></div>
</div>
<?php
		}else{
?>
<div align="center" class="confirmtxt">以下の内容で間違いがなければ、「送信する」ボタンを押してください。</div><br><br>
<form action="<?php echo $file_name; ?>" method="POST">
<div class="confirmtable">
<table align="center">
<?php
foreach($_POST as $key=>$val) {
  $key = strtr($key, $string_from, $string_to);
  //※追記　チェックボックス（配列）の場合は以下の処理で複数の値を取得するように変更　HTML側のname属性の値にも[と]を追加する。
  $out = '';
  if(is_array($val)){
  foreach($val as $item){ 
  $out .= $item . ','; 
  }
  if(substr($out,strlen($out) - 1,1) == ',') { 
  $out = substr($out, 0 ,strlen($out) - 1); 
  }
 }
  else { $out = $val; }//チェックボックス（配列）追記ここまで
  if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
  $out = htmlspecialchars($out);
  $out=nl2br($out);//※追記 改行コードを<br>タグに変換
  $key = htmlspecialchars($key);
  print("<tr><td class=\"l_Cel formcell\">".$key."</td><td class=\"r_Cel formcell\">".$out);
  $out=str_replace("<br />","",$out);//※追記 メール送信時には<br>タグを削除
?>
<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $out; ?>">
<?php
  print("</td></tr>\n");
}
?>
</table></div><br>
<div align="center" id="thanks_btn"><input type="hidden" name="mail_set" value="submit">
<input type="hidden" name="httpReferer" value="<?php echo $_SERVER['HTTP_REFERER'] ;?>">
<input class="button_left" type="submit" value="送信する">
<input class="button_style" type="button" value="前に戻る" onclick="history.back()">
</div>
</form>
<?php } ?>
<!-- ▲ *********** 送信内容確認部　※編集は自己責任で ************ ▲-->

<!-- ▼ Footerその他コンテンツなど　※編集可 ▼-->
</div>
<div class="footwrap">
		<div class="footer overflow">
			<div class="footlogo">
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
	 y="0px" width="154.1px" height="25px" viewBox="0 0 154.1 25" style="enable-background:new 0 0 154.1 25;" xml:space="preserve">
<style type="text/css">
	.st0{fill:#FFFFFF;}
</style>
<g>
	<path class="st0" d="M24.2,19.8V0.4h1.8v17.8h9.7v1.6H24.2z"/>
	<path class="st0" d="M38.7,13.4c0,2.9,1.3,5.3,4.6,5.3c1.9-0.1,3.5-1.3,3.9-3.2h1.7c-0.5,2.9-3.2,5-6.1,4.7c-4,0-5.9-3.5-5.9-7.2
		s2-7.2,6.1-7.2c4.5,0,6.2,3.3,6.2,7.5H38.7L38.7,13.4z M47.4,12c-0.2-2.6-1.6-4.6-4.4-4.6c-2.8,0-4,2.2-4.2,4.6H47.4z"/>
	<path class="st0" d="M61.9,6.3h1.7c0,0.9-0.1,2-0.1,3.1v8.1c0,2.7,0.1,5.5-2.9,6.9c-1,0.5-2,0.7-3.1,0.6c-2.5,0-5.3-0.9-5.3-3.6
		h1.8c0.2,1.6,2.1,2.1,3.8,2.1c2.1,0.1,4-1.6,4.1-3.7v-2.5c-0.9,1.6-2.6,2.5-4.4,2.5c-4,0-5.8-3.1-5.8-6.8c0-3.7,1.7-7,5.8-7
		c1.8,0,3.5,0.9,4.5,2.5V8.3L61.9,6.3L61.9,6.3z M57.5,7.3c-2.9,0-4.2,2.9-4.2,5.4c0,2.5,1.2,5.4,4.2,5.4c3,0,4.3-2.9,4.3-5.5
		C61.8,10.1,60.3,7.4,57.5,7.3L57.5,7.3L57.5,7.3z"/>
	<path class="st0" d="M74.8,10.3c-0.2-2.2-1.2-2.9-3.2-2.9c-1.7,0-3.1,0.5-3.3,2.3h-1.7c0.4-2.8,2.5-3.8,5.1-3.8
		c3,0,4.8,1.3,4.8,4.5v6.4c0,1,0.1,2.1,0.1,3.1h-1.7v-2L74.8,18c-1,1.4-2.5,2.2-4.2,2.2c-2.3,0.2-4.3-1.5-4.5-3.8
		c-0.2-1.9,1-3.7,2.8-4.3c1.8-0.8,4.2-0.5,6.1-0.6L74.8,10.3z M70.7,18.7c3.5,0,4.2-2.9,4-5.8c-2.3,0.1-7.1-0.4-7.1,3.1
		c0,1.5,1.2,2.7,2.7,2.7L70.7,18.7L70.7,18.7z"/>
	<path class="st0" d="M81.9,19.8h-1.6V0.4h1.6V19.8z"/>
	<path class="st0" d="M89,13.6c-0.3,1.7,0.9,3.4,2.6,3.7c0.3,0.1,0.6,0.1,1,0c2.3,0,3.7-1,3.7-2.7c0-1.7-1.2-2-3.7-2.6
		c-4.7-1.2-7.2-2.5-7.2-5.9c0-3.4,2.3-6.1,7.7-6.1c2.2-0.2,4.3,0.7,5.9,2.3c0.8,1,1.2,2.3,1.1,3.6h-4c0.1-1.6-1.2-2.9-2.8-3h-0.4
		c-2,0-3.2,1-3.2,2.6c0,1.8,1.4,2.1,4.2,2.8c3.4,0.8,6.7,1.7,6.7,5.5c0,3.5-2.9,6.3-8.3,6.3c-7.5,0-7.5-4.8-7.5-6.7L89,13.6L89,13.6
		z"/>
	<path class="st0" d="M103.4,0.4h4.2v3.5h-4.2V0.4z M103.5,6.3h4v13.5h-4V6.3z"/>
	<path class="st0" d="M111.3,9.1h-2V6.3h2V4.4c0-2.4,0.8-4.3,4.6-4.3c0.7,0,1.4,0.1,2.1,0.1v2.8c-0.2-0.1-0.6-0.1-0.9-0.1
		c-2,0-2,1-2,1.8v1.6h2.5V9h-2.5v10.8h-3.9L111.3,9.1L111.3,9.1z"/>
	<path class="st0" d="M129,19.6c-1,0.3-2,0.4-3.1,0.4c-4,0-4-2.5-4-3.7V9h-2.6V6.3h2.6V3.6l4-1.5v4h3.2v2.7h-3.2V15c0,1.2,0,2,1.8,2
		c0.4,0,0.9,0,1.3-0.1L129,19.6z"/>
	<path class="st0" d="M134.1,14c0,0.9,0,3.6,2.8,3.6c1.2,0.1,2.2-0.7,2.4-1.9h3.6c-0.1,1.1-0.5,2.1-1.2,2.9c-1.3,1.2-3,1.7-4.8,1.6
		c-1.8,0.1-3.5-0.6-4.8-1.7c-1.2-1.5-1.9-3.4-1.8-5.3c-0.2-2.5,0.9-4.9,2.8-6.4c1.1-0.7,2.4-1.2,3.8-1.1c2.3-0.1,4.5,1.2,5.5,3.3
		c0.7,1.6,1,3.2,0.8,5L134.1,14z M139.1,11.5c0-0.6,0-3.1-2.4-3.1c-1.8,0-2.5,1.5-2.6,3.1H139.1L139.1,11.5z"/>
	<path class="st0" d="M145.9,9.6c0-0.7-0.1-2.6-0.2-3.4h3.7l0.1,2.6c0.5-1.2,1.4-2.8,4.6-2.6v3.6c-4-0.4-4.3,1.7-4.3,3.4v6.7h-4V9.6
		L145.9,9.6z"/>
	<path class="st0" d="M20.4,10.2c0,5.6-4.6,10.2-10.2,10.2C4.6,20.5,0,15.9,0,10.2C0,4.6,4.6,0,10.2,0C15.8,0,20.4,4.6,20.4,10.2z
		 M12.3,13.1h3c0.2,0.7,0.9,1,1.6,0.7c0.7-0.2,1-0.9,0.7-1.6c-0.2-0.7-0.9-1-1.6-0.7c-0.4,0.1-0.6,0.4-0.7,0.7h-3.4l-3,3.5
		c-0.6-0.2-1.3,0.1-1.6,0.7C7.1,17,7.4,17.8,8.1,18c0.6,0.2,1.3-0.1,1.6-0.7c0.1-0.4,0.1-0.7-0.1-1C9.6,16.2,12.3,13.1,12.3,13.1z
		 M11.3,9.7L6.5,4.1c0.3-0.6,0.1-1.3-0.6-1.7S4.6,2.4,4.3,3S4.2,4.4,4.8,4.7c0.3,0.2,0.7,0.2,1,0l4.3,5l-4.8,5.7
		c-0.7-0.2-1.3,0.1-1.6,0.7c-0.2,0.7,0.1,1.3,0.7,1.6c0.7,0.2,1.3-0.1,1.6-0.7c0.1-0.4,0.1-0.7-0.1-1L11.3,9.7z M7.5,9.7L4.4,6.1
		c0.2-0.4-0.1-0.9-0.4-1C3.5,5,3.1,5.2,2.9,5.6C2.8,6,3,6.4,3.4,6.6c0.1,0.1,0.2,0.1,0.4,0.1l2.6,3.1l-3.5,4.2
		c-0.4-0.1-0.9,0.2-0.9,0.7c-0.1,0.4,0.2,0.9,0.7,0.9c0.4,0.1,0.9-0.2,0.9-0.7v-0.4L7.5,9.7z M17.3,9.3h-2.5l-3.5-4.1
		c0.3-0.6,0.1-1.3-0.5-1.7c-0.6-0.3-1.3-0.1-1.7,0.5c-0.4,0.6,0,1.5,0.6,1.8c0.3,0.2,0.7,0.2,1,0.1l3.7,4.3h2.9
		c0.2,0.7,0.9,1,1.6,0.7c0.7-0.2,1-0.9,0.7-1.6c-0.2-0.7-0.9-1-1.6-0.7C17.7,8.7,17.4,9,17.3,9.3"/>
</g>
</svg>
			</div>
			<div id="footnav">
        		<ul>
                    <li><a href="index.html#anker4">お申し込み・お問い合わせ</a></li>
           	 		<li>|</li>
                    <li><a href="policy.html">情報セキュリティポリシー</a></li>
            		<li>|</li>
            		<li><a href="index.html#anker3">専門家</a></li>
            		<li>|</li>
                    <li><a href="index.html#anker2">料金プラン</a></li>
                    <li>|</li>
            		<li><a href="index.html#anker1">対応契約書</a></li>
        		</ul>
    		</div>
		</div>
		<div class="footsign pdg-btm1em pdg-top1em">
			<a href="https://tandemsprint.com/" target="_blank">TandemSprint, Inc.</a><br>
			<a href="https://first-tandemsprint.com/" target="_blank">弁護士法人ファースト＆タンデムスプリント法律事務所</a>
		</div>
	</div>
<p class="pagetop">
	<a href="#"><img src="img/2top.png" alt="TOP"></a>
</p>
</body>
</html>
<?php
/* ▲▲▲送信確認画面のレイアウト　※オリジナルのデザインも適用可能▲▲▲　*/
}
if(($jumpPage == 0 && $sendmail == 1) || ($jumpPage == 0 && ($confirmDsp == 0 && $sendmail == 0))) { 

/* ▼▼▼送信完了画面のレイアウト　編集可 ※送信完了後に指定のページに移動しない場合のみ表示▼▼▼　*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>お問い合わせ完了画面</title>
</head>
<body>
<div align="center">
<?php if($empty_flag == 1){ ?>
<h3>入力エラー</h3><?php echo $errm; ?><br><br><input type="button" value=" 前画面に戻る " onClick="history.back()">
<?php
  }else{
?>
送信ありがとうございました。<br>
送信は正常に完了しました。<br><br>
<a href="<?php echo $site_top ;?>">トップページへ戻る⇒</a>
</div>
<div style="text-align:center;margin-top:15px;"><a style="font-size:11px;color:#aaa;text-decoration:none" href="http://www.kens-web.com/" target="_blank">- Ken'sWeb -</a></div>
<!--  CV率を計測する場合ここにAnalyticsコードを貼り付け -->
</body>
</html>
<?php 
/* ▲▲▲送信完了画面のレイアウト 編集可 ※送信完了後に指定のページに移動しない場合のみ表示▲▲▲　*/
  }
}
//完了時、指定のページに移動する設定の場合、指定ページヘリダイレクト
else if(($jumpPage == 1 && $sendmail == 1) || $confirmDsp == 0) { 
	 if($empty_flag == 1){ ?>
<div align="center"><h3>入力エラー</h3><?php echo $errm; ?><br><br><input type="button" value=" 前画面に戻る " onClick="history.back()"></div>
<?php }else{ header("Location: ".$thanksPage); }
} ?>
