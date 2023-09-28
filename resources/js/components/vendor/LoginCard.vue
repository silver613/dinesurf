<template>
  <div class="auth-card">
    <div class="auth-card-header flex flex-col items-center">
      <a :href="route('index')">
        <img
          class="fill-current block md:hidden h-7 w-auto object-contain mb-5"
          src="/images/company_logo.png"
        />
      </a>
      
      <h3>Restaurant Login</h3>
      <p>Please enter your details to proceed</p>
      <jet-validation-errors class="mb-4" />
      <div v-if="status" class="mt-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
    </div>
    <div class="auth-card-body text-center">
      <form @submit.prevent="submit" class="flex flex-col w-full">
        <input
          type="email"
          v-model="form.email"
          class="auth-card-input"
          placeholder="Email address"
          required
          autofocus
        />
        <input
          type="password"
          class="auth-card-input"
          placeholder="Password"
          v-model="form.password"
          required
          autocomplete="password"
        />
        <button
          type="submit"
          class="
            inline-flex
            items-center
            justify-center
            px-4
            py-2
            bg-red-800
            border border-transparent
            rounded-md
            font-semibold
            text-xs text-white
            uppercase
            tracking-widest
            hover:bg-red-700
            active:bg-red-900
            focus:outline-none
            focus:border-red-900
            focus:ring
            focus:ring-red-300
            disabled:opacity-25
            transition
            mb-6
            btn-md btn-auth btn-red
          "
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Log In
          <i class="fas fa-spinner fa-spin ml-1" v-if="form.processing"></i>
        </button>

        <a
        class="btn btn-md btn-white-black welcome-btn2 p-0 w-80"
        :href="route('google-redirect')"
      >
        <div class="flex flex-row justify-center items-center">
          <img src="/images/google-favicon.png" class="h-6 w-6 mr-3" />
          <span class="text-center mt-1 text-[grey] font-semibold"
            >Continue with Google</span
          >
        </div>
      </a> 
        <p class="my-5">
          <Link :href="route('password.request')" class="dinesurf-green">
            Forgot your password?
          </Link>
          <!-- <a href="" class="dinesurf-green">Forgot password?</a> -->
        </p>
        <p>
          Not yet registered on Dinesurf?
          <Link :href="'/vendors/register'" class="dinesurf-green">
            Register here
          </Link>
        </p>
      </form>
    </div>
  </div>
</template>

<script>
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
      this.form.post(route("login"), {
        onFinish: () => this.form.reset("password"),
        onSuccess: () => window.location.reload(),
      });
    },
  },
};
</script>
