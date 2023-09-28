<template>

<a
        :href="route('restaurants.index', [{ id: vendor.id }])"
      >
      <div class="flex justify-between ">
          <span class="font-bold text-[18px]">{{  vendor.company_name }}</span>
      </div>
                
    <div  class="
             relative
     ">  
                <div class="bg-[#00000080] z-0 rounded-[14px] opacity-2 w-full h-full absolute top-0 bottom-0">
                
                </div>
                <div class="restaurant-card-img bg-grey   rounded-[14px] flex   p-3"
                                :style="'background-image: url(' + this.vendor?.banner_url + ')'"
                        >
                            
                </div>
           </div>
          </a>
    </template>
    
    
    
    <script>
    
    export default {
       props: ['vendor', 'index'],
       components:{
        // SideDots
       },
        data() {
        return {
            vendor: this.vendor,
            index: this.index,
            image: this.vendor?.banner_url
        };
      },
      
        mounted() {

            console.log("vendor:", this.vendor.banner_url)
        },
    };
    
    
    </script>