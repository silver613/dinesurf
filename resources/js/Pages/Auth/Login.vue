<template>
  <jet-authentication-card>
    <jet-validation-errors class="mb-4" />

    <div class="auth-card-header flex flex-col items-center">
      <a :href="route('dashboard')">
        <img
          class="fill-current block h-10 w-full object-contain mb-5"
          src="/images/company_logo.png"
        />
      </a>
      <jet-validation-errors class="mb-4" />
    
    </div>
    <div class="px-10 mb-3">
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

    <div class="text-center font-bold">Or</div>
    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    
    <form @submit.prevent="submit" class="px-10 ">
      <div class="mt-6">
        <!-- <jet-label for="email" value="Email" /> -->
        <jet-input
          id="email"
          name="email"
          type="email"
          class="mt-1 block w-full "
          v-model="form.email"
          required
          autocomplete="email"
          placeholder="Email"
        />
      </div>

      <div class="mt-6">
        <!-- <jet-label for="password" value="Password" /> -->
        <jet-input
          id="password"
          name="password"
          type="password"
          class="mt-1 block w-full "
          v-model="form.password"
          required
          autocomplete="password"
          placeholder="Password"
        />
      </div>

      <div class=" mt-4 justify-between flex flex-row">
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

      <div class="flex flex-col w-auto items-center justify-center mt-8">
        <jet-button
          class="mb-4 searchBtnBg w-full"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Login
        </jet-button>
      </div>
<!-- 
      <div class="text-center font-bold">Or</div>

      <a
        :href="
          route('google-redirect', {
            intendedUrl: $page.props.intendedUrl,
          })
        "
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
      </a> -->

      <div class="mt-5">
        New to Dinesurf ?
        <Link
          :href="route('register')"
          class="underline text-sm primaryTextColor hover:text-gray-900"
        >
          Sign Up.
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
          intendedUrl: this.$page.props.intendedUrl,
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