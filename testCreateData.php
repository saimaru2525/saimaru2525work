<!DOCTYPE html>
<html>
<head>
	<meta charser="UTF-8" />
	<script type="text/javascript" src="formcheckNew.js"></script>
	<title>APIのテスト（データ登録）</title>
    <style type="text/css">
        td.center { text-align: center; }
        td.right { text-align: right; }
    </style>
</head>
<body>
<?php
if (empty($_SESSION['TOKEN'])) {
    //認証情報無しの為、ログイン画面へ転送
	// ステータスコードを出力
	http_response_code( 401 ) ;
	// 転送処理
	header( "Location: /shibaTest/testPHP.php" ) ;
    echo "TOKEN無し";
	// 終了
	exit ;
}
date_default_timezone_set('Asia/Tokyo');
$view_date = date("Y-m-d");
echo $view_date;

$hidViewYMD='';
if (!empty($_POST['hidViewYMD'])){
    $hidViewYMD=htmlspecialchars($_POST['hidViewYMD']);
}
$result_msg = '入力してください。<br/>';
$title='';
$date='';
$memo='';
$no='';
$check1='0';
$select='';
$select2=array();
$select2_red='';
$select2_blue='';
$select2_yellow='';
$select2_orange='';
if (!empty($hidViewYMD)){
    //登録処理呼び出し
    include("create_data.php");
    //パラメーター取得
    if (!empty($_POST['title'])){
        $title=htmlspecialchars($_POST['title']);
    }
    if (!empty($_POST['date'])){
        //TODO y-m-dに変換要
        $date=htmlspecialchars($_POST['date']);
    }
    if (!empty($_POST['memo'])){
        //\r\n または　\n　どっちでもOK
        $memo=htmlspecialchars($_POST['memo']);
    }
    if (!empty($_POST['no'])){
        //数値はそのまま
        $no=htmlspecialchars($_POST['no']);
    }
    if (isset($_POST['check1'])){
        //YES=1 NO=0(integer)
        $check1='1';
    }
    if (!empty($_POST['select'])){
        $select=htmlspecialchars($_POST['select']);
    }
    //選択肢複数　（複数選択可の場合はstring型の配列）
    if (isset($_POST['select2_red'])){
        $select2_red='red';
        $select2[] = "red";
    }
    if (isset($_POST['select2_blue'])){
        $select2_blue='blue';
        $select2[] = "blue";
    }
    if (isset($_POST['select2_yellow'])){
        $select2_yellow='yellow';
        $select2[] = "yellow";
    }
    if (isset($_POST['select2_orange'])){
        $select2_orange='orange';
        $select2[] = "orange";
    }

    $params = array(
        "value" => array(
            "title" => $title,
            "date" => $date,
            "memo" => $memo,
            "no" => intval($no),
            "check1" => $check1,
            "select" => $select,
            "select2" => $select2,
        )
    );

    $ret = createData($params, $result_msg);

    if ($ret == 8) {
        //認証情報無しの為、ログイン画面へ転送
        // ステータスコードを出力
        http_response_code( 401 ) ;
        // 転送処理
        header( "Location: /shibaTest/testPHP.php" ) ;
        // 終了
        exit ;
    }
}
?>
<input type="button" onclick="location.href='/shibaTest/testDataList.php'" value="一覧へ戻る" />
<div><?= "$result_msg" ?></div>
</table>
<form id="form" method="POST" action="/shibaTest/testCreateData.php" onSubmit="return check()">
<input type="hidden" name="hidViewYMD" id="hidViewYMD" value="<?=$view_date; ?>"/>
<br />
<table>
<tr><th>
<label for="title">タイトル</label>
</th><td>
<input type='text' id='title' name='title' value="<?= $title ?>">
</td></tr>
<tr><th>
<label for="date">日付(Y-M-D)</label>
</th><td>
<input type='text' id='date' name='date' value='<?= $date ?>'>
</td></tr>
<tr><th>
<label for="memo">メモ</label>
</th><td>
<textarea id='memo' name='memo'  cols=40 rows=4><?= $title ?></textarea>
</td></tr>
<tr><th>
<label for="no">番号</label>
</th><td>
<input type='text' id='no' name='no' value='<?= $no ?>'><br />
</td></tr>
<tr><th>
<label for="check1">チェック</label>
</th><td>
<input type='checkbox' id='check1' name='check1' <?= ($check1 == '0') ? '' : 'checked="checked"' ?>><br />
</td></tr>
<tr><th>
<label for="select">選択肢テスト</label>
</th><td>
<select name="select" id="select">
    <option value="">選択無し</option>
    <option <?= ($select == '1') ? 'selected' : '' ?> value="1">りんご</option>
    <option <?= ($select == '2') ? 'selected' : '' ?> value="2">ポスト</option>
    <option <?= ($select == '3') ? 'selected' : '' ?> value="3">止まれ！</option>
</select>
</td></tr>
<tr><th>
<label>選択肢テスト（複数）</label>
</th><td>
<input type='checkbox' id='select2_red' name='select2_red' <?= ($select2_red == '') ? '' : 'checked="checked"' ?>>
<label for="select2_red">赤</label>　　
<input type='checkbox' id='select2_blue' name='select2_blue' <?= ($select2_blue == '') ? '' : 'checked="checked"' ?>>
<label for="select2_blue">青</label>　　
<input type='checkbox' id='select2_yellow' name='select2_yellow' <?= ($select2_yellow == '') ? '' : 'checked="checked"' ?>>
<label for="select2_yellow">黄</label>　　
<input type='checkbox' id='select2_orange' name='select2_orange' <?= ($select2_orange == '') ? '' : 'checked="checked"' ?>>
<label for="select2_orange">オレンジ</label>　　<br />
</td></tr>
<tr>
<tr><td colspan="2" class="right"><input type=submit value=" 登録 "></td></tr>
</table>
</form>
</body>
</html>



