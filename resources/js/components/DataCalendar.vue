<template>
  <div class="pt-5 md:pt-12 px-3 ">
    <div class="mx-auto  sm:px-2 lg:px-8">
      <div class="w-44 pl-3">
            <filter-button
              :showFilter="showFilter"
              :text="'Calendar'"
              @click="showFilter = !showFilter"
              class="mb-3 lg:hidden"
            ></filter-button>
          </div>
      <div class="flex flex-wrap mb-0 md:mb-20 w-full justify-between">
        <div class="flex flex-col md:flex-row w-full ">
          
          <transition
            enter-active-class="transition-all duration-750 ease-out"
            leave-active-class="transition-all duration-750 ease-in"
            enter-class="opacity-0 scale-75"
            enter-to-class="opacity-100 scale-100"
            leave-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-75"
          >
            <div
              :class="showFilter ? 'block ' : 'hidden'"
              class="lg:block w-full md:w-2/6 flex flex-col"
            >
              <div class="py-3">
                <p class="font-normal mb-3 mt-5 px-3 text-xs text-base">
                  Dates with Reservations are Highlighted
                </p>
                <input
                  style="margin-bottom: 0; display: none"
                  class="auth-card-input"
                  id="calendar"
                />
              </div>
              <div></div>
            </div>
          </transition>
          <div class="w-full md:w-96 md:pl-9 pt-7 md:mt-0 md:pt-0">
            <p class="font-bold mt-3 px-3 text-sm">
              {{ readableDate(date) }}
            </p>
            <p class="font-normal mb-4   px-3 text-sm">
              {{ models.length }} Reservation(s)
            </p>
            <div
              class="overflow-y-scroll calendar-scroll px-3"
              id="calendarScroll"
            >
              <div class="flex flex-col py-8 pt-0">
                <div class="shadow-md">
                  <div
                    class="tab w-full overflow-hidden border-t"
                    v-for="(hour, index) in hours"
                    :key="index"
                    :class="hour.show ? '' : 'opacity-0'"
                  >
                    <input
                      class=" opacity-0"
                      :id="'tab-single' + index"
                      type="radio"
                      name="calendarTabs"
                    />
                    <label
                      class="
                        accordion-label
                        block
                        p-5
                        leading-normal
                        cursor-pointer
                        text-xs
                      "
                      :class="
                        hour.models.length
                          ? 'dinesurf-green-bg text-white font-bold'
                          : 'bg-white'
                      "
                      @click="toggleTab = !toggleTab"
                      :for="'tab-single' + index"
                      >{{ hour.time }} - {{ hour.models.length }} Reservation(s)
                    </label>
                    <div
                      class="
                        tab-content
                        overflow-hidden
                        border-l-2
                        bg-gray-100
                        border-green-500
                        leading-normal
                        w-full
                      "
                    >
                      <div class="px-2 ">
                        <mini-data-table
                          v-if="hour.models.length > 0"
                          :models="hour.models"
                          :columns="columns"
                          :actions="actions"
                          :rows="getModelRows(hour.models)"
                          @runAction="runAction"
                          :indexLink="route('vendors.reservation')"
                        ></mini-data-table>
                        <div class="w-full p-4" v-else>No Reservations</div>
                      </div>
                    </div>
                  </div>
                  <div class="w-full hidden md:block" style="height: 200px"></div>
                  <div
                    class="w-full"
                    style="height: 1000px"
                    v-show="toggleTab"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Send Emails -->
    <send-email
      :showEmailModal="showEmailModal"
      :backendActionData="actionData"
    ></send-email>
    <!-- End Send Emails -->

    <!-- Send Texts -->
    <send-text
      :showTextModal="showTextModal"
      :backendActionData="actionData"
    ></send-text>
    <!-- End Send Texts -->
  </div>
</template>


<script>
import flatpickr from "flatpickr";
import { throttle } from "lodash";
import moment from "moment";
import FilterButton from "@/components/FilterButton.vue";
import "flatpickr/dist/flatpickr.css";

