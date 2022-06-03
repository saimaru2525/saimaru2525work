<?php
//Ajaxから呼ばれ、
//データ一覧をhtml(text)で返す。
header('Content-type: text/plain; charset= UTF-8');
?>
<?php
if (empty($_SESSION['TOKEN'])) {
	// ステータスコードを出力
	http_response_code( 401 ) ;
	// 転送処理
	header( "Location: /shibaTest/testPHP.php" ) ;
	// 終了
	exit ;
}
//データ取得
$page='1';
if (!empty($_POST['page'])){
    $user=htmlspecialchars($_POST['name']);
}
$count='10';
if (!empty($_POST['count'])){
    $passwd=htmlspecialchars($_POST['passwd']);
}

$data = NULL;
$ret = getDataList($page, $count, $data);
if ($ret == 8) {
	// 認証切れはログイン画面へ戻す
	http_response_code( 401 ) ;
	// 転送処理
	header( "Location: /shibaTest/testPHP.php" ) ;
	// 終了
	exit ;
}
?>
<?php if ($ret == 0): ?>
<table>
<tr>
    <th>タイトル</th>
    <th>日付</th>
    <th>メモ</th>
    <th>番号</th>
    <th>チェック</th>
    <th>選択肢テスト</th>
    <th>選択肢テスト<BR>（複数）</th>
</tr>
<?php foreach ($data['data'] as $datarow) { ?>
<tr>
    <td><?= $datarow['value']['title'] ?></td>
    <td><?= $datarow['value']['date'] ?></td>
    <td><?= $datarow['value']['memo'] ?></td>
    <td><?= $datarow['value']['no'] ?></td>
    <td><?= $datarow['value']['check1'] ?></td>
    <td><?= $datarow['value']['select'] ?></td>
    <td>
        <?php 
        if (is_array($datarow['value']['select2'])) {
            foreach ($datarow['value']['select2'] as $select2item) { 
                echo $select2item . ",";
            }
        }
        else {
            echo $datarow['value']['select2'];
        }
        ?>
    </td>
</tr>
<?php } ?>
</table>
<?php endif; ?>
<?php
// 
// retval: 0:正常 1:引数チェックエラー 2:APIエラー 8:認証切れ 9:想定外のエラー
function getDataList($page, $count, &$res_json)
{
    $token = $_SESSION['TOKEN'];

    $url = "http://exment.localapp/admin/api/data/test_req";
    $url = $url . "?page=" . $page;
    $url = $url . "&count=" . $count;
    $header = array(
        "Authorization:" . $token["token_type"] . " " . $token["access_token"]
    );

    $curl = curl_init($url);
    $options = array(
        CURLOPT_HTTPGET=> true,
        // HEADER
        CURLOPT_HTTPHEADER =>$header,
        // 変数に保存。これがないと即時出力
        CURLOPT_RETURNTRANSFER => true,
        // header出力
        CURLOPT_HEADER => true, 
    );
    
    //set options
    curl_setopt_array($curl, $options);
    
    // 実行
    $response = curl_exec($curl);
    
    // ステータスコード取得
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    // header & body 取得
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE); // ヘッダサイズ取得
    $header = substr($response, 0, $header_size); // header切出
    $header = array_filter(explode("\r\n",$header));
    $ret = 0;
    if ($code == 200) {
        $res_json = substr($response, $header_size); // body切出
        $res_json = json_decode($res_json, true);
    }
    elseif ($code == 401) {
        echo "認証切れです！";
        //認証切れ
        unset($_SESSION["TOKEN"]);
        $ret = 8;
    }
    else {
        echo "<br>api error status" . $code;
        $ret = 2;
    }
    curl_close($curl);
    return $ret;
}
?>