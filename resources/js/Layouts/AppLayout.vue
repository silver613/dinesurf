<template>
  <div>
    <!-- <jet-banner /> -->
    <div  class="w-full bg-dinesurf-red-500  flex  justify-center items-center  p-3">
        <button  @click="handleClick" class="text-white  md:text-sm  text-md"> 🎉 New: Discover Great Entertainment and Local Attractions near you  &nbsp;<i class="fa fa-arrow-right"></i> </button>
    </div>

    <div class="min-h-screen bg-gray-100">
      <nav class="user-nav bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->

              <!-- Hamburger -->
              <div class="flex lg:hidden">
                <button
                  @click="
                    showingNavigationDropdown = !showingNavigationDropdown
                  "
                  class="
                    inline-flex
                    items-center
                    justify-center
                    pr-2
                    py-0
                    rounded-md
                    text-gray-400
                    hover:text-gray-500 hover:bg-gray-100
                    focus:outline-none focus:bg-gray-100 focus:text-gray-500
                    transition
                  "
                >
                  <svg
                    class="h-6 w-6"
                    stroke="black"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      :class="{
                        hidden: showingNavigationDropdown,
                        'inline-flex': !showingNavigationDropdown,
                      }"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                      :class="{
                        hidden: !showingNavigationDropdown,
                        'inline-flex': showingNavigationDropdown,
                      }"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </div>

              <div class="flex-shrink-0 flex items-center">
                <Link :href="route('dashboard')">
                  <jet-authentication-card-logo />
                </Link>
              </div>

              <div class="flex-shrink-0 flex items-center midLineContainer">
                <div class="midLine"></div>
              </div>

              <div class="flex-shrink-0 flex items-center">
                <!-- <global-LocationSearch
                  :city_id="city_id"
                  :state_id="state_id"
                  :query="query"
                  @setCityId="setCityId"
                  @setStateId="setStateId"
                /> -->
              </div>
            </div>

            <div class="hidden lg:flex lg:items-center sm:ml-6">
              <div
                class="flex items-center "
                v-if="
                  page != 'search/restaurant' &&
                  page != 'restaurant' &&
                  page != 'dashboard' &&
                  page != 'valentine'
                "
              >
                <button
                  class="
                    flex
                    py-2
                    px-5
                    bg-white
                    text-xs
                    font-medium
                    text-gray-700
                    border border-black
                    rounded
                    w-full
                  "
                  @click="openSearchModal = true"
                >
                  <JetSearchIcon class="text-slate-300 mr-2" />
                  Search restaurants
                </button>
              </div>

              <jet-modal
                v-if="
                  page != 'search/restaurant' &&
                  page != 'restaurant' &&
                  page != 'dashboard'
                "
                :show="openSearchModal"
                @close="openSearchModal = false"
              >
                <global-search-new @loadData="search()" />
              </jet-modal>

              <div
                class="relative hidden sm:-my-px sm:ml-10 sm:flex  "
              >
                <div
                  class="flex justify-center items-center text-center"
                >
                  <jet-dropdown-link
                    :as="'button'"
                    @click="checkAuth(route('restaurants.favourite'), true)"
                    :active="route().current('restaurants.favourite')"
                    class="
                      flex
                      justify-between
                      bg-white
                      text-sm
                      font-bold
                      text-gray-700
                      rounded
                    "
                  >
                    <i style="font-size: 16px" class="far fa-heart mr-1"></i>
                    <span class="text-black text-thin"> Dinelist Profile</span>
                  </jet-dropdown-link>
                </div>

                <div class="flex items-center">
                  <jet-dropdown-link
                    :as="'button'"
                    @click="checkAuth(route('reservations'), true)"
                    :active="route().current('reservations')"
                    class="
                      flex
                      justify-between
                      bg-white
                      text-sm
                      font-bold
                      text-gray-700
                      rounded
                    "
                  >
                    <i style="font-size: 16px" class="fa fa-list mr-1"></i>
                    <span class="text-black text-thin"> Reservations</span>
                  </jet-dropdown-link>
                </div>
              </div>

              <div
                class="
                  ml-3
                  relative
                  hidden
                  space-x-5
                  sm:-my-px sm:ml-10 sm:flex
                  user-nav
                "
                v-if="!$page.props.user"
              >
                <button
                  @click="checkAuth()"
                  class="
                    justify-between
                    px-4
                    py-2
                    text-xs
                    font-medium
                    border
                    rounded-full
                    bg-dinesurf-green-400
                    ml-4
                    h-9
                    jump
                    text-white
                  "
                >
                  <span class="font-bold">Sign in</span>
                </button>

                <button
                  @click="checkAuth(null, false, false)"
                  class="
                    justify-between
                    px-4
                    py-2
                    bg-white
                    text-xs
                    font-medium
                    text-dinesurf-green-400
                    border border-dinesurf-green-400
                    rounded-full
                    jump
                    h-9
                  "
                >
                  <span class="font-bold">Sign up</span>
                </button>
              </div>
              <!-- Settings Dropdown -->
              <div class="ml-3 relative">
                <jet-dropdown align="right" width="48">
                   <!-- $page.props.jetstream.managesProfilePhotos -->
                  <template #trigger>
                   
                    <button
                      v-if="$page.props.user?.image_url"
                      
                      class="
                        flex
                        text-sm
                        border-2 border-transparent
                        rounded-full
                        focus:outline-none focus:border-gray-300
                        transition
                      "
                    >
                      <img
                        class="h-8 w-8 rounded-full bg-gray-300 object-cover"
                        v-if="$page.props.user"
                        :src="$page.props.user.image_url"
                        :alt="$page.props.user.first_name"
                      />
                    </button>

                    <span v-else class="inline-flex rounded-md">
                      <button
                        type="button"
                        style="
                          width: 44px;
                          padding: 6px;
                          border-radius: 50%;
                        "
                      >
                        <UserIcon style="width: 30px; height: 25px" />
                      </button>
                    </span>
                  </template>

                  <template #content @click="showingNavigationDropdown = false">
                    <!-- Account Management -->

                    <div class="text-left pl-4 p-2" v-if="$page.props.user">
                      <span class="font-bold"
                        >Hello {{ $page.props.user.first_name }}!</span
                      >
                    </div>
                    <div class="border-t border-gray-100"></div>

                    <jet-dropdown-link :href="route('dashboard')">
                      Home
                    </jet-dropdown-link>
                    <jet-dropdown-link
                      :as="'button'"
                      @click="checkAuth(route('profile.show'), true)"
                    >
                      Settings
                    </jet-dropdown-link>

                    <!-- <jet-dropdown-link
                                            :href="
                                                route('restaurants.favourite')
                                            "
                                        >
                                            Favourites
                                        </jet-dropdown-link> -->

                    <div class="border-t border-gray-100"></div>

                    <!-- Authentication -->

                    <form @submit.prevent="logout" v-if="$page.props.user">
                      <jet-dropdown-link as="button">
                        Log Out
                      </jet-dropdown-link>
                    </form>
                  </template>
                </jet-dropdown>
              </div>
            </div>

            <!-- Search Icon Here -->

            <div
              class="lg:hidden   flex"
              v-if="
                page != 'search/restaurant' &&
                page != 'restaurant' &&
                page != 'dashboard'
              "
            >
              <button
                class="
                  flex
                  mt-4
                  ml-12
                  w-12
                  text-center
                  items-center
                  px-4
                  py-2
                  bg-white
                  text-sm
                  font-medium
                  text-gray-700
                "
                @click="openSearchModal = true"
              >
                <JetSearchIcon class="text-slate-300" width="90" />
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
          :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }"
          class="lg:hidden"
          @click="showingNavigationDropdown = false"
        >
          <!-- Responsive Settings Options -->
          <div
            class="pt-4 pb-1 border-t border-gray-200"
            v-if="!$page.props.user"
          >
            <jet-responsive-nav-link
              :as="'button'"
              @click="checkAuth()"
              :active="route().current('login')"
            >
              <span class="mr-2">Login</span>
              <i class="fas fa-sign-in-alt"></i>
            </jet-responsive-nav-link>
            <jet-responsive-nav-link
              :as="'button'"
              @click="checkAuth(null, false, false)"
              :active="route().current('register')"
            >
              <span class="mr-2">Register</span>
              <i class="fas fa-user"></i>
            </jet-responsive-nav-link>
          </div>

          <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4" v-if="$page.props.user">
              <div class="flex-shrink-0 mr-3">
                <img
                  class="h-10 w-10 bg-gray-300 rounded-full object-cover"
                  :src="$page.props.user.profile_photo_url"
                  :alt="$page.props.user.first_name"
                />
              </div>

              <div>
                <div class="font-medium text-base text-gray-800">
                  {{ $page.props.user.first_name }}
                </div>
              </div>
            </div>

            <div class="mt-3 space-y-1">
              <jet-responsive-nav-link
                :href="route('dashboard')"
                :active="route().current('dashboard')"
              >
                Home
              </jet-responsive-nav-link>
              <jet-responsive-nav-link
                :as="'button'"
                @click="checkAuth(route('profile.show'), true)"
                :active="route().current('profile.show')"
              >
                Settings
              </jet-responsive-nav-link>

              <jet-responsive-nav-link
                :as="'button'"
                @click="checkAuth(route('restaurants.favourite'), true)"
                :active="route().current('restaurants.favourite')"
              >
                Dinelist Profile
              </jet-responsive-nav-link>

              <jet-responsive-nav-link
                :as="'button'"
                @click="checkAuth(route('reservations'), true)"
                :active="route().current('reservations')"
              >
                Reservations
              </jet-responsive-nav-link>

              <jet-responsive-nav-link
                :href="route('api-tokens.index')"
                :active="route().current('api-tokens.index')"
                v-if="$page.props.jetstream.hasApiFeatures"
              >
                API Tokens
              </jet-responsive-nav-link>

              <!-- Authentication -->
              <form
                method="POST"
                @submit.prevent="logout"
                v-if="$page.props.user"
              >
                <jet-responsive-nav-link as="button">
                  Log Out
                </jet-responsive-nav-link>
              </form>
            </div>
          </div>
        </div>
      </nav>



    <!-- banner modal --> 
      <card-modal
        :showing="openBannerModal"
        @close="openBannerModal = false"
        :key="'BannerModal'"
      >

      <div class="flex flex-col space-y-3">
          <span class="text-3xl font-bold capitalize"> coming soon!. </span>

          <button @click="openBannerModal = false" class="bg-dinesurf-green-500 py-2  px-3 font-bold text-white">Okay</button>
      </div>
       
      </card-modal>


      <card-modal
        :showing="openRegisterModal"
        @close="openRegisterModal = false"
        :key="'cardModal1'"
      >
        <login-card
          :key="'navRegister'"
          @close="openLoginModal = false"
          :status="status"
          :canResetPassword="true"
          :openRegister="true"
          :intendedUrl="intededUrl"
        ></login-card>
      </card-modal>

      <!-- Login -->
      <card-modal
        :showing="openLoginModal"
        @close="openLoginModal = false"
        :key="'cardModal1'"
      >
        <login-card
          :key="'navLogin'"
          @close="openLoginModal = false"
          :status="status"
          :canResetPassword="true"
          :intendedUrl="intededUrl"
        ></login-card>
      </card-modal>
      <!-- Page Content -->
      <main
        class="overflow-x-hidden md:overflow-x-auto bg-white"
        style="background-color: #fff !important"
      >
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script>
import JetApplicationMark from "@/Jetstream/ApplicationMark.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo2.vue";
import JetBanner from "@/Jetstream/Banner.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink.vue";
import JetLocationMarkerLogo from "@/Jetstream/LocationMarkerLogo.vue";
import JetSearchIcon from "@/Jetstream/SearchIcon.vue";
import Register from "@/components/Register.vue";
import LoginCard from "@/components/LoginCard.vue";

