<template>
    <div class="flex flex-col mb-3">
      <div class="favoriteContainer justify-between w-full border-b-2 p-5 pb-3">
        <div class="flex">
          <div
            :style="
              'background-image: url(' +
              reservationData.event.image_url +
              ')'
            "
            class="bg-fav"
            >
         </div>
          <div class="flex flex-col pl-2"> 
            <span class="font-bold text-xs">{{
              reservationData.event.name
            }}</span>
            <span class="text-xs text-grey-300">{{
              `${ new Date(reservationData.event_date).toDateString()} at  ${ new Date("1970-01-01T" + reservationData.event_time).toLocaleString('en-US', { hour: 'numeric',minute: 'numeric', hour12: true })}`
            }}</span>
            <span class="text-xs text-grey-300">{{
              ` Reserved for ${reservationData.reserve_seat}   ${
                Number(reservationData.reserve_seat) > 1 ? "Guests" : "Guest"
              }`
            }}</span>

            <span v-if="reservationData.event_status === 'live'" class="bg-dinesurf-green-400 w-20  text-center mt-3 text-white">
                Live
            </span>

            <span  v-if="reservationData.event_status === 'upcoming'" class="bg-yellow-300 w-20  text-center mt-3 text-white">
                Upcoming
            </span>

            <span  v-if="reservationData.event_status === 'past'" class="bg-dinesurf-red-400 w-20  text-center mt-3 text-white">
                Past
            </span>
  
          
  
         
          </div>
        </div>
  
        <div class="btnContainerfavorite mt-5"  v-if="reservationData.event_status === 'upcoming'">
      
          <div
            class="md:flex flex-col flex-wrap text-xs md:flex-row mb-3"
          >
            
          
            <div class="top-25 md:mt-3">
              <a
              :href="
                'https://calendar.google.com/calendar/render?' + googlecalendar
              "
                target="_blank"
                class="btn btn-md btn-white-gray mr-5 text-xs h-8"
              >
                <i class="far fa-calendar-alt"></i> Add to Calendar
              </a>
            </div>
  
          
          </div>
        </div>
      </div>
    </div>
  
    
  </template>
  
  <script>
  import tippy from "tippy.js";
  import "tippy.js/dist/tippy.css"; // optional for styling
  import moment from "moment";
  import JetLabel from "@/Jetstream/Label.vue";
  
  export default {
    props: ["reservation"],
    components: {
      JetLabel,
    },
    data() {
      return {
        exampleModalShowing: false,
        reservationData: this.reservation,
        updating: null,
        filter: false,
        responseText: null,
        email: null,
        phone: null,
        note: null,
        name: null,
      };
    },
    computed: {
      googlecalendar() {
        const params = new URLSearchParams({
          action: "TEMPLATE",
          dates:
            moment(this.reservation.iso_start)
              .utc()
              .format("YYYYMMDD[T]HHmmss[Z]") +
            "/" +
            moment(this.reservation.iso_end).utc().format("YYYYMMDD[T]HHmmss[Z]"),
          details:
            this.capitalizeWords(this.reservation.event.name) +
            " Reservation on Dinesurf\n\n" +
            route("reservations", { id: this.reservation.id }),
          location: this.reservation.vendor.company_address,
          text:
            this.capitalizeWords(this.reservation.event.name) +
            " Reservation on Dinesurf\n\n",
        });
        return params.toString();
      },
      outLookcalendar() {
        j;
        const params = new URLSearchParams({
          body: "Learn all about the rules of the Motorway and how to access the fast lane.\n\nhttps://en.wikipedia.org/wiki/Gridlock_(Doctor_Who)",
          enddt: "2022-01-12T20:00:00+00:00",
          location: "New Earth",
          path: "/calendar/action/compose",
          rru: "addevent",
          startdt: "2022-01-12T18:00:00+00:00",
          subject: "Welcome to the Motorway",
        });
        return params.toString();
      },
      office365calendar() {
        const params = new URLSearchParams({
          body: "Learn all about the rules of the Motorway and how to access the fast lane.\n\nhttps://en.wikipedia.org/wiki/Gridlock_(Doctor_Who)",
          enddt: "2022-01-12T20:00:00+00:00",
          location: "New Earth",
          path: "/calendar/action/compose",
          rru: "addevent",
          startdt: "2022-01-12T18:00:00+00:00",
          subject: "Welcome to the Motorway",
        });
        return params.toString();
      },
    },
    methods: {
      capitalizeWords(string) {
        return string.replace(/(?:^|\s)\S/g, function (a) {
          return a.toUpperCase();
        });
      },
      toggleReservation(id, status) {
        if (status == "cancelled") {
          var r = confirm("Are you sure you want to cancel this reservation?");
        } else {
          var r = confirm(
            "Are you sure you want to undo this reservation status?"
          );
        }
  
        if (!r) {
          return;
        }
  
        this.updating = "Updating...";
  
        fetch(route("cancel-reservation", { id: id, status: status }))
          .then((result) => {
            return result.json();
          })
          .then((result) => {
            if (result.success) {
              this.updating = "Updated";
              var vm = this;
  
              setTimeout(
                () => {
                  vm.updating = null;
                },
                3000,
                vm
              );
              this.getReservation();
            }
          })
          .catch((err) => {
            this.handleApiError(err);
          });
      },
      getReservation() {
        fetch(route("get-reservation", { id: this.reservationData.id }))
          .then((result) => {
            return result.json();
          })
          .then((result) => {
            console.log("result: ", result);
            if (result.success) {
              console.log("reservation: ", result.data);
              this.reservationData = result.data.reservation;
            }
          })
          .catch((err) => {
            this.handleApiError(err);
          });
      },
      inviteGuest() {
        this.responseText = "Inviting...";
  
        axios
          .post(route("invite-guest"), {
            reservation_id: this.reservationData.id,
            email: this.email,
            phone: this.phone,
            note: this.note,
            name: this.name,
          })
          .then((result) => {
            this.responseText = "Invited.";
  
            this.email = null;
            this.note = null;
            this.name = null;
            this.phone = null;
  
            this.getReservation();
            let vm = this;
            setTimeout(
              function () {
                vm.responseText = null;
              },
              3000,
              vm
            );
          })
          .catch((err) => {
            this.responseText = null;
            this.handleApiError(err);
          });
      },
      deleteGuest(id) {
        var r = confirm("Are you sure you want to delete this invitation?");
  
        if (!r) {
          return;
        }
  
        this.responseText = "Deleting...";
  
        axios(route("delete-guest", { id: id }))
          .then((result) => {
            this.responseText = "Deleted.";
            this.getReservation();
            let vm = this;
            setTimeout(
              function () {
                vm.responseText = null;
              },
              3000,
              vm
            );
          })
          .catch((err) => {
            this.responseText = null;
            this.handleApiError(err);
          });
      },
      updateInvitation(status) {
        if (status == "accepted") {
          var statusMsg = "accept";
        }
  
        if (status == "remove") {
          var statusMsg = "remove";
        }
  
        if (status == "declined") {
          var statusMsg = "decline";
        }
  
        var r = confirm(
          "Are you sure you want to " + statusMsg + " this invitation?"
        );
  
        if (!r) {
          return;
        }
  
        var formData = new FormData();
        formData.append("status", status);
        formData.append("invitation_id", this.reservationData.invitation.id);
  
        axios
          .post(route("update-invitation"), formData)
          .then((result) => {
            this.getReservation();
          })
          .catch((err) => {
            this.handleApiError(err);
          });
      },
    },
    mounted() {
      tippy(".undone", {
        content: "Coming Soon",
      });
  
      tippy(".delete-guest", {
        content: "Delete Invitation",
      });
  
    },
  };
  </script>
  