export default {
  components: {
    FilterButton,
  },
  props: {
    // models: Array,
    initialDate: String,
    initialDates: Array,
  },
  data() {
    return {
      showFilter: false,
      fp: null,
      date: this.initialDate,
      hours: [],
      models: [],
      columns: [
        {
          name: "created_at",
          db_name: "created_at",
          sortable: true,
        },
        {
          name: "id",
          db_name: "id",
          sortable: true,
        },
        {
          name: "user",
        },
        {
          name: "phone",
          db_name: "phone",
        },

        {
          name: "date",
          db_name: "date",
          sortable: true,
        },
        {
          name: "time",
        },
        {
          name: "party_size",
          db_name: "party_size",
          sortable: true,
        },
        {
          name: "status",
        },
        {
          name: "past",
        },
        // {
        //   name: "seating_preferences",
        // },
      ],
      actions: [
        {
          value: "approved",
          name: "Approve",
        },
        {
          value: "cancelled",
          name: "Cancel",
        },
        {
          value: "un-approve",
          name: "Make Pending",
        },
        {
          value: "email_users",
          name: "Send E-Mail",
        },
        {
          value: "text_users",
          name: "Send Text",
        },
      ],
      actionData: {},
      showEmailModal: false,
      showTextModal: false,
      defaultDates: this.initialDates,
      toggleTab: true,
    };
  },
  methods: {
    loadData() {
      const urlSearchParams = new URLSearchParams(window.location.search);
      var params = Object.fromEntries(urlSearchParams.entries());

      if (params.date == undefined) {
        params.date = this.initialDate;
      }

      axios
        .get(route("data.vendors.reservations", { ...params }))
        .then((result) => {
          this.models = result.data.models.data;
          this.defaultDates = result.data.dates_with_reservations;
          this.setHours();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getModelRows(models) {
      var modelRows = [];

      models.forEach((el) => {
        var statusColor = this.getModelStatusColor(el);
        modelRows.push([
          el.formatted_created_at,
          el.id,
          this.getUser(el),
          el.phone,
          el.formated_date,
          el.formated_time,
          el.party_size,
          '<span class="font-bold ' +
            statusColor +
            '">' +
            el.status +
            "</span>",
          el.past ? "yes" : "no",
          // el.seating_preferences,
        ]);
      });

      return modelRows;
    },
    startcalendar() {
      var element = document.getElementById("flatpickrCalendar");
      if (element) {
        element.remove();
      }

      if (this.fp) {
        this.fp.destroy();
      }

      const vm = this;

      this.fp = flatpickr("#calendar", {
        inline: true,
        dateFormat: "Y-m-d",
        mode: "multiple",
        defaultDate: this.defaultDates,
        onChange: function (selectedDates, dateStr, instance) {
          vm.date = moment(instance.latestSelectedDateObj).format("YYYY-MM-DD");
          console.log("date: ", vm.date);
          vm.handleNewDate();
        },
      });

      setTimeout(
        () => {
          var elements = document.getElementsByClassName("flatpickr-calendar");
          if (elements) {
            elements[0].setAttribute("id", "flatpickrCalendar");
          }
        },
        1000,
        vm
      );
    },
    initAccordion() {
      var myRadios = document.getElementsByName("calendarTabs");
      var setCheck;
      var x = 0;
      for (x = 0; x < myRadios.length; x++) {
        myRadios[x].onclick = function () {
          if (setCheck != this) {
            setCheck = this;
          } else {
            this.checked = false;
            setCheck = null;
          }
        };
      }
    },
    setHours() {
      var hours = [];
      hours.push({ time: 12 + " AM", models: [], show: true });
      for (let index = 1; index <= 11; index++) {
        hours.push({ time: index + " AM", models: [], show: true });
      }
      hours.push({ time: 12 + " PM", models: [], show: true });
      for (let index = 1; index <= 12; index++) {
        hours.push({ time: index + " PM", models: [], show: true });
      }
      this.hours = hours;
      this.setModelsPerHour();
    },
    setModelsPerHour() {
      var first_index = null;
      this.hours.forEach((hr, index) => {
        this.models.forEach((el) => {
          if (el.hour_range == hr.time) {
            this.hours[index].models.push(el);
            if (!first_index) {
              first_index = index;
            }
          }
        });
      });

      this.startcalendar();
      var vm = this;
      setTimeout(
        function () {
          vm.initAccordion();
          if (first_index) {
            vm.scrollChildIntoView(
              "calendarScroll",
              "tab-single" + first_index
            );
          }
        },
        500,
        vm,
        first_index
      );
    },
    readableDate(date) {
      return moment(date, "YYYY-MM-DD").format("dddd Do MMMM, YYYY");
    },
    getModelStatusColor(reservation) {
      if (reservation.status == "approved") {
        return "text-green-700";
      }

      if (reservation.status == "pending") {
        return "text-yellow-500";
      }

      if (reservation.status == "failed" || reservation.status == "cancelled") {
        return "text-red-700";
      }

      return;
    },
    runAction(data) {
      var action = data.action;
      var ids = data.ids;
      var all = data.all;
      // alert("run action " + action + " for models " + [...ids]);

      if (!action) {
        return;
      }

      if (ids.length <= 0) {
        alert(
          "Please select a Reservation or some reservations you want send an E-Mail"
        );
        return;
      }

      if (action == "un-approve" || action == "un-cancel") {
        var r = confirm(
          "Are you sure you want to set this Reservation to pending?"
        );
        action = "pending";
      }

      if (action == "cancelled") {
        var r = confirm("Are you sure you want to cancel this reservation?");
      }

      if (action == "approved") {
        var r = confirm("Are you sure you want to approve this reservation?");
      }

      if (action == "email_users") {
        this.actionData = {
          ids: ids,
          action: action,
          allMatching: all,
          filters: this.filters,
          type: this.type,
          subject: null,
          message: null,
          banner: null,
          action_model: "reservation",
        };
        this.showEmailModal = true;
      }

      if (action == "text_users") {
        this.actionData = {
          ids: ids,
          action: action,
          allMatching: all,
          filters: this.filters,
          type: this.type,
          message: null,
          action_model: "reservation",
        };
        this.showTextModal = true;
      }

      if (!r) {
        return;
      }

      if (
        action == "un-approve" ||
        action == "un-cancel" ||
        action == "cancelled" ||
        action == "approved" ||
        action == "pending"
      ) {
        this.$inertia.post(
          route("vendors.toggle-reservation"),
          {
            ids: ids,
            action: action,
            allMatching: all,
            filters: this.filters,
            type: this.type,
          },
          {
            onFinish: (visit) => {
              this.loadData();
            },
          }
        );
      }
    },
    lockCalendar() {
      var elements = document.getElementsByClassName("flatpickr-calendar");
      if (elements) {
        elements[0].setAttribute("style", "pointer-events: none");
      }
      console.log("calendar locked");
    },
    unlockCalendar() {
      var elements = document.getElementsByClassName("flatpickr-calendar");
      if (elements) {
        if (elements[0]) {
          elements[0].setAttribute("style", "pointer-events: auto");
        }
      }
      console.log("calendar unlocked");
    },
    handleNewDate() {
      console.log("changed");
      var vm = this;
      setTimeout(
        () => {
          vm.unlockCalendar();
        },
        500,
        vm
      );

      this.$inertia.get(
        route("vendors.reservations"),
        {
          view: "calendar",
          date: this.date,
        },
        {
          onFinish: (visit) => {
            this.loadData();
            this.unlockCalendar();
          },
        }
      );
    },
  },
  mounted() {
    this.loadData();
    this.unlockCalendar();
  },
};
</script>


<style scoped>
/* Tab content - closed */

.tab-content {
  max-height: 0;
  -webkit-transition: max-height 0.35s;
  -o-transition: max-height 0.35s;
  transition: max-height 0.35s;
}
/* :checked - resize to full height */
.tab input:checked ~ .tab-content {
  max-height: 150vh;
}
/* Label formatting when open */
.tab input:checked + label {
  font-size: 1.25rem; /*.text-xl*/
  padding: 1.25rem; /*.p-5*/
  border-left-width: 2px; /*.border-l-2*/
  border-color: #006c33; /*.border-green*/
  background-color: #f8fafc; /*.bg-gray-100 */
  color: #006c33; /*.text-green*/
}
/* Icon */
.tab label::after {
  float: right;
  right: 0;
  top: 0;
  display: block;
  width: 1.5em;
  height: 1.5em;
  line-height: 1.5;
  font-size: 1.25rem;
  text-align: center;
  -webkit-transition: all 0.35s;
  -o-transition: all 0.35s;
  transition: all 0.35s;
}
/* Icon formatting - closed */
.tab input[type="checkbox"] + label::after {
  content: "+";
  font-weight: bold; /*.font-bold*/
  border-width: 1px; /*.border*/
  border-radius: 9999px; /*.rounded-full */
  border-color: #b8c2cc; /*.border-grey*/
}
.tab input[type="radio"] + label::after {
  content: "\25BE";
  font-weight: bold; /*.font-bold*/
  border-width: 1px; /*.border*/
  border-radius: 9999px; /*.rounded-full */
  border-color: #b8c2cc; /*.border-grey*/
}
/* Icon formatting - open */
.tab input[type="checkbox"]:checked + label::after {
  transform: rotate(315deg);
  background-color: #006c33; /*.bg-green*/
  color: #f8fafc; /*.text-grey-lightest*/
}
.tab input[type="radio"]:checked + label::after {
  transform: rotateX(180deg);
  background-color: #006c33; /*.bg-green*/
  color: #f8fafc; /*.text-grey-lightest*/
}
.calendar-scroll {
  height: 300px;
  scroll-behavior: smooth;
}
</style>
