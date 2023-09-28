<template>
  <div>
    <jet-banner />

    <div class="bg-gray-100 flex overflow-hidden">
      <transition
        enter-active-class="transition-all duration-750 ease-out"
        leave-active-class="transition-all duration-750 ease-in"
        enter-class="opacity-0 scale-75"
        enter-to-class="opacity-100 scale-100"
        leave-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-75"
      >
        <div
          id="sidebar"
          :class="showingSidebar ? 'flex' : 'hidden'"
          class="
            md:block
            z-1024
            h-screen
            md:h-auto
            fixed
            md:relative
            w-screen
            md:w-auto
          "
        >
          <nav
            class="
              border-b border-0
              w-full
              cbg-red
              !bg-dinesurf-red-0
              z-[1030]
              h-screen
            "
          >
            <!-- Primary Navigation Menu -->
            <div class="md:mx-auto">
              <div class="flex flex-col justify-between">
                <div class="flex flex-col">
                  <!-- Logo -->
                  <div
                    class="
                      flex-shrink-0 flex
                      justify-between
                      w-full
                      sm:pl-4
                      lg:pl-8
                      pl-6
                      pr-5
                      h-[6rem]
                    "
                  >
                    <Link :href="route('vendors.dashboard')">
                      <jet-app-logo-white class="md:w-[8rem]" />
                    </Link>
                  </div>

                  <div
                    class="
                      w-full
                      flex
                      justify-center
                      items-center
                      content-center
                      border-y-[0.2px] border-[#EFEFEFCC]
                      p-4
                      pt-0
                      pb-3
                      mb-5
                    "
                    v-if="$page.props.auth.vendor"
                  >
                    <div class="-ml-2">
                      <img
                        class="
                          mt-3
                          md:h-[2.5rem] md:w-[2.5rem]
                          h-[2.2rem]
                          w-[2.2rem]
                          rounded-full
                          object-cover
                          mr-2
                        "
                        :src="$page.props.auth.vendor.profile_photo_url"
                        :alt="$page.props.auth.vendor.company_name"
                      />
                    </div>
                    <div class="flex flex-col">
                      <span class="text-xl">

                        {{
                        $page.props.auth.vendor.company_name.length > 10
                            ? $page.props.auth.vendor.company_name.substring(0, 10) + "..."
                            :$page.props.auth.vendor.company_name
                        }}
                        </span
                      >
                      <a
                        :href="route('vendors.profile.show')"
                        style="
                         margin: 0px !important;

                          font-size: 10px;
                          color: #efefefcc;
                          text-decoration: underline;
                        "
                        class="h-2 m-0"
                      >
                        <span class="text-[12px]"> Edit Profile </span>
                      </a>
                    </div>
                  </div>

                  <!-- Navigation Links -->
                  <div
                    class="
                      space-x-8
                      sm:-my-px
                      flex flex-col
                      nav-links
                      vendor-nav
                      md:h-[30rem]
                      overflowHidden 
                      pb-20
                      md:pt-15
                      pr-3
                    "
                  >
                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.dashboard')"
                      :active="route().current('vendors.dashboard')"
                      :id="'navLinkDashboard'"
                      class="bg-blue"
                    >
                      <jet-dashboard-icon
                        width="15px"
                        height="15px"
                        :fill="
                          route().current('vendors.dashboard')
                            ? '#B72F2F'
                            : 'white'
                        "
                        class="mr-2 mt-1"
                      />
                      <span v-if="expandingSidebar">Dashboard</span>
                    </jet-nav-link>


                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.events')"
                      :active="route().current('vendors.events')"
                      :id="'navLinkEvents'"
                    >
                     <i  class="far fa-calendar mr-2 text-center mt-1"></i>
                      <span v-if="expandingSidebar">Events</span>
                      <span
                        style="font-size: 6px"
                        class="
                          text-xs
                          p-1
                          pt-0
                          w-7
                          text-center
                          items-center
                          h-4
                          rounded
                          ml-5
                          bg-white
                          text-dinesurf-red-400
                        "
                        >New</span
                      >
                    </jet-nav-link>

                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.tutorial')"
                      :active="route().current('vendors.tutorial')"
                      :id="'navLinkTutorial'"
                    >
                    
                      <jet-tutorial-icon
                        width="15px"
                        height="15px"
                        :fill="
                          route().current('vendors.tutorial')
                            ? '#B72F2F'
                            : 'white'
                        "
                        class="mr-2 mt-1"
                      />
                      <span v-if="expandingSidebar">Tutorial</span>
                    </jet-nav-link>

                   

                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.reservations')"
                      :active="route().current('vendors.reservations')"
                      :id="'navLinkReservations'"
                    >
                      <jet-reservation-icon
                        width="15px"
                        height="15px"
                        :fill="
                          route().current('vendors.reservations')
                            ? '#B72F2F'
                            : 'white'
                        "
                        class="mr-2 mt-1"
                      />
                      <span v-if="expandingSidebar">Reservations</span>
                    </jet-nav-link>



                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.guests')"
                      :active="route().current('vendors.guests')"
                      :id="'navLinkGuests'"
                    >
                      <jet-guestbook-icon
                        width="15px"
                        height="15px"
                        :fill="
                          route().current('vendors.guests')
                            ? '#B72F2F'
                            : 'white'
                        "
                        class="mr-2 mt-1"
                      />
                      <span v-if="expandingSidebar">Guests Book</span>
                    </jet-nav-link>

                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.menus')"
                      :active="this.isMenuRoute"
                      :id="'navLinkMenuSection'"
                    >
                      <jet-menu-icon
                        width="15px"
                        height="15px"
                        :fill="this.isMenuRoute ? '#B72F2F' : 'white'"
                        class="mr-2 mt-1"
                      />
                      <span v-if="expandingSidebar">Menu</span>
                      <span
                        style="font-size: 6px"
                        class="
                          text-xs
                          p-1
                          pt-0
                          w-7
                          text-center
                          items-center
                          h-4
                          rounded
                          ml-5
                          bg-white
                          text-dinesurf-red-400
                        "
                        >New</span
                      >
                    </jet-nav-link>

                   
                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.reviews')"
                      :active="route().current('vendors.reviews')"
                      :id="'navLinkReviews'"
                    >
                      <jet-review-icon
                        width="15px"
                        height="15px"
                        :fill="
                          route().current('vendors.reviews')
                            ? '#B72F2F'
                            : 'white'
                        "
                        class="mr-2 mt-1"
                      />

                      <span v-if="expandingSidebar">Reviews</span>
                    </jet-nav-link>
                   
                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.billing')"
                      :active="route().current('vendors.billing')"
                      :id="'navLinkBilling'"
                    >
                      <jet-billing-icon
                        width="15px"
                        height="15px"
                        :fill="
                          route().current('vendors.billing')
                            ? '#B72F2F'
                            : 'white'
                        "
                        class="mr-2 mt-1"
                      />

                      <span v-if="expandingSidebar">Billing</span>
                    </jet-nav-link>

                    <jet-nav-link
                      :vendorLink="true"
                      v-if="$page.props.user?.current_team"
                      :href="route('teams.show', $page.props.user.current_team)"
                      :active="route().current('teams.show')"
                      :id="'navLinkAdmin'"
                    >
                      <jet-team-icon
                        width="15px"
                        height="15px"
                        :fill="'white'"
                        class="mr-2 mt-1"
                      />

                      <span v-if="expandingSidebar">Team/Employees</span>
                    </jet-nav-link>


                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.management.order')"
                      :active="route().current('vendors.management.order')"
                      :id="'navLinkOrderManagement'"
                    >
                      <jet-order-icon
                        width="15px"
                        height="15px"
                        :fill="route().current('vendors.management.order') ? '#B72F2F' : 'white'"
                        class="mr-2 mt-1"
                      />
                      <span v-if="expandingSidebar">Order</span>
                      <span
                        style="font-size: 6px"
                        class="
                          text-xs
                          p-1
                          pt-0
                          w-7
                          text-center
                          items-center
                          h-4
                          rounded
                          ml-5
                          bg-white
                          text-dinesurf-red-400
                        "
                        >New</span
                      >
                    </jet-nav-link>


                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.transactions')"
                      :active="route().current('vendors.transactions')"
                      :id="'navLinkTransactions'"
                    >
                      <jet-transaction-icon
                        width="15px"
                        height="15px"
                        :fill="
                          route().current('vendors.transactions')
                            ? '#B72F2F'
                            : 'white'
                        "
                        class="mr-2 mt-1"
                      />
                      <span v-if="expandingSidebar">Transactions</span>
                    </jet-nav-link>

                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.profile.show')"
                      :active="route().current('vendors.profile.show')"
                      :id="'navLinkSettings'"
                    >
                      <jet-setting-icon
                        width="15px"
                        height="15px"
                        :fill="
                          route().current('vendors.profile.show')
                            ? '#B72F2F'
                            : 'white'
                        "
                        class="mr-2 mt-1"
                      />
                      <span v-if="expandingSidebar">Settings</span>
                    </jet-nav-link>

                    <!-- </jet-nav-link> -->

                    <jet-nav-link
                      :vendorLink="true"
                      :href="route('vendors.logout')"
                      :id="'navLinkLogout'"
                    >
                      <i
                        class="fas fa-power-off"
                        :class="{ 'mr-2': expandingSidebar }"
                      ></i>
                      <span v-if="expandingSidebar">Log Out</span>
                    </jet-nav-link>
                  </div>
                </div>
              </div>
            </div>
          </nav>
          <div
            class="dark-overlay md:hidden flex-grow h-screen z-1024"
            @click="showingSidebar = !showingSidebar"
          ></div>
        </div>
      </transition>
      <section class="flex-grow" :style="sidebarStyle">
        <!-- Page Heading -->
        <header class="bg-white shadow" v-if="$slots.header">
          <div class="max-w-7xl mx-auto py-2 md:py-6 px-4 sm:px-6 lg:px-8">
            <div
              class="flex w-full justify-between p-2 border-2 border-[#F1F1F1]"
            >
              <div class="flex items-center justify-start w-3/5 md:w-auto">
                <div class="flex md:hidden items-center mr-3">
                  <i
                    class="fa-solid fa-bars w-3 text-xl mr-3 cursor-pointer"
                    @click="showingSidebar = !showingSidebar"
                  ></i>
                  <img
                    src="/images/company_logo.jpg"
                    alt=""
                    class="h-3 md:h-5 w-auto"
                  />
                </div>
                <div style="font-size: 10px" class="md:block hidden">
                  <slot name="header" v-if="vendor"></slot>
                </div>
              </div>
              <div class="flex items-center">
                <jet-dropdown align="right" class="w-auto">
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                        type="button"
                        class="
                          inline-flex
                          items-center
                          sm:px-3
                          border border-transparent
                          text-xs
                          md:text-sm
                          leading-4
                          font-medium
                          rounded-md
                          text-gray-500
                          bg-white
                          hover:text-gray-700
                          focus:outline-none
                          transition
                        "
                      >
                        <div
                          v-if="$page.props.auth.vendor"
                          class="flex items-center"
                        >
                          <img
                            v-if="$page.props.auth.vendor.profile_photo_path"
                            class="
                              h-9
                              w-[2.25rem]
                              rounded-full
                              object-cover
                              mr-2
                            "
                            :src="$page.props.auth.vendor.profile_photo_url"
                            :alt="$page.props.auth.vendor.company_name"
                          />
                          <div class="flex flex-col">
                            <span style="text-align: left; font-size: 12px">
                            
                           
                            {{
                        $page.props.auth.vendor.company_name.length > 15
                            ? $page.props.auth.vendor.company_name.substring(0, 15) + "..."
                            :$page.props.auth.vendor.company_name
                        }}
                            
                          </span>
                            <span
                              style="
                                 font-size: 10px;
                                color: #00000080;
                                text-transform: capitalize;
                              "
                              >{{
                                $page.props.auth.vendor.first_name +
                                " " +
                                $page.props.auth.vendor.last_name
                              }}</span
                            >
                          </div>
                        </div>
                        <div v-else class="flex items-center">Account</div>

                        <svg
                          class="ml-2 -mr-0.5 h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                      Manage Account
                    </div>

                    <jet-dropdown-link :href="route('vendors.switch-vendor')">
                      Switch Accounts
                    </jet-dropdown-link>

                    <jet-dropdown-link :href="route('vendors.profile.show')">
                      Profile
                    </jet-dropdown-link>

                    <div class="border-t border-gray-100"></div>
                    <jet-dropdown-link as="a" :href="route('vendors.logout')">
                      Log Out
                    </jet-dropdown-link>
                  </template>
                </jet-dropdown>
              </div>
            </div>
          </div>
        </header>

        <!-- menu notes -->
        <!-- <menu-popup  :show="this.menuBoxShow"   @close=" this.menuBoxShow = !this.menuBoxShow" /> -->

        <!-- Page Content -->
        <main
          class="w-screen bg-white h-screen md:w-auto relative"
          @click="showingSidebar = false"
        >
          <div
            class="bg-white md:pl-8 mb-5 pl-2 flex justify-between"
            v-if="!route().current('vendors.dashboard')"
          >
            <back-button />
            <slot name="rightContent"></slot>
          </div>

          <div v-if="!vendor" class="overflow-y-scroll h-screen pb-96">
            <SwitchAccounts />
          </div>
          <div v-else class="overflowHidden 
                      overflow-x-scroll h-screen pb-96">
            <slot />
          </div>

       
        </main>
      </section>
    </div>
  </div>
</template>

<script>
import JetApplicationMark from "@/Jetstream/ApplicationMark.vue";
import JetBanner from "@/Jetstream/Banner.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink.vue";
// icons
import JetAppLogoWhite from "@/Jetstream/Vendor_icons/LogoWhite.vue";
import JetDashboardIcon from "@/Jetstream/Vendor_icons/DashboardIcon.vue";
import JetReservationIcon from "@/Jetstream/Vendor_icons/ReservationIcon.vue";
import JetTutorialIcon from "@/Jetstream/Vendor_icons/TutorialIcon.vue";
import JetGuestbookIcon from "@/Jetstream/Vendor_icons/GuestbookIcon.vue";
import JetMenuIcon from "@/Jetstream/Vendor_icons/MenuIcon.vue";
import JetBillingIcon from "@/Jetstream/Vendor_icons/BillingIcon.vue";
import JetAnalyticIcon from "@/Jetstream/Vendor_icons/AnalyticsIcon.vue";
import JetReviewIcon from "@/Jetstream/Vendor_icons/ReviewIcon.vue";
import JetDTableIcon from "@/Jetstream/Vendor_icons/TutorialIcon.vue";
import JetWaitlistIcon from "@/Jetstream/Vendor_icons/WaitlistIcon.vue";
import JetCustomerIcon from "@/Jetstream/Vendor_icons/CustomerIcon.vue";
import JetGalleryIcon from "@/Jetstream/Vendor_icons/GalleryIcon.vue";
import JetTeamIcon from "@/Jetstream/Vendor_icons/TeamIcon.vue";
import JetSettingIcon from "@/Jetstream/Vendor_icons/SettingIcon.vue";
import SwitchAccounts from "@/components/vendor/SwitchAccounts.vue";
import JetTransactionIcon from "@/Jetstream/Vendor_icons/TransactionIcon.vue";
import JetOrderIcon from "@/Jetstream/Vendor_icons/OrderIcon.vue";
export default {
  components: {
    JetApplicationMark,
    JetBanner,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
    JetAppLogoWhite,
    JetDashboardIcon,
    JetReservationIcon,
    JetTutorialIcon,
    JetGuestbookIcon,
    JetMenuIcon,
    JetBillingIcon,
    JetGalleryIcon,
    JetAnalyticIcon,
    JetReviewIcon,
    JetDTableIcon,
    JetWaitlistIcon,
    JetCustomerIcon,
    JetTeamIcon,
    JetSettingIcon,
    SwitchAccounts,
    JetTransactionIcon,
    JetOrderIcon
  },

  data() {
    return {
      menuBoxShow: false,
      showingNavigationDropdown: false,
      showingSidebar: false,
      expandingSidebar: true,
      sidebarWidth: 0,
      sidebarStyle: { "max-width": this.contentWidth },
      openMenu: false,
      isMenuRoute:
        route().current("vendors.menus") ||
        route().current("vendors.menu-categories") ||
        route().current("vendors.menu-category-items"),
      isShowingAgain: false,
    };
  },

  computed: {
    contentWidth() {
      var width = screen.width - this.sidebarWidth;
      return width + "px";
    },
    vendor() {
      return this.$page.props.auth.vendor;
    },
  },

  methods: {
    openMenuBox() {
      const menuvendorId = localStorage.getItem("menuvendorId");
      if (menuvendorId && menuvendorId == this.$page.props.auth.vendor.id) {
        this.$inertia.get(route("vendors.menus"));
      } else {
        this.menuBoxShow = true;
        this.showingSidebar = false;
      }
    },
    handleCheckbox(event) {
      this.isShowingAgain = !this.isShowingAgain;
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
    toggleOpenMenu() {
      this.openMenu = !this.openMenu;
      localStorage.setItem("openMenu", JSON.stringify(this.openMenu));
    },

    toggleExpandingSidebar() {
      this.expandingSidebar = !this.expandingSidebar;
      localStorage.setItem(
        "expandingSidebar",
        JSON.stringify(this.expandingSidebar)
      );
      this.getSidebarWidth();
    },

    logout() {
      this.$inertia.post(route("vendors.logout"));
    },
    getSidebarWidth() {
      var vm = this;
      setTimeout(
        () => {
          var sidebar = document.getElementById("sidebar");
          if (sidebar) {
            vm.sidebarWidth = document.getElementById("sidebar").offsetWidth;
            vm.sidebarStyle = { "max-width": this.contentWidth };
          }
        },
        500,
        vm
      );
    },

    initSidebar() {
      var expandingSidebarJson = localStorage.getItem("expandingSidebar");
      this.expandingSidebar = expandingSidebarJson
        ? JSON.parse(expandingSidebarJson)
        : true;

      this.tipElements([
        {
          id: "#navLinkDashboard",
          content: "Dashboard",
          placement: "right",
        },
        {
          id: "#navLinkReservations",
          content: "Reservations",
          placement: "right",
        },
        {
          id: "#navLinkGuests",
          content: "Guests Book",
          placement: "right",
        },
        {
          id: "#navLinkReviews",
          content: "Reviews",
          placement: "right",
        },
        {
          id: "#navLinkGallery",
          content: "Gallery",
          placement: "right",
        },
        {
          id: "#navLinkBilling",
          content: "Billing",
          placement: "right",
        },
        {
          id: "#navLinkAdmins",
          content: "Admins",
          placement: "right",
        },
        {
          id: "#navLinkTransactions",
          content: "Transactions",
          placement: "right",
        },
        {
          id: "#navLinkSettings",
          content: "Settings",
          placement: "right",
        },
        {
          id: "#navLinkLogout",
          content: "Logout",
          placement: "right",
        },
        {
          id: "#navLinkMenuSection",
          content: "Menu Section",
          placement: "right",
        },
        {
          id: "#navLinkMenu",
          content: "Menus",
          placement: "right",
        },
        {
          id: "#navLinkMenuCategories",
          content: "Menu Categories",
          placement: "right",
        },
        {
          id: "#navLinkMenuCategoryItems",
          content: "Menu Category Items",
          placement: "right",
        },
        {
          id: "#navLinkTutorial",
          content: "Tutorial",
          placement: "right",
        },
        {
          id: "#navLinkEvents",
          content: "Events",
          placement: "right",
        },
        {
          id:'#navLinkOrderManagement',
          content: "Order Management",
          placement: "right"
        }
       
        
      ]);

      this.getSidebarWidth();
    },

    redirectPage() {
      if (this.isShowingAgain) {
        localStorage.setItem("menuvendorId", this.$page.props.auth.vendor.id);
        this.$inertia.get(route("vendors.menus"));
        this.menuBoxShow = false;
      } else {
        this.$inertia.get(route("vendors.menus"));
        this.menuBoxShow = false;
      }
    },
  },
  mounted() {
    this.initSidebar();
    var openMenuJson = localStorage.getItem("openMenu");
    this.openMenu = JSON.parse(openMenuJson);
  },
};
</script>



<style>
body {
  overflow-y: hidden !important;
}
</style>