const execAuth = (arg) => {
    var token = sessionStorage.getItem("TOKEN");
    if(token != null){
        func(token);
        return;
    }

    const user = document.getElementById('name').value;
    const passwd = document.getElementById('passwd').value;

    const token_request = `{
    "grant_type": "password",
    "client_id": "92cd55a0-db24-11ec-9895-f1cec3ec309c",
    "client_secret": "HzS4hRWktUTBWVRc5SbUjcR96qEqwXnBc7hyX6NK",
    "username": "${user}",
    "password": "${passwd}",
    "provider_name": "hoge",
    "scope": "value_read value_write workflow_read workflow_execute"
}`
    fetch("http://exment.localapp/admin/oauth/token", {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'no-cors', // no-cors, *cors, same-origin
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(token_request) // 本文のデータ型は "Content-Type" ヘッダーと一致させる必要があります
      })
      .then((res)=>{
        if( ! res.ok ) {
          // 例外を投げるとcatch()へ行く
          throw new Error(`Fetch: ${res.status} ${res.statusText}`);
        }
        return( res.json() );
      })
      .then((json)=>{
        console.log(json);
      })
      .catch((error)=>{
        console.error(error);
      });
};