<template>
  <div class="auth-card auth-card2 reservation-auth-card bg-white">
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >
        <h3 class="mb-3">Admin Information</h3>

        <div class="flex flex-col w-full mb-3">
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

        <div class="flex flex-col w-full">
          <jet-label>Role</jet-label>
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
              block
              w-full
            "
            v-model="form.role_id"
            name="source"
            id="source"
            required
          >
            <option
              v-for="(item, index) in roles"
              :key="index"
              :value="item.id"
            >
              {{ item.name }}
            </option>
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
          Update Admin
        </button>
      </form>
      <div
        class="flex flex-col justify-center items-center my-10"
        v-if="regSuccess"
      >
        <img src="/images/success-icon.svg" class="h-16 md:h-32" />
        <h4 class="text-center my-3">Admin Updated Successfully</h4>
        <div class="flex justify-around">
          <div>
            <Link
              style="padding: 15px 40px"
              class="jet-btn active:bg-gray-900 mx-2"
              :href="route('vendors.admins')"
            >
              View Admins
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
    admin: {
      type: Object,
      required: true,
    },
    status: String,
  },
  data() {
    return {
      form: this.$inertia.form({
        id: this.admin.id,
        first_name: this.admin.first_name,
        last_name: this.admin.last_name,
        email: this.admin.email,
        role_id: null,
      }),
      regSuccess: false,
      // response
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
      roles: [],
    };
  },
  methods: {
    getRole(admin) {
      var role = null;
      if (admin) {
        if (admin.admin_roles) {
          admin.admin_roles.forEach((el) => {
            role = el.name;
          });
        }
      }
      return role;
    },
    reset() {
      this.regSuccess = false;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = null;
    },
    submit() {
      this.submitVendorForm(
        route("vendors.edit-admin", { id: this.form.id }),
        this.form,
        "Updating admin..."
      );
    },
    getRoles() {
      axios
        .get(route("data.vendors.roles"))
        .then((result) => {
          this.roles = result.data.models.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  mounted() {
    this.getRoles();
  },
};
</script>
