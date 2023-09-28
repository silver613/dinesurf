
<template>
    <app-layout>

     <div class="bg-white w-full  h-full">
        <div class="flex flex-col justify-center items-center align-top w-full  mt-8 h-full">
           
         <!-- header -->
             <div class="md:w-1/2 w-full md:px-0  px-5  mb-4">
                 <div class="flex items-center justify-between  md:justify-start">
                    <a  :href="route('view.dinelist', {slug: list?.slug })">
                           <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                               <circle cx="18" cy="18" r="18" fill="#9DC64E"/>
                               <path d="M20.707 11.636C20.8945 11.8236 20.9998 12.0779 20.9998 12.343C20.9998 12.6082 20.8945 12.8625 20.707 13.05L15.757 18L20.707 22.95C20.8892 23.1387 20.99 23.3913 20.9877 23.6535C20.9854 23.9156 20.8803 24.1665 20.6949 24.3519C20.5095 24.5373 20.2586 24.6424 19.9964 24.6447C19.7343 24.647 19.4817 24.5462 19.293 24.364L13.636 18.707C13.4486 18.5195 13.3433 18.2652 13.3433 18C13.3433 17.7349 13.4486 17.4806 13.636 17.293L19.293 11.636C19.4806 11.4486 19.7349 11.3433 20 11.3433C20.2652 11.3433 20.5195 11.4486 20.707 11.636Z" fill="white"/>
                             </svg>
                   </a>
                  <div class="flex justify-center w-full">
                     <span class="text-dinesurf-green-400 font-semibold text-[18px]">Add to this DineList</span>
                  </div  >
                 </div>

             <!-- serahc bar -->
             <div class="w-full relative mt-7">
               <input type="search" placeholder="Search keywords.. " @input="handleKeyUp()" v-model="search" 
               class="search block text-gray-300 border font-normal border-gray-100 text-[14px]  w-full form-control "  style="padding:10px 20px 12px 30px !important; border: 1px solid #e5e7eb ;"  />
                 <div class="absolute inset-y-0 left-0 flex items-center ">
                     <button
                     type="submit"
                     class="p-1 focus:outline-none focus:shadow-outline"
                     >
                     <svg
                         fill="none"
                         stroke="currentColor"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         stroke-width="2"
                         viewBox="0 0 24 24"
                         class="w-6 h-4  text-gray-400"
                     >
                         <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                     </svg>
                     </button>
                  </div>
             </div>
          </div>
      </div>

      <div class="min-h-[20rem]  mx-auto md:w-1/2 w-full md:px-0  px-5 mt-5  mb-10" >

        <div class="mt-5 flex flex-col space-y-5  mb-5"  v-if=" this.models.data?.length > 0">
                    <template   v-for="(item, index) in  this.models.data" :key="index" >
                        <dinelist-vendor-card :vendor="item" :dinelist="this.list"   :isSameUser="this.isSameUser"  />
                    </template>      

                    

                    <div  class=" w-full flex  items-center justify-center ">
                        <div class="bg-white p-5 pt-0"> 
                           <pagination class="mt-10 pl-5 " :links="models.links" v-if="models.data.length > 5" />
                        </div>     
                     </div>
         </div>
         <div v-else-if="this.models.data?.length < 1  && loaded">
                              <empty-space 
                                        :title="'Oops!... Its Empty'"
                                        :description=" searchQuery ? `we are unable to load any restaurants with '${searchQuery.toUpperCase()}', please try another keywords` : 'we are unable to load any restaurants  at the moment'"   
                                        :image="'/images/empty_images/4.png'"
                                        :noButton="true"
                                            />
            </div>

        <div v-if="this.loading"  class="flex items-center w-full justify-center">
                    <span class="px-7 text-3xl py-8">Loading restaurants</span>
                    <loading-icon />
         </div>
     </div>



           <div class="w-full">
              <Footer /> 
           </div>
        </div>
    </app-layout>
</template>

<script>

import AppLayout from '@/Layouts/AppLayout.vue';
import Footer from "@/components/Footer.vue";
import DinelistVendorListCard from '@/components/DinelistVendorListCard.vue';
import UserDinelistCard from '../../../components/UserDinelistCard.vue';
import EmptySpace from "@/components/EmptySpace.vue";
import DinelistVendorCard from "@/components/DinelistVendorCard.vue";
export default  {
props:['dinelist', 'isSameUser'],
 components: {
     AppLayout,
     Footer,
     DinelistVendorListCard,
     EmptySpace,
     UserDinelistCard,
     DinelistVendorCard
 },

 data(){
     return{
       tab: 'suggested',
       search: null,
       loading: true,
       loaded: false,
       list: this.dinelist,
       restaurants: [],
       searchQuery: null,
       models: {
        data: [],
        links: [],
        total: 0,
      },
      debounceTimer: null,
     }
 },

 methods:{

     handleKeyUp(){

      clearTimeout(this.debounceTimer);
          this.debounceTimer = setTimeout(() => {
               this.filterData()
          }, 500); 
     }, 
     toggleTab(tab) {
         this.$inertia.get(
             route('explore.dinelist'),
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

 filterData() {
   this.loading = true
   this.$inertia.get(
     route('add.to.dinelist', {id : this.list.id}),
     {
       search: this.search
      },
     {
       replace: true,
       preserveState: true,
       onFinish: (visit) => {
         this.loadData();
       },
     }
   );
 },

 loadData() {
     const urlSearchParams = new URLSearchParams(window.location.search);
     this.params = Object.fromEntries(urlSearchParams.entries());
     this.loaded = false;
     this.loading = true;
    this.models = {
        data: [],
        links: [],
        total: 0,
      }
   axios
     .get(route("get.restaurants.dinelist", { ...this.params, dinelist_id: this.list.id }))
     .then((result) => {
        this.models = result.data.models;
        this.searchQuery = result.data.search
     })
     .catch((err) => {
        console.log("err:",err)
     }).finally(() => {
         this.loaded = true;
         this.loading = false;
     });
 },
 load(){
         const urlSearchParams = new URLSearchParams(window.location.search);
         const params = Object.fromEntries(urlSearchParams.entries());
         this.search =  params?.search
           console.log(params)

    },

  debounce(func, delay) {
     let timerId;
     
     return function() {
         const context = this;
         const args = arguments;
         
         clearTimeout(timerId);
         
         timerId = setTimeout(function() {
         func.apply(context, args);
         }, delay);
     };
  }

},

mounted(){
 this.load()
 this.loadData()
}

}



</script>