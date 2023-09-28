<template>
  <div class="w-full reservation-auth-card bg-white">
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >

      
       <div class="flex flex-wrap md:flex-row md:px-20  flex-col w-full justify-between">

          <div class="md:w-[50%]">
            <span class="text-xl font-normal ">Personal Information</span>

           
            <div class="flex mt-5 flex-col w-full mb-3">
              <jet-label class="font-bold">ID: {{ form.id }}</jet-label>
            </div>

            <div class="flex flex-col w-full">
              <jet-label>First Name: </jet-label>
              <input
                type="text"
                v-model="form.first_name"
                class="auth-card-input"
                placeholder="Enter First Name"
                required
              />
            </div>

            <div class="flex flex-col w-full">
              <jet-label>Last Name: </jet-label>
              <input
                type="text"
                v-model="form.last_name"
                class="auth-card-input"
                placeholder="Enter Last Name"
              />
            </div>

            <div class="flex flex-col w-full">
              <jet-label>E-Mail: </jet-label>
              <input
                type="email"
                v-model="form.email"
                class="auth-card-input"
                placeholder="Enter E-Mail"
              />
            </div>

            <!-- Phone Number -->
            <div class="flex flex-col w-full">
              <jet-label for="phone" value="Phone:" />
              <phone-input
                class="w-full phone-input"
                v-model="phone"
                :modelName="'phone'"
                @setValue="setPhone"
              ></phone-input>
            </div>

            <div class="flex flex-col w-full mb-3">
              <jet-label for="birthday" value="Birthday:" />
              <input
                class="auth-card-input"
                id="birthday"
                type="text"
                v-model="form.birthday"
                autocomplete="birthday"
                placeholder="dd / mm"
              />
            </div>
            </div>


          <div  class="md:w-[40%]">
            <span class="text-xl font-normal ">Other  Details</span>

              <div class="flex flex-col mt-5 w-full  mb-3">
                <jet-label for="general_notes" value="General Notes:" />
                <textarea
                  id="general_notes"
                  type="text"
                  class="form-control"
                  v-model="form.general_notes"
                ></textarea>
              </div>

              <div
                class="flex flex-wrap mb-3"
                v-if="vendor.seating_preferences.length > 0"
              >
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
                  <label for="sp" class="capitalize">{{ item.name }}</label>
                </div>
              </div>

              <div class="flex flex-col w-full my-3">
                <jet-label
                  for="food_drink_preferences"
                  value="Food & Drink Prefrences"
                />
                <textarea
                  id="food_drink_preferences"
                  type="text"
                  class="form-control"
                  v-model="form.food_drink_preferences"
                ></textarea>
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

            </div>
        </div>

        <div class="md:px-20 w-full flex flex-col items-center ">

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
                style="padding: 15px 0"
                class=" rounded bg-dinesurf-green-400 text-white md:w-96   active:bg-gray-900 w-full"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
              >
                Update Guest
              </button>
           </div>

      </form>
      <div
        class="flex flex-col justify-center items-center my-10"
        v-if="regSuccess"
      >
        <img src="/images/success-icon.svg" class="h-16 md:h-32" />
        <h4 class="text-center my-3">Guest Updated Successfully</h4>
        <div class="flex justify-around">
          <div>
            <Link
              style="padding: 15px 40px"
              class="jet-btn active:bg-gray-900 mx-2"
              :href="route('vendors.guests')"
            >
              View Guests
            </Link>
            <button
              style="padding: 15px 40px"
              class="jet-btn active:bg-gray-900 mx-2"
              @click="reset()"
            >
              Back To Editing
            </button>
          </div>
        </div>
      </div>
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

export default {
  components: {
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
  },

  props: {
    vendor: {
      type: Object,
      required: true,
    },
    guest: {
      type: Object,
      required: true,
    },
    reservations: {
      type: Array,
      required: true,
    },
    status: String,
  },
  data() {
    return {
      showUsers: false,
      exampleModalShowing: false,
      today: new Date(),
      phone: this.unSetPhone(this.guest.phone),
      form: this.$inertia.form({
        id: this.guest.id,
        birthday: this.guest.birthday,
        source: this.guest.source,
        first_name: this.guest.first_name,
        last_name: this.guest.last_name,
        email: this.guest.email,
        phone: this.guest.phone,
        general_notes: this.guest.general_notes,
        seating_preferences: this.guest.seating_prefs_array,
        food_drink_preferences: this.guest.food_drink_preferences,
        vendor_id: this.vendor.id,
        user_id: this.guest.user_id,
        created_by_vendor: true,
        user_query: null,
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
    formatedBirthday() {
      if (this.form.birthday) {
        return moment(this.form.birthday).format("dddd, MMM Do YYYY");
      }
      return;
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
        route("vendors.edit-guest", { id: this.form.id }),
        this.form,
        "Updating Guest..."
      );
    },
  },
};
</script>
