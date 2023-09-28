<template>
  <jet-authentication-card>
    <jet-validation-errors class="mb-4" />

    <div class="px-0 py-4 flex justify-center border-b">
      <span class="font-bold text-lg"> Sign Up</span>
    </div>

    <form @submit.prevent="submit" class="px-10 py-4">
      <div>
        <!-- <jet-label for="first_name" value="Enter First Name" /> -->
        <jet-input
          id="first_name"
          type="text"
          class="mt-1 block w-full border-black rounded-none"
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
          class="mt-1 block w-full border-black rounded-none"
          v-model="form.last_name"
          required
          autofocus
          autocomplete="last_name"
          placeholder="Enter Last Name"
        />
      </div>
      <div class="mt-5">
        <!-- <jet-label for="phone_number" value="Enter Phone Number" /> -->
        <jet-input
          id="phone_number"
          type="text"
          class="mt-1 block w-full border-black rounded-none"
          v-model="form.phone_number"
          required
          placeholder="Enter Phone Number"
        />
      </div>
      <div class="mt-5">
        <!-- <jet-label for="birthday" value="Enter Birthday" /> -->
        <input
          class="mt-1 block w-full border-black rounded-none"
          @input="formatBirthday"
          id="birthday"
          type="text"
          v-model="form.birthday"
          required
          autocomplete="birthday"
          placeholder="Birthdate: dd / mm"
        />
      </div>

      <div class="mt-6">
        <!-- <jet-label for="email" value="Email" /> -->
        <jet-input
          id="email"
          type="email"
          class="mt-1 block w-full border-black rounded-none"
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
          class="mt-1 block w-full border-black rounded-none"
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
          class="mt-1 block w-full border-black rounded-none"
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
        </jet-button>
      </div>

      <div class="text-center font-bold">Or</div>

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
          text-sm
          font-medium
          text-gray-700
          border border-black
        "
      >
        <i class="fab fa-google primaryTextColor mt-1 mr-3"></i>
        <span class="text-black font-bold"> Continue with Google</span>
      </a>

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
      }),
    };
  },

  methods: {
    submit() {
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
