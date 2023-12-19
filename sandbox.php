<html>
<head>
	<title>API Sandbox</title>
</head>

<style>

body {
	margin-top: 0;
	overflow-y: scroll;
	font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif
}
.inputs {
	position: sticky;
	top: 0;
	padding: 8px 0;
	background-color: rgba(255,255,255,0.7);
}

.row {
	display: flex;
	flex-direction: row;
	background-color: antiquewhite;
	margin-bottom: 4px;
	padding: 4px 10px;
	border-radius: 5px;
}
.key {
	width: 100px;
}
.value {
	flex-grow: 1;
}
.value input {
	width: 100%;
	border: 0;
	padding: 5px;
}
.fetch button {
	border-radius: 5px;
	background-color: firebrick;
	color: #ffffff;
	border: 0;
	font-weight: 800;
	padding: 6px 20px;
	width: 100%;
	transition: all 0.25s;
	cursor: pointer;
}
.fetch button:hover {
	background-color: darkred;
	letter-spacing: 1rem;
}

#url {
	background-color: #444444;
	color: white;
	word-break: break-all;
	font-size: 14px;
}
#url:not(:empty) {
	padding: 10px;
	border-radius: 10px;
}

.json {
	font-family: 'Courier New', Courier, monospace; 
	font-size: 12px; 
	background: #dddddd;
}
.json:not(:empty) {
	padding: 10px;
	border-radius: 10px;
}

</style>


<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js" integrity="sha512-a+SUDuwNzXDvz4XrIcXHuCf089/iJAoN4lmrXJg18XnduKK6YlDHNRalv4yd1N40OKI80tFidF+rqTFKGPoWFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

async function fetchAPI() {
	const timestamp = Math.floor((new Date()).getTime() / 1000 / 60);

	accountid = document.getElementById("accountid").value;
	secret = document.getElementById("secret").value;
	params = document.getElementById("params").value;
	const hash = CryptoJS.HmacSHA512(secret, timestamp.toString()).toString(CryptoJS.enc.Hex);
	const url = "http://localhost/test/api/?"+params+"&accountid="+accountid+"&hash="+hash;

	const response = await fetch(url);
	const data = await response.json()

	document.getElementById("result").innerText = JSON.stringify(data, null, 2);
	document.getElementById("url").innerText = url;


}
</script>

<div class="inputs">
	<div class="row">
		<div class="key">Account ID:</div>
		<div class="value"><input type="text" id="accountid" value="1"></div>
	</div>
	<div class="row">
		<div class="key">Secret:</div>
		<div class="value"><input type="text" id="secret" value="abcabcabc"></div>
	</div>
	<div class="row">
		<div class="key">Params:</div>
		<div class="value"><input type="text" id="params" value="action=actorcount&country=USA"></div>
	</div>
	<div class="fetch">
		<button onClick="fetchAPI()">Fetch</button>
	</div>
</div>

<div id="url"></div>

<pre id="result" class="json"></pre>




</body>
</html>