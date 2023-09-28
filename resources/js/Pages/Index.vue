<template>
  <div class="flex md:flex-row flex-col">
    <div
      class="
        index-bg
        md:h-screen
        h-[14rem]
        w-full
        md:border-b-0
        border-b-8 border-[#9DC64E]
      "
    >
      <div
        class="index-overlay relative items-center flex flex-col content-center"
      >
        <div class="absolute bottom-0 md:p-20 p-7 pt-0 pb-[3.5rem]">
          <span class="font-Work md:text-3xl text-2xl font-semibold"
            >Search, discover and make reservations at the best restaurants in
            Africa, easier and faster with Dinesurf</span
          ><span>.</span>
        </div>

        <div class="md:hidden block bottom-0 left-0 absolute ml-5 pb-3">
          <JetIndexDinsurfIcon />
        </div>
      </div>
    </div>

    <div class="bg-red md:h-screen w-full">
      <div class="flex flex-col items-center w-full">
        <a :href="route('index')" class="mb-5 md:block mt-5 hidden">
          <img
            class="fill-current h-10 w-auto object-contain"
            src="/images/company_logo.jpg"
          />
        </a>

        <div class="mt-8 flex flex-col items-center w-full md:w-96 px-4">
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

          <div class="flex flex-row items-center text-center mt-5">
            <span>Or</span>
          </div>

          <form @submit.prevent="submit" class="px-5 w-full">
            <jet-validation-errors class="mb-4" />

            <div class="mt-6">
              <!-- <jet-label for="email" value="Email" /> -->
              <jet-input
                id="email"
                type="email"
                class="
                  mt-1
                  block
                  w-full
                  !border-gray-400
                  form-control
                  rounded-none
                "
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
                type="password"
                class="
                  mt-1
                  block
                  w-full
                  !border-gray-400
                  form-control
                  rounded-none
                "
                v-model="form.password"
                required
                autocomplete="password"
                placeholder="Password"
              />
            </div>

            <div class="mt-4 justify-between flex flex-row">
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

            <!-- <div
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
                    <Link
                      target="_blank"
                      :href="route('terms.show')"
                      class="
                        underline
                        text-sm text-gray-600
                        hover:text-gray-900
                      "
                      >Terms of Service</Link
                    >
                    and
                    <Link
                      target="_blank"
                      :href="route('policy.show')"
                      class="
                        underline
                        text-sm text-gray-600
                        hover:text-gray-900
                      "
                      >Privacy Policy</Link
                    >
                  </div>
                </div>
              </jet-label>
            </div> -->

            <div
              class="
                flex flex-col
                md:flex-row
                w-auto
                items-center
                justify-between
                mt-8
              "
            >
              <jet-button
                class="mb-4 searchBtnBg py-3 w-full md:w-[6rem]"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
              >
                Login
              </jet-button>

              <Link
                class="
                  mb-4
                  text-center
                  p-2
                  rounded-md
                  text-[grey]
                  font-semibold
                  btn btn-white-black
                  hover:bg-white
                  w-full
                  md:w-[12rem]
                "
                :href="route('dashboard')"
              >
                <span class="text-center text-[grey] font-semibold">
                  Continue as a Guest</span
                >
              </Link>
            </div>

            <div class="mt-5">
              Don't have an account ?
              <Link
                :href="route('register')"
                class="underline text-sm primaryTextColor hover:text-gray-900"
              >
                Sign-up here.
              </Link>
            </div>

            <div class="mt-5">
              <span class="primaryTextColor">Dinesurf for Restaurants?</span>

              <Link
                :href="route('vendors.login.index')"
                class="underline text-sm hover:text-gray-900"
              >
                Click Here
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard2.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import JetIndexDinsurfIcon from "@/Jetstream/IndexDinesurfIcon.vue";

export default {
  props: { appName: String },
  components: {
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
    JetIndexDinsurfIcon,
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
    document.title = "Dining Reservations Made Easy - Dinesurf";
  },
};
</script>
