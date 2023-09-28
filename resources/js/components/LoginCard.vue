<template>
  <div>
    <div v-if="!$page.props.user"  class="h-[35rem]  overflow-y-scroll">
      <!-- <h3 class="text-lg font-medium text-gray-900 capitalize mb-4 text-center">
        Please Login or signup to Continue
      </h3> -->

      <jet-validation-errors class="mb-4" />

      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>

      <div class="mt-3 flex justify-center">
        <a
          class="btn btn-md welcome-btn btn-white-black welcome-btn2 w-full  border"
          :href="
            route('google-redirect', {
              intendedUrl: intendedUrl,
            })
          "
        >
          <!-- <i class="fab fa-google"></i> -->
          <img src="/images/google-favicon.png" class="h-6 w-6 mr-3" />
          <span> Continue with Google</span>
        </a>
      </div>

      <div class="flex items-center justify-center">
          <span class="font-bold">Or</span> 
      </div>

      <form @submit.prevent="register()" v-if="registerForm" class="text-left">
        <div class=" py-3">

          <div class="flex gap-3">
              <div>
                <jet-label for="first_name" value="First Name" class="font-bold" />
                <jet-input
                  id="first_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.first_name"
                  required
                  autofocus
                  autocomplete="first_name"
                />
              </div>
              <div class="">
                <jet-label for="last_name" value="Last Name" class="font-bold" />
                <jet-input
                  id="last_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.last_name"
                  required
                  autofocus
                  autocomplete="last_name"
                />
              </div>
          </div>

          <div class="mt-4">
            <jet-label for="email" value="Email" class="font-bold" />
            <jet-input
              id="email"
              type="email"
              class="mt-1 block w-full"
              v-model="form.email"
              required
            />
          </div>
        
          <div class="mt-4 relative  w-full">
            <jet-label for="phone_number" value="Enter Phone Number" class="font-bold" />
            <!-- <jet-input
              id="phone_number"
              type="text"
              class="mt-1 block w-full"
              v-model="form.phone_number"
              required
            /> -->

            <phone-input
                        class="w-full phone-input"
                        v-model="form.phone_number"
                        :modelName="'phone_number'"
                        @setValue="setPhone"
                        required
                ></phone-input>
          </div>
          <!-- <div class="mt-4">
            <jet-label for="birthday" value="Enter Birthday" />
            <input
              class="form-control w-full"
              id="birthday"
              type="text"
              v-model="form.birthday"
              required
              autocomplete="birthday"
              placeholder="dd / mm"
            />
          </div> -->

        

          <div class="flex gap-3">
                <div class="mt-4">
                  <jet-label for="password" value="Password" class="font-bold" />
                  <jet-input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="password"
                  />
                </div>

                <div class="mt-4">
                  <jet-label for="password_confirmation" value="Confirm Password" class="font-bold" />
                  <jet-input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                  />
                </div>
          </div>
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

        <div class="flex flex-col items-center justify-center mt-5">
          <button class="mb-4 py-3 px-10 font-bold rounded-full bg-dinesurf-green-400 text-white"
             :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Create Account
          </button>

          <sp
            @click="registerForm = !registerForm"
            type="submit"
            class="underline text-sm text-gray-600 cursor-pointer hover:text-gray-900"
          >
            Already registered?
          </sp>
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
      <form v-else @submit.prevent="submit" class="text-left">
        <div>
          <jet-label class="text-left font-bold" for="email" value="Email"  />
          <jet-input
            id="email"
            name="email"
            autocomplete="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autofocus
          />
        </div>

        <div class="mt-4">
          <jet-label for="password" value="Password" class="font-bold" />
          <jet-input
            id="password"
            name="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="password"
          />
        </div>

        <div class="block mt-4">
          <label class="flex items-center">
            <jet-checkbox name="remember" v-model:checked="form.remember" />
            <span class="ml-2 text-sm text-gray-600">Remember me</span>
          </label>
        </div>

        <div class="flex flex-col justify-center items-center mt-4">
          <!-- <jet-button
            class="mb-4 btn-md"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Log in
          </jet-button> -->
            <button class="mb-4 py-3 px-10 font-bold rounded-full bg-dinesurf-green-400 text-white"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing">
               Log in
            </button>

          <div class="flex justify-around w-full">
            <Link
              v-if="canResetPassword"
              :href="route('password.request')"
              class="underline text-sm text-gray-600 hover:text-gray-900"
            >
              Forgot your password?
            </Link>
            <span
              @click="registerForm = !registerForm"
              class="
                cursor-pointer
                underline
                text-sm text-gray-600
                hover:text-gray-900
              "
            >
              Don't have an Account?
            </span>
          </div>
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
    </div>
    <div v-else class="flex flex-col justify-center items-center my-10">
      <img src="/images/success-icon.svg" class="h-16 md:h-32" />
      <h4 class="text-center my-3" v-if="registerForm">
        Registration successful
      </h4>
      <h4 class="text-center my-3" v-else>Login successful</h4>
      <button
        type="button"
        class="mx-2 btn-md btn-auth btn-red text-center"
        @click="close()"
      >
        Ok
      </button>
    </div>
  </div>
</template>

<script>
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default {
  components: {
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
    intendedUrl: String,
    openRegister: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      registerForm: this.openRegister,
      form: this.$inertia.form({
        email: "",
        password: "",
        remember: false,
        first_name: "",
        last_name: "",
        phone_number: "",
        birthday: "",
        email: "",
        password_confirmation: "",
        source: null,
        campaign_id: null,
        terms: false
      }),
    };
  },

  methods: {
    submit() {
      this.form
        .transform((data) => ({
          ...data,
          remember: this.form.remember ? "on" : "",
          intendedUrl: this.intendedUrl,
          _token: this.$page.props.csrf_token,
        }))
        .post(this.route("login"), {
          onFinish: () => this.form.reset("password"),
        });
    },
    register() {
      this.form.source = this.$page.props.tracking.source;
      this.form.campaign_id = this.$page.props.tracking.campaign_id;
      this.form
        .transform((data) => ({
          ...data,
          intendedUrl: this.intendedUrl,
        }))
        .post(this.route("register"), {
          onFinish: () => this.form.reset("password"),
          onSuccess: () => window.location.reload(),
        });
    },
    close() {
      this.$emit("close");
    },
  },
};
</script>
