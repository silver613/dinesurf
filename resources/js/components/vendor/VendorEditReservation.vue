<template>
  <div class="auth-card auth-card2 reservation-auth-card bg-white">
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >
        <h3 class="mb-3">Reservation Information</h3>
        <!-- <div class="w-full mb-10 mt-3 flex justify-around">
          <jet-button
            class="ml-2"
            :type="'button'"
            @click="form.user_type = 'user'"
          >
            Add Existing User
          </jet-button>
          <jet-button
            class="ml-2"
            :type="'button'"
            @click="form.user_type = 'guest'"
          >
            Add New Guest
          </jet-button>
        </div> -->

        <search-users
          v-if="form.user_type == 'user'"
          @setUser="setUser"
        ></search-users>

        <add-guest
          @setGuest="setGuest"
          v-if="form.user_type == 'guest'"
        ></add-guest>

        <div class="flex flex-col w-full mb-10">
          <p>ID: {{ reservation.id }}</p>
          <p>First Name: {{ form.guest.first_name }}</p>
          <p>Last Name: {{ form.guest.last_name }}</p>
          <p>E-Mail: {{ form.guest.email }}</p>
          <p>Phone: {{ form.guest.phone }}</p>
          <p v-if="form.guest_id">
            <Link
              :href="route('vendors.edit-guest-page', { id: form.guest_id })"
              >Edit Guest >>></Link
            >
          </p>
        </div>

        <div class="flex flex-col w-full">
          <jet-label :value="'Party Size'"></jet-label>
          <select
            v-if="vendor.party_size"
            class="
              auth-card-input
              border-gray-300
              focus:border-indigo-300
              focus:ring
              focus:ring-indigo-200
              focus:ring-opacity-50
              rounded-md
              shadow-sm
              mt-1
              block
              w-full
            "
            v-model="form.party_size"
            name="party_size"
            id="party_size"
            required
          >
            <option v-for="(i, index) in vendor.party_size" :key="index">
              {{ i }}
            </option>
          </select>
          <input
            v-else
            type="number"
            v-model="form.party_size"
            class="auth-card-input"
            placeholder="Enter Party Size"
            min="0"
            required
          />
        </div>
        <div class="flex flex-col w-full">
          <jet-label
            >Date: <span class="ml-1">{{ formatedDate }}</span></jet-label
          >
          <!-- <input
            id="date"
            type="date"
            v-model="form.date"
            class="auth-card-input"
            placeholder="Enter Date"
            required
          /> -->
          <date-input
            v-model="form.date"
            class="auth-card-input"
            required
            placeholder="Enter Date"
            :enabledDays="enabledDays"
          >
          </date-input>
        </div>
        <div class="flex flex-col w-full">
          <p v-if="vendor.open_time && vendor.close_time" class="mb-1">
            Open: {{ vendor.hours }}
          </p>
          <p v-if="vendor.open_time && vendor.close_time">
            You can make reservation only during open hours
          </p>
          <jet-label
            >Time: <span class="ml-1">{{ formatedTime }}</span></jet-label
          >
          <input
            type="time"
            v-model="form.time"
            class="auth-card-input"
            placeholder="Enter Time"
            required
            id="timeInput"
            :min="timeMin"
            :max="timeMax"
          />
        </div>

        <!-- Phone Number -->
        <div class="col-span-6 sm:col-span-4">
          <jet-label for="phone" value="Phone Number" />
          <phone-input
            class="w-full phone-input"
            v-model="phone"
            :modelName="'phone'"
            @setValue="setPhone"
            required
          ></phone-input>
          <jet-input-error :message="form.errors.phone" class="mt-2" />
        </div>

        <div
          class="flex flex-col w-full mt-3"
          v-if="vendor.seating_preferences.length > 0"
        >
          <jet-label>Seating preferences</jet-label>
          <select
            class="
              auth-card-input
              border-gray-300
              focus:border-indigo-300
              focus:ring
              focus:ring-indigo-200
              focus:ring-opacity-50
              rounded-md
              shadow-sm
              mt-1
              block
              w-full
            "
            v-model="form.seating_preferences"
            name="party_size"
            id="party_size"
            required
          >
            <option
              v-for="(item, index) in vendor.seating_preferences"
              :key="index"
            >
              {{ item.name }}
            </option>
          </select>
          <!-- <div class="flex flex-wrap">
                        <div class="flex items-center mx-3 pt-1 mb-3" v-for="(item, index) in vendor.seating_preferences" :key="index">
                            <input
                                class="form-control mr-1"
                                :id="'sp'+item.name"
                                :name="'sp'+item.name"
                                type="checkbox"
                                :value="item.name"
                                v-model="form.seating_preferences"
                            />
                            <label for="day2" class="capitalize"
                                >{{ item.name }}</label
                            >
                        </div>
                    </div> -->
        </div>

        <div class="flex flex-col w-full mt-3">
          <jet-label for="note" value="Notes" />
          <textarea
            id="note"
            class="mt-1 block w-full"
            v-model="form.note"
            autocomplete="note"
          ></textarea>
          <jet-input-error :message="form.errors.note" class="mt-2" />
        </div>

        <div class="flex flex-col w-full my-5">
          <jet-label>Source</jet-label>
          <select
            class="
              auth-card-input
              border-gray-300
              focus:border-indigo-300
              focus:ring
              focus:ring-indigo-200
              focus:ring-opacity-50
              rounded-md
              shadow-sm
              mt-1
              block
              w-full
            "
            v-model="form.source"
            name="source"
            id="source"
          >
            <option>Walk-In</option>
            <option>Instagram</option>
            <option>Facebook</option>
            <option>Twitter</option>
            <option>Dinesurf</option>
            <option>Phone-Calls</option>
            <option>Whatsapp</option>
          </select>
        </div>

        <div class="text-center mb-5">
          <span>{{ responseMessage }}</span>
          <i class="fas fa-spinner fa-spin" v-if="form.processing"></i>
          <span class="text-green-800 font-bold text-lg">{{
            responseSuccess
          }}</span>
          <span
            class="text-red-700 font-bold text-lg"
            v-html="responseFail"
          ></span>
        </div>
        <button
          type="submit"
          class="jet-btn jet-btn-lg active:bg-gray-900 mx-2"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Update Reservation
        </button>
      </form>
      <div
        class="flex flex-col justify-center items-center my-10"
        v-if="regSuccess"
      >
        <img src="/images/success-icon.svg" class="h-16 md:h-32" />
        <h4 class="text-center my-3">Reservation successful</h4>
        <div class="flex justify-around">
          <Link
            style="padding: 15px 40px"
            class="jet-btn active:bg-gray-900 mx-2"
            :href="route('vendors.reservations')"
          >
            View Reservations
          </Link>
          <button
            style="padding: 15px 40px"
            class="jet-btn active:bg-gray-900 mx-2"
            @click="reset()"
          >
            Continue Editing
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import AddGuest from "@/components/AddGuest.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import JetInputError from "@/Jetstream/InputError.vue";


