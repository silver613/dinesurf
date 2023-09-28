<template>
  <div class="auth-card auth-card2 reservation-auth-card bg-white">
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >
        <h3 class="mb-3">Make a reservation</h3>
        <div class="w-full mb-10 mt-3 flex justify-around">
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
        </div>

        <search-users
          v-if="form.user_type == 'user'"
          @setUser="setUser"
        ></search-users>

        <add-guest
          @setGuest="setGuest"
          :phone="form.guest.phone"
          v-if="form.user_type == 'guest'"
        ></add-guest>

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
            @setValue="setUserPhone"
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

        <!-- Notes -->
        <div class="flex flex-col w-full mt-3">
          <jet-label for="note" value="Additional Information" />
          <textarea
            id="note"
            class="mt-1 block w-full"
            v-model="form.note"
            autocomplete="note"
          ></textarea>
          <jet-input-error :message="form.errors.note" class="mt-2" />
        </div>

        <!-- Source -->

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
          style="padding: 15px 40px"
          class="jet-btn jet-btn-lg active:bg-gray-900"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Confirm Reservation
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
            Make Another Reservation
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import LoginCard from "@/components/LoginCard.vue";
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
    LoginCard,
    AddGuest,
    JetInputError,
  },

  props: {
    vendor: {
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
      phone: null,
      form: this.$inertia.form({
        seating_preferences: [],
        party_size: "",
        date: "",
        // start_time: "",
        // end_time: "",
        time: "",
        note: null,
        source: null,

        vendor_id: this.vendor.id,
        user_id: null,
        created_by_vendor: true,
        user_query: null,
        phone: null,
        user_type: null,
        guest: {
          first_name: null,
          last_name: null,
          email: null,
          phone: null,
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
      this.form = this.$inertia.form({
        party_size: "",
        date: "",
        // start_time: "",
        // end_time: "",
        time: "",
        vendor_id: this.vendor.id,
        user_id: null,
        created_by_vendor: true,
        seating_preferences: [],
        guest: {},
      });
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = null;
    },
    setUserPhone(data) {
      if (data) {
        this.form.phone = data.val;
        this.form.guest.phone = data.val;
        if (this.$refs.guestPhone) {
          this.$refs.guestPhone = data.val;
        }
        this.setPhone(data);
      }
    },
    submit() {
      this.submitVendorForm(
        route("vendors.make-reservation"),
        this.form,
        "Making Reservation..."
      );
    },
    setUser(user_id) {
      this.form.user_id = user_id;
    },
    setGuest(guest) {
      this.form.guest.first_name = guest.first_name;
      this.form.guest.last_name = guest.last_name;
      this.form.guest.email = guest.email;

      console.log("guest.phone: ", guest.phone);
      if (guest.phone && guest.phone != "") {
        this.form.guest.phone = guest.phone;
        this.form.phone = guest.phone;
        this.phone = guest.phone;
        console.log(
          ".phone: ",
          this.form.phone,
          this.form.guest.phone,
          this.phone
        );
      } else {
        this.form.guest.phone = this.form.phone;

        console.log(".phone: ", this.form.phone, this.form.guest.phone);
      }
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
