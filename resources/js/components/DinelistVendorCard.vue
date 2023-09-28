<template>

    <div  class="flex  space-x-[10px] ">  
       <div  @click="viewVendor()" class=" w-20 h-20 bg-grey bg-gray-200 cursor-pointer   bg-center bg-cover rounded-[14px] flex   p-3" :style="'background-image: url(' + image + ')'" >
      </div> 
 
      <div class="flex justify-between w-full">
            <div class="flex flex-col cursor-pointer" @click="viewVendor()" >
                <span class="text-dinesurf-green-600 font-medium text-[20px]">{{ vendor.company_name }}</span>
                <span class="font-normal text-[18px]  text-[#383838] ">{{ vendor.company_address }}</span>
            </div>



            <div v-if="this.isSameUser">
                  <add-dinelist :vendor="vendor"  :dinelist="list" :isDinelistvendor="this.isDinelistvendor" />
    
            </div>
       </div>
     
    </div>
    
 </template>
    
 <script>
    
    import SideDots from '../Jetstream/Vendor_icons/SideDots.vue';
    import JetDropdown from "@/Jetstream/Dropdown.vue";
    import EditDineList from './EditDineList.vue';
    import AddDinelist from './AddDinelist.vue';
    import axios from 'axios';
    export default {
       props: ['vendor', 'user', 'dinelist', 'isDinelistvendor', 'isSameUser'],
       components:{
        SideDots,
        JetDropdown,
        EditDineList,
        AddDinelist
       },
        data() {
        return {
          image:this.vendor?.banner_url,
          isDeleting:false,
          vendor:this.vendor,
          list: this.dinelist
        };
      },
    
      methods:{

        viewVendor(){

          return window.location.href = route('restaurants.index', { id: this.vendor.id })
        },
        OnClosingModal(){
                 this.showEditDineList = false;
                 window.location.reload();
          },
           deleteDinelist(){
            const id = this.list.id;
            this.isDeleting = true;
            axios.delete(route('delete.dinelist', {id }))
            .then((result) => {
              toastr.success(result.data.message);
              setTimeout(
                    function () {
                      window.location.reload();
                    },
                    500,
                  );
            })
            .catch((error)=>{
              console.log("error:", error)
            }).finally(() =>  this.isDeleting = false  )
    
           }
      },
      
        mounted() {

        },
    };
    
    
    </script>