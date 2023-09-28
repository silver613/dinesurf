<template>
  <div class="auth-card auth-card2 reservation-auth-card bg-white">
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >
        <h3 class="mb-3">Create a Guest</h3>
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

        <div class="flex flex-col w-full mb-3">
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
          class="jet-btn active:bg-gray-900"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Create Guest
        </button>
      </form>
      <div
        class="flex flex-col justify-center items-center my-10"
        v-if="regSuccess"
      >
        <img src="/images/success-icon.svg" class="h-16 md:h-32" />
        <h4 class="text-center my-3">Guest Created Successfully</h4>
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
              Create Another Guest
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
    status: String,
  },
  data() {
    return {
      showUsers: false,
      exampleModalShowing: false,
      today: new Date(),
      phone: null,
      form: this.$inertia.form({
        birthday: "",
        first_name: null,
        source: null,
        last_name: null,
        email: null,
        phone: null,
        general_notes: null,
        seating_preferences: [],
        food_drink_preferences: null,
        vendor_id: this.vendor.id,
        user_id: null,
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
      this.form = this.$inertia.form({
        birthday: "",
        first_name: null,
        last_name: null,
        email: null,
        phone: null,
        general_notes: null,
        seating_preferences: [],
        food_drink_preferences: null,
        vendor_id: this.vendor.id,
        user_id: null,
        guest_id: null,
        created_by_vendor: true,
        user_query: null,
      });
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = null;
    },
    submit() {
      this.submitVendorForm(
        route("vendors.create-guest"),
        this.form,
        "Creating Guest..."
      );
    },
  },
};
</script>
