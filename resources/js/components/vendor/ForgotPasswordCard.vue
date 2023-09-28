<template>
  <div class="auth-card">
    <div class="auth-card-header flex flex-col items-center">
      <a :href="route('index')">
        <img
          class="fill-current block md:hidden h-7 w-auto object-contain mb-5"
          src="/images/company_logo.png"
        />
      </a>
      <h3>Forgot Password</h3>
      <p>Please enter your email address.</p>
      <jet-validation-errors class="mb-4" />

      <div v-if="status" class="mt-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
    </div>
    <div class="auth-card-body text-center">
      <form @submit.prevent="submit" class="flex flex-col w-full">
        <input
          id="email"
          type="email"
          v-model="form.email"
          class="auth-card-input"
          placeholder="Email address"
          required
          autofocus
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
            text-sm text-white
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
            btn-md btn-auth btn-red btn-reset
          "
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Email Password Reset Link
        </button>
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
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        email: "",
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("vendors.forgot-password.reset"));
    },
  },
};
</script>
