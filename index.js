let map;

async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  map = new Map(document.getElementById("map"), {
    center: { lat: 43.650, lng: -79.395 },
    zoom: 5,
    mapId: "DEMO_MAP_ID",
  });

  for (const loc of locations) {
    const marker = new AdvancedMarkerElement({
        map: map,
        position: loc,
        title: "Uluru",
    });
  }
}

initMap();