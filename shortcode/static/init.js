window.initMap = function () {
  window.gmap = new google.maps.Map(document.getElementById("mps-gmap"), {
    center: { lat: -34.397, lng: 150.644 },
    zoom: 8,
  });
  window.codeAddress = function (query, callback) {
    window.geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: query }, function (results, status) {
      if (status == "OK") {
        callback(results[0].geometry.location);
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  };
  window.showInfoWindow = function (store, marker) {
    const contentString =
      '<div style="min-width:200px; padding-bottom:16px;">' +
      "<h4>" +
      store.title +
      "</h4>" +
      '<div id="bodyContent">' +
      "<div>" +
      store.address +
      "</div>" +
      "<div>" +
      store.city +
      "</div>" +
      "<div>" +
      store.country +
      "</div>" +
      "<div>" +
      store.pincode +
      "</div>" +
      "<div>" +
      store.phone +
      "</div>" +
      '<p>Website: <a href="http://' +
      store.website +
      '"' +
      " target='_blank'>" +
      store.title +
      "</a></p>" +
      "</div>" +
      "</div>";
    let infowindow = new google.maps.InfoWindow({
      content: contentString,
    });
    infowindow.open({
      anchor: marker ? marker : window.markers[window.currentMarker],
      gmap,
    });
  };
};
