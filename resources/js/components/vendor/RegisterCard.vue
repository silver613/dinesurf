<template>
  <div class="auth-card !px-4">
    <div class="auth-card-header flex flex-col items-center">
      <a :href="route('vendors.index')">
        <img
          class="fill-current block md:hidden h-7 w-auto object-contain mb-5"
          src="/images/company_logo.png"
        />
      </a>
      <h3>Restaurant Registration</h3>
      <p>Please enter your details to proceed</p>
      <jet-validation-errors class="mb-4" />
      <div v-if="status" class="mt-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
    </div>
    <div class="auth-card-body text-center">
      <div class="vendor-step">
        <form @submit.prevent="submit">
          <div class="text-left">
            <h4 class="text-center">Personal Information</h4>
            <div class="flex flex-col md:flex-row justify-between">
              <div class="mb-6 w-full md:w-48 mx-0 md:mx-5">
                <jet-label for="first_name" value="First Name" />
                <jet-input
                  id="first_name"
                  name="first_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.first_name"
                  :required="true"
                  autofocus
                  autocomplete="first_name"
                />
              </div>
              <div class="mb-6 w-full md:w-48 mx-0 md:mx-5">
                <jet-label for="last_name" value="Last Name" />
                <jet-input
                  id="last_name"
                  name="last_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.last_name"
                  :required="true"
                  autofocus
                  autocomplete="last_name"
                />
              </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between">
              <div class="mb-6 w-full md:w-48 mx-0 md:mx-5">
                <jet-label for="phone" value="Phone Number" />
                <!-- <jet-input
                  id="phone"
                  name="phone"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.phone_number"
                  :required="true"
                  autofocus
                  autocomplete="phone"
                /> -->

                <phone-input
                        class="w-full phone-input"
                        v-model="form.phone_number"
                        :modelName="'phone_number'"
                        @setValue="setPhone"
                        required
                ></phone-input>
              </div>
              <div class="mb-6 w-full md:w-48 mx-0 md:mx-5">
                <jet-label for="email" value="Email" />
                <jet-input
                  id="email"
                  name="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  :required="true"
                />
              </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between">
              <div class="mb-6 w-full md:w-48 mx-0 md:mx-5">
                <jet-label for="password" value="Password" />
                <jet-input
                  id="password"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password"
                  required
                  autocomplete="new-password"
                />
              </div>

              <div class="mb-6 w-full md:w-48 mx-0 md:mx-5">
                <jet-label
                  for="password_confirmation"
                  value="Confirm Password"
                />
                <jet-input
                  id="password_confirmation"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password_confirmation"
                  required
                  autocomplete="new-password"
                />
              </div>
            </div>
            <div class="mt-4">
              <jet-label for="terms">
                <div class="flex pl-4">
                  <jet-checkbox
                    name="terms"
                    id="terms"
                    v-model:checked="form.terms"
                  />

                  <div class="ml-2">
                    I agree to the
                    <a
                      target="_blank"
                      :href="route('terms.show')"
                      class="
                        underline
                        text-sm text-gray-600
                        hover:text-gray-900
                      "
                      >Terms of Service</a
                    >
                    and
                    <a
                      target="_blank"
                      :href="route('policy.show')"
                      class="
                        underline
                        text-sm text-gray-600
                        hover:text-gray-900
                      "
                      >Privacy Policy</a
                    >
                  </div>
                </div>
              </jet-label>
            </div>
            <div class="flex justify-center items-center w-full mt-4">
              <jet-button
                class="
                  mb-6
                  mx-0
                  md:mx-5
                  btn-md btn-auth btn-red
                "
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                :type="'submit'"
              >
                Submit
                <i
                  class="fas fa-spinner fa-spin ml-1"
                  v-if="form.processing"
                ></i>
              </jet-button>
            </div>
          </div>
        </form>
        <p>
          Already Registered?
          <Link :href="route('vendors.login.index')" class="dinesurf-green">
            Login here
          </Link>
        </p>
      </div>
    </div>
  </div>
</template>


<script>
import SiteLayout from "@/Layouts/SiteLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default {
  components: {
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
    SiteLayout,
  },
  data() {
    return {
      regSuccess: false,
      form: this.$inertia.form({
        first_name: "",
        last_name: "",
        phone_number: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
        source: null,
        campaign_id: null,
      }),
    };
  },
  props: {
    currentStep: Number,
    status: String,
  },
  methods: {
    check() {
      this.form.company_address =
        document.getElementById("company_address").value;
      console.log(document.getElementById("company_address").value);
    },
    submit() {
      this.form.source = this.$page.props.tracking.source;
      this.form.campaign_id = this.$page.props.tracking.campaign_id;
      this.form.post(this.route("register"), {
        onFinish: () => this.form.reset("password", "password_confirmation"),
        onSuccess: () => window.location.reload(),
      });
    },
  },
};
</script>