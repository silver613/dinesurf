<template>
 
   <div class="flex    mb-20 h-screen   bg-no-repeat bg-cover mainContainer   bg-center"  :style="'background-image: url(' + bgImage +') '">



          <div class="shadow-side-menu  h-screen w-[24%]  hidden lg:block   ">
                    <div class="p-5 pt-4  ">
                      <a
                        v-if="vendor.profile_photo_path"
                        target="_blank"
                        :href="route('restaurants.index', [{ id: vendor.id }])">

                         <jet-authentication-card-logo  class="text-center  w-[6rem] h-[6rem] object-contain" />
                        </a>

                      <button>
                        <span  class="fa fa-angle-left  mr-3"></span>
                             <span>Back</span>
                        </button>
                        

                    </div>


                    <div
                        class="
                          w-full
                          flex
                          flex-col
                          justify-center
                          items-center
                          content-center
                          border-y
                          border-black
                          py-2
                         
                        "
                        v-if="vendor"
                      >
                        <div class="">
                          <img
                            class="
                              md:h-[2.5rem] md:w-[2.5rem]
                              h-[2.2rem]
                              w-[2.2rem]
                              rounded-full
                              object-cover
                            "
                            :src="vendor.profile_photo_url"
                            :alt="vendor.company_name"
                          />
                        </div>
                        <div class="flex flex-col">
                          <span class="text-[12px]">

                            {{
                            vendor.company_name.length > 20
                                ? vendor.company_name.substring(0, 20) + "..."
                                :vendor.company_name
                            }}
                            </span
                          >
                      
                        </div>
                      </div>


                      <div class="flex flex-col  mt-7  p-3  pb-10  h-[60%] overflowHidden-menu  overflow-x-scroll ">
                            <button 
                            v-for="menu in this.menus" :key="menu.id"
                              :class=" this.selectedMenu.id === menu.id ? 'bg-dinesurf-red-400 text-white': 'text-black'"
                             @click="getSelectedCategories(menu.menu_categories, menu)"
                              class="mt-3  font-bold  rounded-md p-2 px-5   text-justify " >
                             {{menu.name}}
                            </button>
                           
                      </div>

      </div>


         <div class="w-full md:p-20  md:pt-10   max-h-screen overflow-y-auto  md:mb-0  mb-[5rem] "
        
         >

            <div
                class="restaurant-card-img restaurant-card-img-event"
                :style="'background-image: url(' + vendor.banner_url + ')'"
              >
              <div class=" relative vendor-index-overlay">
                       <div class="bottom-5  left-5  absolute  flex flex-col">
                           <span class="text-white text-xl  font-bold">{{ vendor.company_name }}</span>
                           <span class="text-[#f4f4f4]  text-[12px]">Menu list and prices</span>
                       </div>
              </div>
            </div>
             

                  <!-- mobile menu -->
          <div        class="
                    flex
                    justify-flex-start
                    text-center
                    content-center
                    overflow-x-scroll
                    lg:hidden 
                    pt-8
                  ">

                    <div
                    v-for="menu in this.menus" :key="menu.id"
                      class="  mr-3 mb-2 text-sm  cursor-pointer"
                      :class=" this.selectedMenu.id === menu.id ? 'font-bold text-black  border-b-2 border-b-dinesurf-red-400 ': ' text-[#9F9F9F]'"
                      @click="getSelectedCategories(menu.menu_categories, menu)"
                      >
                    <span  class="w-32  block">
                        {{menu.name}}
                    </span>
                  
                    </div>
             
              </div>

          <div class="mb-5  ld:mt-10  mt-3 md:px-0 px-5 "  v-for="category in  this.selectedCategories" :key="category.id">

              
            <div class="mb-4 items-center  mt-[55px] flex pb-2 flex-col">
                      <h2 class="font-bold  pb-1 text-base">{{category.name}}</h2>
                      <p>{{category.description}}</p>
                    </div>

                   
                    <div class="grid lg:grid-cols-4  grid-cols-2 gap-2">
                             <div  class="single-restaurant-card  lg:w-[196px] lg:min-h-[196px] bg-white md:m-3 mt-3
                                  mr-3
                                  rounded-lg
                                  relative
                                  shadow-md border
                                  "   v-for="item in category.items" :key="item.id">
                                 
                                  <div
                                    class="item-card-img bg-grey  rounded-t-md"
                                    :style="'background-image: url(' + item.image_url + ')'"
                                  ></div>
                                  
                                  <div class="flex flex-col  p-[10px]">
                                       <span class="text-[16px]  font-medium">{{ item.name }}</span>
                                       <span class="mt-[3px]  text-[13px] font-normal  text-[#9CA3AF]">
                                        {{ item.description }}
                                        <!-- some descrip about milosome descrip about milosome descrip about milosome descrip about milosome descrip about milosome descrip about milosome descrip about milosome descrip about milo -->
                                      </span>
                                       <div class="flex justify-end items-end">
                                            <span class="mt-[6px]  text-[13px]  font-medium">{{ 
                                            numberWithCommas(
                                              item.price,
                                               this.vendor.average_menu_price_currency
                                            ) 
                                    }}</span>
                                       </div>
                                       
                                 </div>

                             </div>
                    </div>
          
                        <!-- <div class="mb-3 border  rounded-md shadow-md p-5 bg-white " v-for="item in category.items" :key="item.id">
                             
                          <div class="flex flex-row justify-between   mb-1">
                            <span class=" w-64">{{item.name}} </span>
                            <span>{{
                                 numberWithCommas(
                                       item.price,
                                      this.vendor.average_menu_price_currency
                                    )
                               }}</span>
                          </div>
                          <p     style="font-size:12px; color:#8b7b7b"> {{item.description}}</p>  
                        </div> -->


            </div>

           
       <main class="w-screen md:w-auto  lg:hidden block   absolute bottom-0 bg-white z-index-0" >
        
          <div class="my-2 leading-normal items-center  content-center  text-center text-xs text-gray-500 space-y-1">
            <p class="text-center  text-[#198f68]">
              Powered by
            </p>
            <div  class="flex flex-row justify-center  content-center">
               <a class="link-default   text-center" href="https://dinesurf.com"> 
                <jet-authentication-card-logo  class="text-center object-contain" />
              </a>
            </div>
           
            <p class="text-center">© {{ new Date().getFullYear() }} Dinesurf Ltd.</p>
          </div>
        </main>

     

  </div>




    </div>
         




