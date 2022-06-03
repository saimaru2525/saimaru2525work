<?php
// 
// retval: 0:正常 1:パラメーター無し 2:APIエラー 3:初回 8:認証切れ 9:想定外のエラー
function createData($params, &$ret_message)
{
    $token = $_SESSION['TOKEN'];

    $url = "http://exment.localapp/admin/api/data/test_req";
    $params = json_encode($params); // json化
    $header = array(
        "Content-Length: ".strlen($params),
        "Accept: application/json",
        "Content-Type: application/json",
        "Authorization:" . $token["token_type"] . " " . $token["access_token"]
    );

    $curl = curl_init($url);
    $options = array(
        // HEADER
        CURLOPT_HTTPHEADER =>$header,
        // Method
        CURLOPT_POST => true, // POST
        // body
        CURLOPT_POSTFIELDS => $params,
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
    if ($code == 200 or $code == 201) {
        $res_json_str = substr($response, $header_size); // body切出
        $arr = json_decode($res_json_str, true);
        $ret_message = '登録が正常終了しました。';
    }
    elseif ($code == 401) {
        echo "認証切れです！";
        //認証切れ
        unset($_SESSION["TOKEN"]);
        $ret = 8;
    }
    else {
        $ret_message = "APIエラーです。<br>http status" . $code;
        $ret = 2;
    }
    curl_close($curl);
    
    return $ret;
}

?>