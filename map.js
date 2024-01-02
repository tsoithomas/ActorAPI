let map;
let markers = [];
let infowindow;

export async function initMap() {
	const { Map } = await google.maps.importLibrary("maps");
	const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

	map = new Map(document.getElementById("map"), {
		center: { lat: 43.65, lng: -79.395 },
		zoom: 4,
		mapId: "DEMO_MAP_ID",
	});

	infowindow = new google.maps.InfoWindow();
}

export async function addMarker(location, actorname, birthyear, fullcity) {
	const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
	const marker = new AdvancedMarkerElement({
		map: map,
		position: location,
		title: "aaa",
	});


	marker.addListener("click", () => {
		const contentString =
			'<div class="infoWindow">' +
			'<h1 class="infoTitle">' + actorname +  "</h1>" +
			'<div class="infoBody">' +
			'<div>Year of Birth: '+ birthyear + '</div>' + 
			'<div>'+ fullcity + '</div>' + 
			'</div>' +
			"</div>";
		infowindow.setContent(contentString);
			infowindow.open({
				anchor: marker,
				map,
			});
		});

	markers.push(marker);
}

export async function clearMarkers() {
	for (let i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers = [];
}