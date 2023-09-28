<template>
     <div  class="flex md:flex-row  flex-col w-full   md:mt-10 mt-4" v-if="this.menus">


       <!-- mobile view -->
      
    <div class="flex flex-row content-center justify-center  w-auto items-center   md:hidden"  >
        <select  v-model="this.selectedV" @change="fireAction()"  class="w-96 ml-3 mr-5  block py-3 px-4 text-base text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected="" disabled>Choose a Menu</option>
            <option   v-for="menu in menus" :key="menu.id"  :value="menu.id">{{menu.name}}</option>
        </select>
    </div>
  <!-- end mobile view -->


 <!-- desktop view -->
          <div class="w-64 hidden md:block   h-[22rem] bg-red  pr-5 overflowHidden  overflow-x-scroll  "  v-if="!this.page">
                    <button
                v-for="menu in menus"
                :key="menu.id"
                class="flex flex-col
                        justify-center
                        w-full
                        mb-3
                        px-4
                        py-2
                        text-sm
                        font-medium
                        md:border 
                        md:border-[#d8d9db]
                        items-center"
                        :class=" this.selectedMenu.id === menu.id ? 'bg-[#9DC64E] text-white': 'bg-white text-black'"

                      @click="getSelectedCategories(menu.menu_categories, menu)"
                >
                 <span>{{menu.name}}</span>  

                <p v-show="this.page"  style="font-size:10px; color:#8b7b7b">{{menu.description}}</p>   
                </button>
          </div>


          <!-- General area -->
          <div class="bg-red md:p-20  p-10  md:pt-5  pt-6 border-[#d8d9db] border-l md:w-[60%] w-full overflowHidden  overflow-x-scroll  h-[21rem]  "  >
            <div class="mb-5"  v-for="category in  this.selectedCategories" :key="category.id">
              <div class="mb-4 items-center  flex pb-2 flex-col">
                <h2 class="font-bold  pb-1 text-base">{{category.name}}</h2>
                <p v-show="this.page" >{{category.description}}</p>
              </div>
          
                        <div class="mb-3" v-for="item in category.items" :key="item.id">
                             
                          <div class="flex flex-row justify-between border shawdow-md p-3 mb-1">
                            <span class=" w-64">{{item.name}} </span>
                            <span>{{
                                 numberWithCommas(
                                       item.price,
                                      this.vendor.average_menu_price_currency
                                    )
                               }}</span>
                          </div>
                          <p v-show="this.page"    style="font-size:12px; color:#8b7b7b"> {{item.description}}</p>  
                        </div>
            </div>
                    
          </div>
     </div>
     <div class="text-center  items-center" v-else>
           <h6 class="text-center">No menus </h6>
     </div>

</template>

<script>

import { ChevronDownIcon } from "@heroicons/vue/solid";
export default {
  props: ["menus", "vendor", "page"],
  

  components:{
    ChevronDownIcon
  },
  data(){
    return{
      selectedCategories:this.menus[0]?.menu_categories,
      selectedMenu:this.menus[0],
      selectedV: this.menus[0]?.id
    }
  },
  methods:{

    fireAction(){
        const SelectedResult = this.menus.filter((item) => item.id == this.selectedV);
        this.selectedMenu = SelectedResult[0];
        this.selectedCategories = SelectedResult[0].menu_categories;

    },
      getSelectedCategories(categories, menu){
        this.selectedCategories = categories;
        this.selectedMenu = menu;
      },
    
  },


  mounted() {
  
   
  },
};
</script>
