<?php
require_once('vendor/autoload.php');
require_once("./sql.php");
?>
<html>

<head>
	<title>Actors Map</title>
	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

	<link rel="stylesheet" type="text/css" href="./style.css" />
	<script>
		locations = [<?php
		$stmt = $mysqli->prepare("SELECT * FROM cities");
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$parts = array();
			if ($row["city"] != "")
				array_push($parts, $row["city"]);
			if ($row["state"] != "")
				array_push($parts, $row["state"]);
			if ($row["country"] != "")
				array_push($parts, $row["country"]);
			$title = implode(", ", $parts);

			echo "{coords: {lat:" . $row["lat"] . ", lng:" . $row["lng"] . "}, title:\"$title\"},";
		}
		?>];
	</script>
	<script type="module" src="./index.js"></script>
	<script src="./lib.js"></script>
</head>

<body>
	<div class="flexbox-container">
		<div id="map"></div>
		<div id="list-container">
			<?php require_once("listactors.php"); ?>
		</div>
	</div>

	<!-- prettier-ignore -->
	<script>(g => { var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__", m = document, b = window; b = b[c] || (b[c] = {}); var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams, u = () => h || (h = new Promise(async (f, n) => { await (a = m.createElement("script")); e.set("libraries", [...r] + ""); for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]); e.set("callback", c + ".maps." + q); a.src = `https://maps.${c}apis.com/maps/api/js?` + e; d[q] = f; a.onerror = () => h = n(Error(p + " could not load.")); a.nonce = m.querySelector("script[nonce]")?.nonce || ""; m.head.append(a) })); d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)) })
			({ key: "<?php echo $_ENV["GOOGLE_API_KEY"]; ?>", v: "weekly" });</script>
</body>

</html>