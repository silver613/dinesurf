<template>
  <jet-authentication-card>
    <div class="auth-card-header flex flex-col items-center">
      <a :href="route('dashboard')">
        <img
          class="fill-current block  h-10 w-full object-contain mb-5"
          src="/images/company_logo.png"
        />
      </a>
      <!-- <h3>Account Registration</h3> -->
      <jet-validation-errors class="mb-4" />
    
    </div>

    <div class="px-10">
                <a
            :href="route('google-redirect')"
            class="
              flex
              justify-center
              w-full
              py-2
              px-2
              mt-3
              bg-white
              rounded-md
              text-sm
              font-medium
              text-gray-700
          border
            "
          >
          <img src="/images/google-favicon.png" class="h-6 w-6 mr-3" />
            <span class="text-black font-bold"> Continue with Google</span>
          </a>
    </div>
   

<div class="text-center font-bold mt-2">Or</div>


    <jet-validation-errors class="mb-4" />
    <form @submit.prevent="submit" class="px-10 py-4">

      <!-- <p class="mb-2">Please enter your details to proceed</p> -->
      <div>
        <!-- <jet-label for="first_name" value="Enter First Name" /> -->
        <jet-input
          id="first_name"
          type="text"
          class="mt-1 block w-full "
          v-model="form.first_name"
          required
          autofocus
          autocomplete="first_name"
          placeholder="Enter First Name"
        />
      </div>
      <div class="mt-5">
        <!-- <jet-label for="last_name" value="Enter Last Name" /> -->
        <jet-input
          id="last_name"
          type="text"
          class="mt-1 block w-full "
          v-model="form.last_name"
          required
          autofocus
          autocomplete="last_name"
          placeholder="Enter Last Name"
        />
      </div>
      <div class="mt-5 relative">
        <!-- <jet-label for="phone_number" value="Enter Phone Number" /> -->
        <!-- <jet-input
          id="phone_number"
          type="text"
          class="mt-1 block w-full "
          v-model="form.phone_number"
          required
          placeholder="Enter Phone Number"
        /> -->

        <phone-input
                        class="w-full phone-input"
                        v-model="form.phone_number"
                        :modelName="'phone_number'"
                        @setValue="setPhone"
                        required
                ></phone-input>
      </div>
      <div class="mt-5">
        <!-- <jet-label for="birthday" value="Enter Birthday" /> -->
        <!-- <input
          class="mt-1 block w-full "
          @input="formatBirthday"
          id="birthday"
          type="text"
          v-model="form.birthday"
          required
          autocomplete="birthday"
          placeholder="Birthdate: dd / mm"
        /> -->
      </div>

      <div class="mt-6">
        <!-- <jet-label for="email" value="Email" /> -->
        <jet-input
          id="email"
          type="email"
          class="mt-1 block w-full "
          v-model="form.email"
          required
          placeholder="Email"
        />
      </div>

      <div class="mt-6">
        <!-- <jet-label for="password" value="Password" /> -->
        <jet-input
          id="password"
          type="password"
          class="mt-1 block w-full "
          v-model="form.password"
          required
          autocomplete="new-password"
          placeholder="Password"
        />
      </div>

      <div class="mt-6">
        <!-- <jet-label
                    for="password_confirmation"
                    value="Confirm Password"
                /> -->
        <jet-input
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full "
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
          placeholder="Confirm Password"
        />
      </div>

      <div
        class="mt-4"
        v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
      >
        <jet-label for="terms">
          <div class="flex items-center">
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
                class="underline text-sm text-gray-600 hover:text-gray-900"
                >Terms of Service</a
              >
              and
              <a
                target="_blank"
                :href="route('policy.show')"
                class="underline text-sm text-gray-600 hover:text-gray-900"
                >Privacy Policy</a
              >
            </div>
          </div>
        </jet-label>
      </div>

      <div class="flex flex-col w-auto items-center justify-center mt-8">
        <jet-button
          class="mb-4 searchBtnBg w-full"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Create New Account
          <i class="fas fa-spinner fa-spin ml-1" v-if="form.processing"></i>
        </jet-button>
      </div>

    
      <div class="mt-5">
        Already have an account ?
        <Link
          :href="route('login')"
          class="underline text-sm primaryTextColor hover:text-gray-900"
        >
          Sign in.
        </Link>
      </div>

      <div
        class="flex flex-col items-center justify-center mt-4 social-auth-btn"
      >
        <!-- <a :href="route('google-redirect')">
          <img src="/images/btn_google_signin_light_normal_web@2x.png" />
        </a> -->
        <!-- <a :href="route('facebook-redirect')">
          <img src="/images/facebook-login.jpeg" />
        </a> -->
      </div>
    </form>
  </jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard2.vue";
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

  data() {
    return {
      form: this.$inertia.form({
        first_name: "",
        last_name: "",
        phone_number: "",
        birthday: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
        source: null,
        campaign_id: null,
      }),
    };
  },

  methods: {
    submit() {
      this.form.source = this.$page.props.tracking.source;
      this.form.campaign_id = this.$page.props.tracking.campaign_id;
      this.form.post(this.route("register"), {
        onFinish: () => this.form.reset("password", "password_confirmation"),
        onSuccess: () => window.location.reload(),
      });
    },
  },
  mounted() {
    document.title = "Register - Dinesurf";
  },
};
</script>