import { ChevronDownIcon } from "@heroicons/vue/solid";
import { ExclamationIcon } from "@heroicons/vue/outline";
import { UserIcon } from "@heroicons/vue/outline";

export default {
  components: {
    JetApplicationMark,
    JetBanner,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
    JetAuthenticationCardLogo,
    JetLocationMarkerLogo,
    JetSearchIcon,
    UserIcon,
    ChevronDownIcon,
    ExclamationIcon,
    Register,
    LoginCard,
  },

  props: [
    "query",
    "page",
    "title",
    "cuisine",
    "party_size",
    "date",
    "time",
    "lat",
    "lng",
    "defaultState",
    "state_id",
    "city_id",
    "page",
    "status",
  ],

  data() {
    return {
      showingNavigationDropdown: false,
      openSearchModal: false,
      openRegisterModal: false,
      openLoginModal: false,
      intededUrl: this.$page.props.page,
      openBannerModal: false
    };
  },

  methods: {

    handleClick(){
      this.$inertia.post(
        route("metrics.post"),
        {
          onFinish: (result) =>  console.log("result::L", result),
        }
      );
      this.openBannerModal = true
    },
    checkAuth(url = null, goToUrl = false, login = true) {
      if (!this.$page.props.user) {
        this.intededUrl = url ? url : this.intededUrl;
        if (!login) {
          this.openRegisterModal = true;
          return;
        }
        this.openLoginModal = true;
        this.logCurrentUrl(url ? url : this.intededUrl);
        return;
      } else {
        if (url) {
          return goToUrl ? this.$inertia.get(url) : false;
        }
        return;
      }
      return;
    },
    handleSearchModalToggle() {
      if (this.openSearchModal) {
        this.openSearchModal = false;
      } else if (!openSearchModal) {
        this.openSearchModal = true;
      }
    },
    switchToTeam(team) {
      this.$inertia.put(
        route("current-team.update"),
        {
          team_id: team.id,
        },
        {
          preserveState: false,
        }
      );
    },

    logout() {
      this.$inertia.post(
        route("logout"),
        {
          intendedUrl: this.$page.props.page,
          _token: this.$page.props.csrf_token,
        },
        {
          onFinish: () => this.$inertia.reload(),
        }
      );
    },
    setStateId(data) {
      console.log("layout state id:", data);
      this.$emit("setStateId", data);
    },
    setCityId(data) {
      console.log("layout city id:", data);
      this.$emit("setCityId", data);
    },
    search() {
      this.openSearchModal = false;
      this.$inertia.visit(window.location);
    },
  },
  mounted() {
    this.openSearchModal = false;
    this.showingNavigationDropdown = false;
  },
};
</script>
