var currentLocation;
jQuery(document).ready(function () {
  var $ = jQuery;
  var $container = $("#mps-store-list");
  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function (location) {
          currentLocation = {
            latitude: location.coords.latitude,
            longitude: location.coords.longitude,
          };
          getPosts();
        },
        function (err) {
          console.log("Error", err);
          getPosts();
        }
      );
    } else {
      x.innerHTML = "Geolocation is not supported by this browser.";
    }
  }
  function addPostHtml(r, index) {
    var $div = $(`
      <li>
        <div class="wrapper-list-block">
            <div class="">
                <h4 class="list-head">${r.title}</h4>
                <span>${r.address}</span>
                <span>${r.pincode}</span>
                <span>${r.phone}</span>
            </div>
            <div class="">
                <h5>${Number(r.distance).toFixed(2)} km</h5>
            </div>
        </div>
    </li>
    `);
    $div.click(function () {
      gmap.setZoom(17);
      gmap.panTo(new google.maps.LatLng(r.latitude, r.longitude));
      window.currentMarker = index;
      window.showInfoWindow(r);
    });
    $container.append($div);
  }
  $(".store form").submit(function (e) {
    e.preventDefault();
    const query = $(this).find("input").val();
    if (query.length > 2) {
      window.codeAddress(query, function (location) {
        currentLocation = {
          latitude: location.lat,
          longitude: location.lng,
        };
        getPosts();
      });
    }
  });
  function getPosts() {
    $.ajax({
      type: "POST",
      url: ajax.url,
      data: {
        lat: currentLocation?.latitude,
        long: currentLocation?.longitude,
        action: "mps_store_get_stores", //this is the name of the AJAX method called in WordPress
      },
      success: function (result) {
        try {
          var result = JSON.parse(result);
          window.markers = [];
          $container.html("");
          result.forEach((r, i) => {
            addPostHtml(r, i);
            var marker = new google.maps.Marker({
              position: new google.maps.LatLng(r.latitude, r.longitude),
              title: r.title,
            });
            marker.addListener("click", function () {
              window.showInfoWindow(r, marker);
            });
            marker.setMap(gmap);
            window.markers.push(marker);
            if (i === 0) {
              window.currentMarker = 0;
              window.showInfoWindow(r, marker);
            }
          });
        } catch (e) {
          console.log("Error", e);
        }
      },
      error: function () {
        alert("error");
      },
    });
  }
  getLocation();
});
