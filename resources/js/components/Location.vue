<template>
  <div class="flex">
    <form @submit.prevent="show()">
      <div class="flex location-searchbar-div">
        <div
          class="w-8 flex items-center justify-center bg-blue-lighter border-t border-l border-b border-blue-lighter rounded-l text-blue-dark"
        >
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <input
          id="searchTextField"
          name="location"
          class="auth-card-input location-searchbar"
          type="text"
          size="50"
        />
      </div>
      <input type="hidden" id="city2" name="city2" />
      <input type="hidden" id="cityLat" name="cityLat" />
      <input type="hidden" id="cityLng" name="cityLng" />
    </form>
  </div>
</template>

<script>
export default {
  props: ["latQuery", "lngQuery"],
  data() {
    return {
      city2: null,
      cityLat: null,
      cityLng: null,
      lng: null,
      lat: null,
    };
  },
  methods: {
    show() {
      console.log("city2: ", this.city2);
      console.log("cityLat: ", this.cityLat);
      console.log("cityLng: ", this.cityLng);
    },
    initialize() {
      var input = document.getElementById("searchTextField");
      var autocomplete = new google.maps.places.Autocomplete(input);
      this.complete = autocomplete;
      var vm = this;

      google.maps.event.addListener(autocomplete, "place_changed", function () {
        var place = autocomplete.getPlace();

        vm.city2 = place.name;
        console.log("city2: ", vm.city2);
        document.getElementById("city2").value = place.name;

        vm.cityLat = place.geometry.location.lat();
        console.log("cityLat: ", vm.cityLat);
        document.getElementById(
          "cityLat"
        ).value = place.geometry.location.lat();

        vm.cityLng = place.geometry.location.lng();
        console.log("cityLng: ", vm.cityLng);
        document.getElementById(
          "cityLng"
        ).value = place.geometry.location.lng();

        vm.locationChanged();
      });
    },
    geolocate() {
      var vm = this;
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          vm.codeLatLng(position.coords.latitude, position.coords.longitude);
        });
      }
    },
    locationChanged() {
      console.log("changed: ", this.cityLat, this.cityLng);
      if (this.cityLat && this.cityLng) {
        this.$inertia.visit(
          route("dashboard", { lat: this.cityLat, lng: this.cityLng })
        );
      }
    },
    setLocation() {
      if (this.latQuery && this.lngQuery) {
        this.codeLatLng(this.latQuery, this.lngQuery);
      } else {
        this.geolocate();
      }
    },
    codeLatLng(lat, lng) {
      var latlng = new google.maps.LatLng(lat, lng);
      var vm = this;

      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ latLng: latlng }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          console.log(results);
          if (results[1]) {
            vm.city2 = results[1].formatted_address;
            document.getElementById("searchTextField").value =
              results[1].formatted_address;
          } else {
            if (results[0]) {
              vm.city2 = results[0].formatted_address;
              document.getElementById("searchTextField").value =
                results[0].formatted_address;
            }
          }
          vm.cityLat = lat;
          vm.cityLng = lng;
        } else {
          console.log("Geocoder failed due to: " + status);
        }
      });
    },
  },
  mounted() {
    google.maps.event.addDomListener(window, "load", this.initialize);
    this.setLocation();
  },
};
</script>