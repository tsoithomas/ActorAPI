let map;

async function initMap() {
	const { Map } = await google.maps.importLibrary("maps");
	const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

	map = new Map(document.getElementById("map"), {
		center: { lat: 43.65, lng: -79.395 },
		zoom: 4,
		mapId: "DEMO_MAP_ID",
	});

  let infowindow = new google.maps.InfoWindow();
	for (const loc of locations) {
		const marker = new AdvancedMarkerElement({
			map: map,
			position: loc.coords,
			title: loc.title,
		});


		marker.addListener("click", () => {
      const contentString =
			'<div class="infoWindow">' +
			'<h1 class="infoTitle">' + loc.title +  "</h1>" +
			'<div class="infoBody">abc</div>' +
			"</div>";
      infowindow.setContent(contentString);
			infowindow.open({
				anchor: marker,
				map,
			});
		});
	}
}

initMap();