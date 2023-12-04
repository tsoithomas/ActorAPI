let map;

async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");


  map = new Map(document.getElementById("map"), {
    center: { lat: 43.650, lng: -79.395 },
    zoom: 4,
    mapId: "DEMO_MAP_ID",
  });

  for (const loc of locations) {
    const marker = new AdvancedMarkerElement({
        map: map,
        position: loc.coords,
        title: loc.title,
    });
    const contentString =
    '<div class="infoWindow">' +
    '<h1 class="infoTitle">'+loc.title+'</h1>' +
    '<div class="infoBody"></div>' +
    "</div>";
    const infowindow = new google.maps.InfoWindow({
        content: contentString,
        ariaLabel: loc.title,
    });

    marker.addListener("click", () => {
        infowindow.open({
          anchor: marker,
          map,
        });
      });

  }
}


initMap();