export default {
  components: {
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
    AddGuest,
    JetInputError
  },

  props: {
    vendor: {
      type: Object,
      required: true,
    },
    reservation: {
      type: Object,
      required: true,
    },
    status: String,
  },
  data() {
    return {
      timeMin: null,
      timeMax: null,
      showUsers: false,
      exampleModalShowing: false,
      today: new Date(),
      phone: this.unSetPhone(this.reservation.phone),
      form: this.$inertia.form({
        seating_preferences: this.reservation.seating_preferences,
        party_size: this.reservation.party_size,
        date: this.reservation.date,
        // start_time: "",
        // end_time: "",
        time: this.reservation.time,
        source: this.reservation.source,
        vendor_id: this.vendor.id,
        user_id: this.reservation.user_id,
        guest_id: this.reservation.guest_id,
        created_by_vendor: this.reservation.created_by_vendor,
        user_query: null,
        phone: this.reservation.phone,
        note: this.reservation.note,
        user_type: null,
        guest: {
          first_name: this.reservation.user
            ? this.reservation.user.first_name
            : this.reservation.guest.first_name,
          last_name: this.reservation.user
            ? this.reservation.user.last_name
            : this.reservation.guest.last_name,
          email: this.reservation.user
            ? this.reservation.user.email
            : this.reservation.guest.email,
          phone: this.reservation.user
            ? this.reservation.user.phone
            : this.reservation.guest.phone,
        },
      }),
      regSuccess: false,
      // response
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
      users: [],
    };
  },
  computed: {
    formatedTime() {
      if (this.form.time) {
        return moment(this.form.time, "HH:mm:ss").format("hh:mm A");
      }
      return;
    },
    formatedDate() {
      if (this.form.date) {
        return moment(this.form.date).format("dddd, MMM Do YYYY");
      }
      return;
    },
    enabledDays() {
      var days = [];

      if (this.vendor) {
        this.vendor.days.forEach((el) => {
          var dayIndex = el.id == 7 ? 0 : el.id;
          if (!days.includes(dayIndex)) {
            days.push(dayIndex);
          }
        });
      }

      return days.length > 0 ? days : null;
    },
  },
  methods: {
    reset() {
      this.regSuccess = false;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = null;
    },
    submit() {
      this.submitVendorForm(
        route("vendors.update-reservation", { id: this.reservation.id }),
        this.form,
        "Updating Reservation..."
      );
    },
    checkVendorTime() {
      if (this.form.time && this.vendor) {
        console.log(
          this.form.time,
          this.vendor.open_time,
          this.vendor.close_time,
          this.form.time < this.vendor.open_time,
          this.form.time > this.vendor.close_time
        );
        if (this.vendor.open_time && this.vendor.close_time) {
          if (this.form.time < this.vendor.open_time) {
            this.form.time = null;
          }
          if (this.form.time > this.vendor.close_time) {
            if (this.vendor.close_time == "00:00:00") {
              return true;
            }
            this.form.time = null;
          }
        }
      }

      return;
    },
    setUser(user_id) {
      this.form.user_id = user_id;
    },
    setGuest(guest) {
      this.form.guest = guest;
    },
  },
  mounted() {
    // console.log("today: ", this.today);
    // var today = new Date().toISOString().split("T")[0];
    // document.getElementById("date").setAttribute("min", today);

    var timeEl = document.getElementById("timeInput");

    if (timeEl && this.vdendor) {
      if (this.vendor.open_time) {
        timeEl.min = this.vendor.open_time;
        this.timeMin = this.vendor.open_time;
      }

      if (this.vendor.close_time) {
        timeEl.max = this.vendor.close_time;
        this.timeMax = this.vendor.close_time;
      }
    }
    if (this.$page.props.user) {
      this.phone = this.unSetPhone(this.$page.props.user.phone_number);
    }
  },
};
</script>
