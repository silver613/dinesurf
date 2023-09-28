
<template>
    <app-layout>

     <div class="bg-white w-full  h-full">
        <div class="flex flex-col justify-center items-center align-top w-full  mt-8 h-full">
           
         <!-- header -->
             <div class="md:w-1/2 w-full md:px-0  px-5  mb-4">
                 <div class="flex items-center justify-between  md:justify-start">
                    <button @click="back()">
                           <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                               <circle cx="18" cy="18" r="18" fill="#9DC64E"/>
                               <path d="M20.707 11.636C20.8945 11.8236 20.9998 12.0779 20.9998 12.343C20.9998 12.6082 20.8945 12.8625 20.707 13.05L15.757 18L20.707 22.95C20.8892 23.1387 20.99 23.3913 20.9877 23.6535C20.9854 23.9156 20.8803 24.1665 20.6949 24.3519C20.5095 24.5373 20.2586 24.6424 19.9964 24.6447C19.7343 24.647 19.4817 24.5462 19.293 24.364L13.636 18.707C13.4486 18.5195 13.3433 18.2652 13.3433 18C13.3433 17.7349 13.4486 17.4806 13.636 17.293L19.293 11.636C19.4806 11.4486 19.7349 11.3433 20 11.3433C20.2652 11.3433 20.5195 11.4486 20.707 11.636Z" fill="white"/>
                             </svg>
                   </button>
                  <div class="flex justify-center w-full">
                     <span class="text-dinesurf-green-400 font-semibold text-[18px]">Likes</span>
                  </div  >
                 </div>

             <!-- serahc bar -->
             <div class="w-full relative mt-7">
               <input type="search" placeholder="Search likes " @keyup="handleKeyUp()" v-model="search" 
               class="search block text-gray-300 border font-normal border-gray-100 text-[14px]  w-full form-control "  style="padding:10px 20px 12px 30px !important; border: 1px solid #e5e7eb ;"  />
                 <div class="absolute inset-y-0 left-0 mt-3 ">
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


                  <div class="flex justify-center mt-5 items-center">
                    <span v-if="this.loading"> <loading-icon /> </span>
                    <div v-else class="flex items-center">
                           <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.9401 15.66C29.7639 12.6176 27.722 9.98662 25.0669 8.09209C22.4117 6.19756 19.2595 5.12257 16.0001 5C12.7406 5.12257 9.58845 6.19756 6.93326 8.09209C4.27808 9.98662 2.23622 12.6176 1.06006 15.66C0.98063 15.8797 0.98063 16.1203 1.06006 16.34C2.23622 19.3824 4.27808 22.0134 6.93326 23.9079C9.58845 25.8024 12.7406 26.8774 16.0001 27C19.2595 26.8774 22.4117 25.8024 25.0669 23.9079C27.722 22.0134 29.7639 19.3824 30.9401 16.34C31.0195 16.1203 31.0195 15.8797 30.9401 15.66ZM16.0001 25C10.7001 25 5.10006 21.07 3.07006 16C5.10006 10.93 10.7001 7 16.0001 7C21.3001 7 26.9001 10.93 28.9301 16C26.9001 21.07 21.3001 25 16.0001 25Z" fill="#383838"/>
                                <path d="M16 10C14.8133 10 13.6533 10.3519 12.6666 11.0112C11.6799 11.6705 10.9109 12.6075 10.4567 13.7039C10.0026 14.8003 9.88378 16.0067 10.1153 17.1705C10.3468 18.3344 10.9182 19.4035 11.7574 20.2426C12.5965 21.0818 13.6656 21.6532 14.8295 21.8847C15.9933 22.1162 17.1997 21.9974 18.2961 21.5433C19.3925 21.0892 20.3295 20.3201 20.9888 19.3334C21.6481 18.3467 22 17.1867 22 16C22 14.4087 21.3679 12.8826 20.2426 11.7574C19.1174 10.6321 17.5913 10 16 10ZM16 20C15.2089 20 14.4355 19.7654 13.7777 19.3259C13.1199 18.8864 12.6072 18.2616 12.3045 17.5307C12.0017 16.7998 11.9225 15.9956 12.0769 15.2196C12.2312 14.4437 12.6122 13.731 13.1716 13.1716C13.731 12.6122 14.4437 12.2312 15.2196 12.0769C15.9956 11.9225 16.7998 12.0017 17.5307 12.3045C18.2616 12.6072 18.8864 13.1199 19.3259 13.7777C19.7654 14.4355 20 15.2089 20 16C20 17.0609 19.5786 18.0783 18.8284 18.8284C18.0783 19.5786 17.0609 20 16 20Z" fill="#383838"/>
                            </svg>
                            
                            <span class="ml-2 font-semibold" > {{ numberWithCommas(totalPageView, "") }} {{ totalPageView > 1 ? 'Views' : 'Views' }} </span>
                     </div>
                 </div>
             </div>
          </div>
     </div>

         <div class="min-h-[20rem]  mx-auto md:w-1/2 w-full md:px-0  px-5 mt-5  mb-10" >
                <div class="flex justify-between  border-b pb-3">
                      <span class="font-bold ">Liked By</span>

                      <span v-if="this.loading"> <loading-icon /> </span>
                      <span class="font-semibold" v-else>{{ numberWithCommas(totalLikes, "")  }} likes</span>
                </div>


                  <div  v-if="users.length > 0 && loaded" class="grid md:grid-cols-2 mt-5 grid-cols-1 gap-4">
                    <template   v-for="(item, index) in users" :key="index" >
                        <user-dinelist-card :user="item" :page="'likedby'" />
                    </template>
                 </div>
                <div v-else-if="users.length < 1  && loaded" class="mt-5">
                        <empty-space 
                                :title="'Oops!... Its Empty'"
                                :description="'we are unable to load any like at the moment'"   
                                :image="'/images/empty_images/4.png'"
                                :noButton="true"
                                    />
                </div>

                <div v-if="this.loading"  class="flex items-center w-full mt-5 justify-center">
                    <span class="px-7 text-3xl py-8">Loading </span>
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
import UserDinelistCard from '@/components/UserDinelistCard.vue';
import EmptySpace from "@/components/EmptySpace.vue";
export default  {

 props:['getLikes','slug','list', 'pageViewCount'],
 components: {
     AppLayout,
     Footer,
     DinelistVendorListCard,
     EmptySpace,
     UserDinelistCard
 },

 data(){
     return{
       search: null,
       loading: true,
       loaded: false,
       users: [],
       totalLikes: this.getLikes?.length,
       totalPageView: this.pageViewCount?.length
     }
 },

 methods:{

     handleKeyUp(){
         return  this.debounce(this.filterData(), 100)
     }, 

 filterData() {
    this.loading = true
    this.$inertia.get(
        route('likedby.dinelist', { slug :this.slug}),
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
   axios
     .get(route("data.likedby", { ...this.params, dinelist_id: this.list.id}))
     .then((result) => {
        const buildResult = result.data.models.data?.map((item) => item.user);
        this.users = buildResult;

         console.log("result:", buildResult);
     })
     .catch((err) => {
        console.log("err:",err)
     }).finally(() => {
         this.loaded = true;
         this.loading = false;
     });
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

 this.loadData();
//  console.log("pageViewCount:", this.pageViewCount);
}

}



</script>