</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo2.vue";

export default {
  props: [ "vendor",  "menus", "setting"],
  components: {
    AppLayout,
    JetAuthenticationCardLogo
  },
  data() {
    return {
      selectedCategories:this.menus[0]?.menu_categories,
      selectedMenu:this.menus[0],
      selectedV: this.menus[0]?.id,
      exampleModalShowing: false,
      tab: "info",
      color:'#000',
      bg: this.setting?.bg_type == 'color' ? this.setting?.bg : '#fff',
      getCustomizeSettings: Number(localStorage.getItem('vendor_with_customize')), 
      theme: this.setting?.bg_type == 'theme' ? this.setting?.theme_url : null,
      bgImage: this.setting?.bg_type == 'image' ? this.setting?.image_url : this.setting?.bg_type == 'theme' ? this.setting?.theme_url : null,
    };
  },
  methods: {

    getSelectedCategories(categories, menu){
        this.selectedCategories = categories;
        this.selectedMenu = menu;
      },
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
      // this.handleColor();
  }, 2000)
  
    if (this.step) {
      this.tab = this.step;
    }

    if (this.vendor) {
      // document.title = this.vendor.company_name + " - menus";
      document.querySelector('link[rel=icon]').href = this.vendor.profile_photo_url;
    }

  },

 
};
</script>

<style>
  .mainContainer{
    background-color: v-bind('bg');
    color: v-bind('color')
  }

  body{
    overflow: hidden;
  }
</style>
