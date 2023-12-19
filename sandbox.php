<html>
<head>
    <title>API Sandbox</title>
</head>
<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js" integrity="sha512-a+SUDuwNzXDvz4XrIcXHuCf089/iJAoN4lmrXJg18XnduKK6YlDHNRalv4yd1N40OKI80tFidF+rqTFKGPoWFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

async function fetchAPI() {
    const timestamp = Math.floor((new Date()).getTime() / 1000 / 60);

    accountid = document.getElementById("accountid").value;
    secret = document.getElementById("secret").value;
    params = document.getElementById("params").value;
    var hash = CryptoJS.HmacSHA512(secret, timestamp.toString()).toString(CryptoJS.enc.Hex);

    const response = await fetch("http://localhost/test/api/?"+params+"&accountid="+accountid+"&hash="+hash);
    const data = await response.json()
    console.log(hash);
    document.getElementById("result").innerText = JSON.stringify(data, null, 2);
}
</script>
<?php
//echo hash_hmac("sha512", "abcabcabc", "Secret Passphrase");

?>

<div>
    Account ID:
    <input type="text" id="accountid" value="1">
</div>
<div>
    Secret:
    <input type="text" id="secret" value="abcabcabc">
</div>
<div>
    Params:
    <input type="text" id="params" value="action=actorcount&country=USA">
</div>

<div>
    <button onClick="fetchAPI()">Fetch</button>
</div>

<pre id="result" style="font-family: 'Courier New', Courier, monospace; font-size: 12px; background: #dddddd"></pre>




</body>
</html>