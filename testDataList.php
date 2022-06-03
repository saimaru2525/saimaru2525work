<!DOCTYPE html>
<html>
<head>
	<meta charser="UTF-8" />
	<script type="text/javascript" src="formcheck.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<title>APIのテスト（データ検索）</title>
    <style type="text/css">
        th,td {
            border: solid 2px;              /* 枠線指定 */
        }
        table {
            border-collapse:  collapse;     /* セルの線を重ねる */
        }
    </style>    
</head>
<body>
<?php
if (empty($_SESSION['TOKEN'])) {
	// ステータスコードを出力
	http_response_code( 401 ) ;
	// 転送処理
	header( "Location: /shibaTest/testPHP.php" ) ;
	// 終了
	exit ;
}
?>
<input type="button" id="btnGetList" value="データ一覧表示" />　　
<input type="button" onclick="location.href='/shibaTest/testCreateData.php'" value="新規追加" />　　
<br />
<br />
<div class="result"></div>
<br />
<script>
  $(function(){
    // 「Ajax通信」ボタンをクリックしたら発動
    $('#btnGetList').on('click',function(){
      $.ajax({
        url:'/shibaTest/ajex_get_data_List.php',
        type:'POST',
        data:{
          'hogehoge':'fugafuga'
        }
      })
      // Ajax通信が成功したら発動
      .done( (data) => {
        $('.result').html(data);
      })
      // Ajax通信が失敗したら発動
      .fail( (jqXHR, textStatus, errorThrown) => {
        alert('ajax通信に失敗しました。');
        console.log("jqXHR          : " + jqXHR.status); // HTTPステータスを表示
        console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラーなどのエラー情報を表示
        console.log("errorThrown    : " + errorThrown.message); // 例外情報を表示
      })
      // Ajax通信が成功・失敗のどちらでも発動
      .always( (data) => {
        console.log('終了処理');
      });
    });
  });
</script>
</body>
</html>



