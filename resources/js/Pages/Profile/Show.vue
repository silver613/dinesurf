<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profile
            </h2>
        </template>
            <div class="text-sm font-medium  text-gray-500 border-b pb-10  pt-10 border-gray-200 dark:text-gray-400 dark:border-gray-700  w-auto ">
                <ul class="flex  justify-start border-b w-6/6  md:ml-16 md:mr-16 ">
                    <li class="">
                        <a href="#" @click="handleTabChang('profile')"
                         class="inline-block p-4  rounded-t-lg border-b-2  border-grey-500 "
                         :class="currentTab == 'profile' ?  'text-[#DC2626] border-[#DC2626] active dark:text-[#DC2626] dark:border-[#DC2626]': 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' "
                         >Profile  </a>
                    </li>
                    <li class="">
                        <a href="#" @click="handleTabChang('password')"
                         class="inline-block p-4 rounded-t-lg border-b-2   " 
                          :class="currentTab == 'password' ?  'text-[#DC2626] border-[#DC2626] active dark:text-[#DC2626] dark:border-[#DC2626]': 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' "
                        
                        >Password</a>
                    </li>
                    <!-- <li class="">
                        <a href="#" @click="handleTabChang('tfa')" 
                           class="inline-block p-4 rounded-t-lg border-b-2   " 
                          :class="currentTab == 'tfa' ?  'text-[#DC2626] border-[#DC2626] active dark:text-[#DC2626] dark:border-[#DC2626]': 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' "
                        
                        >TFA</a>
                    </li> -->
                   
                    <li class="">
                        <a href="#" @click="handleTabChang('sessions')" 
                           class="inline-block p-4 rounded-t-lg border-b-2   " 
                          :class="currentTab == 'sessions' ?  'text-[#DC2626] border-[#DC2626] active dark:text-[#DC2626] dark:border-[#DC2626]': 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' "
                         
                        >Sessions</a>
                    </li>

                      <li class="">
                        <a href="#" @click="handleTabChang('account')"
                           class="inline-block p-4 rounded-t-lg border-b-2   " 
                          :class="currentTab == 'account' ?  'text-[#DC2626] border-[#DC2626] active dark:text-[#DC2626] dark:border-[#DC2626]': 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' "
                         
                         >Account</a>
                    </li>
                </ul>



             <!-- content -->
          
                        <div class="flex justify-center align-center item-center mt-10" v-if="$page.props.jetstream.canUpdateProfileInformation  &&  currentTab == 'profile' ">
                            <update-profile-information-form :user="$page.props.user" />
       
                        </div>

                                    
                        <div class="flex justify-center align-center item-center mt-10" v-if="$page.props.jetstream.canUpdatePassword  &&  currentTab == 'password'">
                                <update-password-form class="mt-10 sm:mt-0" />
                        </div>

                     

                        <div class="flex justify-center align-center item-center mt-10" v-if="$page.props.jetstream.canManageTwoFactorAuthentication  &&  currentTab == 'tfa'">
                            <two-factor-authentication-form class="mt-10 sm:mt-0" />
                       </div>

                          <div class="flex justify-center align-center item-center mt-10" v-if="currentTab == 'sessions'">
                            <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0"  />
                       </div>


                        <div class="flex justify-center align-center item-center mt-10" v-if="currentTab == 'account'">
                             <delete-user-form class="mt-10 sm:mt-0" />
                        </div>


                       



            </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DeleteUserForm from "./DeleteUserForm.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import LogoutOtherBrowserSessionsForm from "./LogoutOtherBrowserSessionsForm.vue";
import TwoFactorAuthenticationForm from "./TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "./UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./UpdateProfileInformationForm.vue";

export default {
    props: ["sessions"],

    components: {
        AppLayout,
        DeleteUserForm,
        JetSectionBorder,
        LogoutOtherBrowserSessionsForm,
        TwoFactorAuthenticationForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
    },

    data(){
        return{
            currentTab:'profile'
        }
    },

    methods:{
            handleTabChang(value){
              this.currentTab = value;
            }
    },
    mounted() {
        document.title = "Profile - Dinesurf";
    },
};
</script>