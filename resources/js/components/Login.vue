<template>
  <jet-authentication-card>
    <jet-validation-errors class="mb-4" />

    <!-- <jet-authentication-card-logo /> -->

    <div class="px-0 py-4 flex justify-center">
      <jet-authentication-card-logo />
    </div>

    <div class="px-0 py-4 flex justify-center border-b">
      <span class="font-bold text-lg"> Sign In</span>
    </div>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>
    <form @submit.prevent="submit" class="px-10 py-4">
      <div class="mt-6">
        <!-- <jet-label for="email" value="Email" /> -->
        <jet-input
          id="email"
          name="email"
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
          name="password"
          type="password"
          class="mt-1 block w-full border-black rounded-none"
          v-model="form.password"
          required
          autocomplete="new-password"
          placeholder="Password"
        />
      </div>

      <div class="block mt-4 justify-between flex flex-row">
        <label class="flex items-center">
          <jet-checkbox name="remember" v-model:checked="form.remember" />
          <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </label>

        <Link
          :href="route('password.request')"
          class="underline text-sm text-gray-600 hover:text-gray-900"
        >
          Forgot your password?
        </Link>
      </div>

      <!-- <div class="flex justify-around w-full">
                   
                    
                </div> -->

      <!-- <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="
                            underline
                            text-sm text-gray-600
                            hover:text-gray-900
                        "
                    >
                        Forgot your password?
                    </Link> -->

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
          Login
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
        New to Dinesurf ?
        <Link
          :href="route('login')"
          class="underline text-sm primaryTextColor hover:text-gray-900"
        >
          Sign in.
        </Link>
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

  props: {
    canResetPassword: Boolean,
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        email: "",
        password: "",
        remember: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form
        .transform((data) => ({
          ...data,
          remember: this.form.remember ? "on" : "",
        }))
        .post(this.route("login"), {
          onFinish: () => this.form.reset("password"),
          onSuccess: () => window.location.reload(),
        });
    },
  },
  mounted() {
    document.title = "Login - Dinesurf";
  },
};
</script>
