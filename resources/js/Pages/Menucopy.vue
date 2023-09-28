<template>
 
  <div class="flex flex-col  sm:px-6 lg:px-10"  id="mainContainer" >

  <!-- :class=" this.getCustomizeSettings === this.vendor.id ? `bg-[${bg}]   text-[#f46262]` : 'bg-[#000] text-[#fff]'" -->
  

        <div class="flex flex-row justify-center  content-center md:px-40   mt-5  md:p-0 p-5  ">
                <div  class="w-auto text-center ">
                    <a
                        v-if="vendor.profile_photo_path"
                        target="_blank"
                        :href="route('restaurants.index', [{ id: vendor.id }])">

                        <img
                        class="fill-current h-[3rem] w-auto  mb-1  bg-red"
                        :src="vendor.profile_photo_url"
                        />
                      
                    </a>
                  
              </div>
        </div>

            <div class=" md:pr-[17rem]  md:pl-[10rem] ">
              <h3  class="text-center  font-sans  text-2xl pb-5 content-center    p-5 border-b ">
                {{this.vendor.company_name}}'s Menu
              </h3>
            </div>

            <div class="w-auto  md:ml-40">
                <Menu :vendor="vendor" :menus="menus" :page="true"></Menu>
            </div>

       <main class="w-screen md:w-auto" >
          <div class="mt-8 leading-normal items-center  content-center  text-center text-xs text-gray-500 space-y-1">
            <p class="text-center  text-[#198f68]">
              Powered by
            </p>
            <div  class="flex flex-row justify-center  content-center">
               <a class="link-default   text-center" href="https://dinesurf.com"> 
                <jet-authentication-card-logo  class="text-center object-contain" />
              </a>
            </div>
           
            <p class="text-center">© 2022 Dinesurf Ltd.</p>
          </div>
        </main>

        
   </div>



</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Menu from "@/components/vendor/Menu.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo2.vue";

export default {
  props: [ "vendor",  "menus"],
  components: {
    AppLayout,
    Menu,
    JetAuthenticationCardLogo
  },
  data() {
    return {
      exampleModalShowing: false,
      tab: "info",
      color:'#000',
      bg:'#fff',
      getCustomizeSettings: Number(localStorage.getItem('vendor_with_customize'))
    };
  },
  methods: {
    toggleTab(tab) {
      //   var url = window.location.pathname + "?tab=" + tab;
      this.$inertia.get(
        route("restaurants.index", { id: this.vendor.id }),
        { tab: tab },
        {
          preserveState: true,
          preserveScroll: true,
          onFinish: (visit) => {
            this.tab = tab;
          },
        }
      );
    },

    handleColor(){
     const bgColor  =  localStorage.getItem('vendor_with_customize_bg');
     this.bg = '#fff';
     const color = localStorage.getItem('vendor_with_customize_color');
     this.color = color;
    }
  },
  mounted() {
    window.setInterval(() => {
      this.handleColor();
  }, 2000)
  
    if (this.step) {
      this.tab = this.step;
    }

    if (this.vendor) {
      document.title = this.vendor.company_name + " - menus";
      document.querySelector('link[rel=icon]').href = this.vendor.profile_photo_url;
    }
  },
};
</script>

<style>
  #mainContainer{
    background-color: v-bind('bg');
    color: v-bind('color')
  }
</style>
