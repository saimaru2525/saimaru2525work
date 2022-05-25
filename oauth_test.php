<?php
// 
// retval: 0:正常 1:パラメーター無し 2:APIエラー 9:想定外のエラー
function execOAuth2($user, $passwd, &$res_json)
{
    if (!empty($_SESSION['TOKEN'])) {
        echo "<br>token 取得済み";
        return 0;
    }
    if (empty($user)) {
        echo "<br>user 無し";
        return 1;
    }
    if (empty($passwd)) {
        echo "<br>passwd 無し";
        return 1;
    }

    echo "http request start!!!";

    $url = "http://exment.localapp/admin/oauth/token";
    $params = array(
        "grant_type" => "password",
        "client_id" => "884e4160-dbfe-11ec-9706-6f543a6e94ba",
        "client_secret" => "BZ4vMvJ3JwG8SwqnnUkdGYnrphVeRf284WtLTC59",
        "username" => "${user}",
        "password" => "${passwd}",
        "scope" => "value_read"        
    );
    $params = json_encode($params); // json化
    $header = array(
        "Content-Length: ".strlen($params),
        "Accept: application/json",
        "Content-Type: application/json",
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
    echo "<br>http request now!!!";
    $response = curl_exec($curl);
    
    echo "<br>http response check!!!";
    // ステータスコード取得
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    // header & body 取得
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE); // ヘッダサイズ取得
    $header = substr($response, 0, $header_size); // header切出
    $header = array_filter(explode("\r\n",$header));
    $ret = 0;
    print_r($header); // header配列
    if ($code == 200) {
        $res_json = substr($response, $header_size); // body切出
        print_r($res_json); // json結果
        $_SESSION['TOKEN'] = $res_json;
    }
    else {
        echo "<br>http status" . $code;
        $ret = 2;
    }
    curl_close($curl);
    
    return $ret;
}

?>