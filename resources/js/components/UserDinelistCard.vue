<template>
 <a  class="flex  space-x-[10px] items-center" :href="route('dinelist.profile', {slug: user.slug})">          
   <div   class=" w-16 h-14 bg-grey bg-gray-200   bg-center bg-cover rounded-full flex   p-3" :style="'background-image: url(' + image + ')'" ></div> 
        <div class="w-full">
            <div v-if="this.page === 'likedby'" class="flex justify-between items-center">
                  
               <div  class="flex flex-col space-y-0">
                       <span class=" font-semibold text-[18px]">{{ user?.displayname ? user?.displayname : user.name === null ? fullName : user.name }}</span>
                        <span v-if="user.username">@{{  user.username }}</span>
                </div>

                <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                        </div>
            </div>
            <div class="flex flex-col space-y-1   w-full" v-else >
                <span class=" font-semibold text-[18px]">{{ user?.displayname ? user?.displayname : user.name === null ? fullName : user.name }}</span>

                <div class="flex justify-between w-full">
                 <div class="flex flex-col space-y-1">
                           <div class="flex space-x-4 items-center">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 14C5.28334 14 5.521 13.904 5.713 13.712C5.905 13.52 6.00067 13.2827 6 13C6 12.7167 5.904 12.479 5.712 12.287C5.52 12.095 5.28267 11.9993 5 12C4.71667 12 4.479 12.096 4.287 12.288C4.095 12.48 3.99934 12.7173 4 13C4 13.2833 4.096 13.521 4.288 13.713C4.48 13.905 4.71734 14.0007 5 14ZM5 10C5.28334 10 5.521 9.904 5.713 9.712C5.905 9.52 6.00067 9.28267 6 9C6 8.71667 5.904 8.479 5.712 8.287C5.52 8.095 5.28267 7.99934 5 8C4.71667 8 4.479 8.096 4.287 8.288C4.095 8.48 3.99934 8.71734 4 9C4 9.28334 4.096 9.521 4.288 9.713C4.48 9.905 4.71734 10.0007 5 10ZM5 6C5.28334 6 5.521 5.904 5.713 5.712C5.905 5.52 6.00067 5.28267 6 5C6 4.71667 5.904 4.479 5.712 4.287C5.52 4.095 5.28267 3.99934 5 4C4.71667 4 4.479 4.096 4.287 4.288C4.095 4.48 3.99934 4.71734 4 5C4 5.28334 4.096 5.521 4.288 5.713C4.48 5.905 4.71734 6.00067 5 6ZM8 14H14V12H8V14ZM8 10H14V8H8V10ZM8 6H14V4H8V6ZM2 18C1.45 18 0.979002 17.804 0.587002 17.412C0.195002 17.02 -0.000664969 16.5493 1.69779e-06 16V2C1.69779e-06 1.45 0.196002 0.979002 0.588002 0.587002C0.980002 0.195002 1.45067 -0.000664969 2 1.69779e-06H16C16.55 1.69779e-06 17.021 0.196002 17.413 0.588002C17.805 0.980002 18.0007 1.45067 18 2V16C18 16.55 17.804 17.021 17.412 17.413C17.02 17.805 16.5493 18.0007 16 18H2Z" fill="#77943F"/>
                                </svg>
                                <span class="font-normal text-[16px]  text-dinesurf-black-800 ">{{ user?.dinelists?.length }}  {{ user?.dinelists?.length > 1 ? 'lists' : 'list'}}  </span>
                            </div> 
                            <div class="flex space-x-4 items-center">
                                <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.25 1C2.90315 1 1 2.73964 1 4.88594C1 6.61852 1.74375 10.7305 9.0648 14.8873C9.19594 14.961 9.34649 15 9.5 15C9.65351 15 9.80406 14.961 9.9352 14.8873C17.2563 10.7305 18 6.61852 18 4.88594C18 2.73964 16.0968 1 13.75 1C11.4031 1 9.5 3.35511 9.5 3.35511C9.5 3.35511 7.59685 1 5.25 1Z" fill="#FB4949" stroke="#FB4949" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="font-normal text-[14px]  text-dinesurf-black-800 "> {{ likes }}  {{ likes > 1 ? 'likes' : 'like'}}  </span>
                            </div> 
                      </div>
                         
                      <div class="flex items-center">
                        <i class="fa fa-long-arrow-right" ></i>
                         <!-- <a :href="route('dinelist.profile', {slug: user.slug})" class="text-white bg-dinesurf-green-600 font-semibold px-4 py-2 rounded-full">Explore</a> -->
                     </div>
                </div>
              
           </div>

           
        </div>
     
    </a>
</template>


<script>
export default{
    props: ['user', 'page'],
    components: {},
    data() {
        return {
          image:this.user.image_url,
          user: this.user,
          likes: 0,
          fullName: this.user?.first_name + ' ' + this.user?.last_name

        };
    },
    methods:{

            getLikes(){
            let  likes = [];
            this.user?.dinelists?.map((dinelist) => {
                    if(dinelist.likes.length > 0){
                        likes.push(dinelist.likes.length)
                    }
                });
            this.likes =  likes.reduce((a, b) => a + b, 0);
              
            }
    },
    mounted(){
        this.getLikes()
    }

}

</script>