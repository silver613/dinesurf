<template>
  <div class="auth-card">
    <div class="auth-card-header flex flex-col items-center">
      <a :href="route('index')">
        <img
          class="fill-current block md:hidden h-7 w-auto object-contain mb-5"
          src="/images/company_logo.png"
        />
      </a>
    </div>
    <div class="auth-card-body text-left">
      <form @submit.prevent="submit" class="flex flex-col w-full">
        <h3>Edit Reservation</h3>
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
            :min="today.getDate()"
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
        <!-- <div class="flex flex-col w-full">
          <jet-label :value="'Time'" class="mb-3"></jet-label>
          <div class="flex w-full">
            <div class="flex flex-col w-full">
              <jet-label :value="'From'"></jet-label>
              <input
                type="time"
                v-model="form.start_time"
                class="auth-card-input"
                placeholder="From"
                required
              />
            </div>
            <div class="flex flex-col w-full">
              <jet-label :value="'To'"></jet-label>
              <input
                type="time"
                v-model="form.end_time"
                class="auth-card-input"
                placeholder="To"
                required
              />
            </div>
          </div>
        </div> -->
        <div class="flex flex-col w-full">
          <jet-label
            >Time: <span class="ml-1">{{ formatedTime }}</span></jet-label
          >
          <input
            type="time"
            v-model="form.time"
            class="auth-card-input"
            placeholder="Enter Time"
            required
          />
        </div>

        <!-- Phone Number -->
        <div class="col-span-6 sm:col-span-4">
          <jet-label for="phone_number" value="Phone Number" />
          <jet-input
            id="phone"
            type="text"
            class="auth-card-input"
            v-model="form.phone"
            autocomplete="phone"
            required
          />
          <jet-input-error :message="form.errors.phone" class="mt-2" />
        </div>

        <div
          class="flex flex-col w-full mt-3"
          v-if="vendor.seating_preferences.length > 0"
        >
          <jet-label>Seating preferences</jet-label>
          <div class="flex flex-wrap">
            <div
              class="flex items-center mx-3 pt-1 mb-3"
              v-for="(item, index) in vendor.seating_preferences"
              :key="index"
            >
              <input
                class="form-control mr-1"
                :id="'sp' + item.name"
                :name="'sp' + item.name"
                type="checkbox"
                :value="item.name"
                v-model="form.seating_preferences"
              />
              <label for="day2" class="capitalize">{{ item.name }}</label>
            </div>
          </div>
          <!-- <select
                        
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
                         v-for="(item, index) in vendor.seating_preferences" :key="index"
                            
                        >
                           {{ item.name }}
                        </option>
                    </select> -->
        </div>

        <!-- Notes -->
        <div class="flex flex-col w-full mb-5">
          <jet-label for="note" value="Additional Information" />
          <textarea
            id="note"
            class="mt-1 block w-full"
            v-model="form.note"
            autocomplete="note"
          ></textarea>
          <jet-input-error :message="form.errors.note" class="mt-2" />
        </div>

        <div class="text-center mb-5">
          <span>{{ responseMessage }}</span>
          <i class="fas fa-spinner fa-spin" v-if="responseSpin"></i>
          <span class="text-green-800 font-bold text-lg">{{
            responseSuccess
          }}</span>
          <span class="text-red-700 font-bold text-lg">{{ responseFail }}</span>
        </div>
        <button
          type="submit"
          class="btn-md btn-auth btn-red"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Save
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
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
    JetInputError,
  },

  props: ["reservation", "vendor"],
  data() {
    return {
      today: new Date(),
      form: this.$inertia.form({
        id: this.reservation.id,
        party_size: this.reservation.party_size,
        date: this.reservation.date,
        note: this.reservation.note,
        // start_time: this.reservation.start_time,
        // end_time: this.reservation.end_time,
        time: this.reservation.time,
        vendor_id: this.vendor.id,
        seating_preferences: this.reservation.seating_prefs_array,
        phone: this.reservation.phone,
      }),
      //   regSuccess: false,
      // Payment
      responseSpin: false,
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
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
    submit() {
      this.responseSpin = true;
      this.form.processing = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Making Reservation...";

      fetch(route("update-reservation"), {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": this.$page.props.csrf_token,
        },
        body: JSON.stringify(this.form),
      })
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          if (result.success) {
            toastr.success(result.message);
            this.responseSuccess = null;
            this.responseFail = null;
            this.responseMessage = result.message + "!";
            var vm = this;
            setTimeout(
              function () {
                vm.form.processing = false;
                // vm.regSuccess = true;
                vm.$inertia.get(
                  route("reservation", [{ id: vm.reservation.id }])
                );
              },
              1500,
              vm
            );
          } else {
            toastr.error("An Error Occured!");
            this.form.processing = false;
            this.responseSpin = false;
            this.responseSuccess = null;
            this.responseMessage = null;

            if (result.errors) {
              this.responseFail = this.showErrorMsg(result.errors);
            } else {
              if (result.message == "CSRF token mismatch.") {
                window.location.reload();
              }
              this.responseFail = result.message;
            }
          }
        })
        .catch((err) => {
          this.responseSpin = false;
          this.form.processing = false;
          this.responseSuccess = null;
          this.responseFail = err;
          this.handleApiError(err);
        });
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
  },
  mounted() {
    // console.log("today: ", this.today);
    // var today = new Date().toISOString().split("T")[0];
    // document.getElementById("date").setAttribute("min", today);

    var timeEl = document.getElementById("timeInput");

    if (timeEl && this.vdendor) {
      if (this.vendor.open_time) {
        timeEl.min = this.vendor.open_time;
      }

      if (this.vendor.closetime) {
        timeEl.max = this.vendor.closetime;
      }
    }

    if (this.reservation) {
      if (!this.reservation.phone && this.$page.props.user) {
        this.form.phone = this.$page.props.user.phone_number;
      }
    }
  },
};
</script>
