<template>
    <vendor-layout>
        <template #header>
           
            <header-text :title="'Settings'"  />
        </template>

  <div class="flex md:flex-row flex-col   md:px-10 md:space-x-5 ">

    <aside class="md:top-10 md:sticky fixed md:h-screen overflow-scroll  md:w-4/12 w-full justify-center bg-white shadow-settings-page  rounded-md">

      <ul
            class="
              rounded-lg
              flex
              flex-row
              items-center
              md:flex-col
              w-full
              dark:divide-gray-700 dark:text-gray-400
              
            "
          >
            <li 

                v-for="nav in nav_links"  :key="nav.route"
             class="w-full  text-left md:border-b md:border-b-gray-50   md:py-5"
            :class="
                  view == nav.route
                    ? 'border-l-4  border-l-dinesurf-green-400'
                    : ''
                "
            >
              <a
                href="javascript:void(0)"
                @click="goToView(nav.route)"
                class="
                  inline-block
                  px-5
                  w-full
                  focus:outline-none
                  rounded-none
                  text-xs
                  md:text-sm
                  capitalize
                "
                :class="
                  view === nav.route
                    ? 'text-dinesurf-green-400 active  font-semibold'
                    : 'text-gray-400 font-light'
                "
                aria-current="page"
                >{{ nav.name }} </a
              >
            </li>
           

          </ul>
    </aside>

     <div class="md:w-full  md:py-10  md:mt-0  mt-10  ">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation && view != 'photos'">
                    <update-profile-information-form
                        :vendor="vendor"
                        :status="status"
                        :view="view"
                    />
                   </div>

                 <div  v-if="view == 'photos'">
                           <Gallery   :vendor="vendor" />
                </div>
           
            </div>
        </div>
    </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import DeleteUserForm from "./DeleteUserForm.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import LogoutOtherBrowserSessionsForm from "./LogoutOtherBrowserSessionsForm.vue";
import TwoFactorAuthenticationForm from "./TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "./UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./UpdateProfileInformationForm.vue";
import Gallery from "../Gallery.vue"
import CustomizeMenuVue from "./CustomizeMenu.vue";

import tippy from "tippy.js";
 import "tippy.js/dist/tippy.css"; // optional for styling

export default {
    props: ["sessions", "status"],

    components: {
        Gallery,
        VendorLayout,
        DeleteUserForm,
        JetSectionBorder,
        LogoutOtherBrowserSessionsForm,
        TwoFactorAuthenticationForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
        CustomizeMenuVue
    },

    data(){
        return{
            view: "settings",
            params:{},
            vendor:this.$page.props.vendor,
            reservationFormLink: route('restaurants.index', {id: this.$page.props.vendor.id}),
            nav_links: [
              {
                name: "general information",
                route: "general_information"
              },
              {
                name: "contact information",
                route: "contact_information"
              },
              {
                name: "financials",
                route: "financials"
              },
              {
                name: "Restaurant profile Info",
                route: "restaurant_profile_info"
              },
              {
                name: "Open Times & Availability",
                route: "open_time_availability"
              },
              {
                name: "menu highlight",
                route: "menu_highlight"
              },
              {
                name: "Photos",
                route: "photos"
              },
              {
                name: "View Restaurant Profile",
                route: "view_restaurant_profile"
              }
            ]
        }
    },
    

    methods:{

      copyToClipboard(id) {
          var copyText = document.getElementById(id);
            copyText.select();
            document.execCommand("copy");
            toastr.success('Link copied to clipboard');
   },
        goToView(view) {
             this.$inertia.get(
                    route("vendors.profile.show"),
                    {
                    view: view,
                    },
                    {
                    replace: true,
                    }
                );
       },

       load(){
            const urlSearchParams = new URLSearchParams(window.location.search);
            this.params = Object.fromEntries(urlSearchParams.entries());
            this.view = this.params.view ?  this.params.view : 'general_information';

       }
    },
    
    mounted(){
      
    tippy("#VendorInfo", {
      content:
        "Preview to see your landing page, you can also share this link or add to your socials bio",
    });

        this.load();
     
    }
};
</script>
