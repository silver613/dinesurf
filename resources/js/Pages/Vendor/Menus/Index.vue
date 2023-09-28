<template>
  <vendor-layout>
    <template #header>
        <header-text :title="title"  />
    </template>

    <template   #rightContent>
        <div class="flex relative  md:pr-8  pr-3">
              <div class="w-full">
                  <input type="search" placeholder="Search menu" v-model="search" @keyup="filterData()" class="search block text-gray-400 text-sm  w-full form-control "  style=" padding-left:30px"  />
              </div>

              <span class="absolute inset-y-0 left-0 flex items-center ">
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
          </span>
          </div>
    </template>


    <!-- middle section -->
    <div    class="flex flex-wrap md:w-auto w-full justify-between items-center  border-y-2 bg-gray-100 p-2 md:mx-8 md:mr-7 ">
               <div class="flex  md:flex-row flex-col items-center md:w-auto w-full  md:mb-0 mb-4">
                    <!-- <div v-if=" models.data.length >  0" class=" bg-white rounded shadow-md p-3 md:mr-4  w-full  md:w-[10rem] items-center flex justify-center">
                                <Link :href="route('vendors.menu-categories')" :id="'MenuCategories'"  class="flex " >
                                    <span class="text-black">
                                      View categories
                                    </span>
                                    <div  class="mt-1 ml-2">
                                      <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 7H13M7.75 1.75L13 7L7.75 12.25" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                      </svg>
                                    </div>
                                  </Link>
                            </div> -->

                        <!-- <div v-if=" models.data.length >  0" class="shadow-md  bg-white rounded  md:mr-4 p-3 w-full md:w-[10rem] items-center flex justify-center md:mt-0 mt-2">
                           <Link  :href="route('vendors.menu-category-items')" :id="'ViewItems'" class="flex " >
                                <span class="text-black">
                                  View items
                                </span>
                                <div  class="mt-1 ml-2">
                                   <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 7H13M7.75 1.75L13 7L7.75 12.25" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                   </svg>
                                </div>
                              </Link >
                        </div> -->
                        <!-- :href="route('vendors.create-menu-page')" -->
                        <div class="shadow-md  bg-dinesurf-green-400 rounded p-3 w-full  items-center flex justify-center md:mt-0 mt-2">
                           <button  @click="showMenu = true"  type="button"  :id="'CreateMenu'" class="flex  items-center" >
                                 <div  class=" mr-2">
                                  <create-icon />
                                </div>
                                 <span class="text-white">
                                  Create New  Menu
                                </span>
                              
                              </button >
                        </div>
               </div>

               <div class="flex  items-center   lg:space-x-3  lg:space-y-0  space-y-3"  >
                <!-- v-if=" models.data.length >  0" -->
                <div>
                    <button @click="showSettings = true"   class=" mt-1 md:text-xs text-[12px]    text-blue-600">
                      <i class="fa fa-engine  "></i>
                      Menu Setting</button> 
                   </div>
                 <div>
                    <a  :href="this.barcodeLink"  id="qrcodeImage"  target="_blank"  class="mt-1 md:text-xs text-[12px]     text-blue-600">
                      <i class="fa fa-download  "></i>
                      Download QRCode</a> 
                   </div>
                   <div class="mr-3">
                    <a  :href="this.menuPageLink"   target="_blank" class=" mt-1  md:text-xs text-[12px]  underline text-blue-600">View Menu Page</a> 
                   </div>
                   <div>
                    <a  href="#"  @click="copyToClipboard('ctn-link')"    id="copyLink"  class="mt-1  md:text-xs text-[12px]  text-blue-600">
                      <i class="far fa-copy  "></i>
                      Copy Menu Link</a> 
                   </div>

               </div>
    </div>


    <div  class="mt-10 flex   justify-end md:mr-5">
      <flash-text
          :title="'Need help with the menu creation ?'"
           :link="'mailto:info@dinesurf.com'"
           :status="'success'"
        />
    </div>

    <div  class="  flex flex-wrap pt-4 px-3 md:mx-8 md:mr-4  h-auto mb-30">


      <div v-if="this.loading"  class="flex items-center w-full justify-center">
        <span class="px-7 text-3xl py-8">Loading Menus</span>
         <loading-icon />
      </div>


   
       <draggable   v-else  class="dragArea list-group w-full md:flex flex-wrap" :list="models.data" @change="handleChange">
             
              <div  v-for="(model) in models.data" :key="model.id" >
                  <menu-card  :menu="model"  @onDelete="fireAction" />     
              </div> 
        </draggable>
  
   


     <div  class=" w-full flex  items-center justify-center ">
        <div class="bg-white p-5 pt-0"> 
          <pagination class="mt-10 pl-5 " :links="models.links" v-if="models.data.length > 5" />
        </div>     
     </div>

    </div>
       

      <div  v-if="this.loaded && models.data.length === 0" class=" px-10 md:ml-10  items-center justify-center text-center" >
               

                <empty-space 
                  :title="'Oops!... Its Empty'"
                  :description="'94% of diners check a restaurant menu online before deciding to visit, You can start building your online QR code menu with one click'"
                  :page="'menu'"
                  @handleAsbutton="showMenu = !showMenu"
                  :image="'/images/empty_images/4.png'"
                  :asButton="true"
                    />
    </div> 


    <input
                  type="text"
                  :value="this.menuPageLink"
                  name="ctn-link"
                  placeholder="copy link"
                  class="opacity-0 h-1 focus:outline-none"
                  id="ctn-link"
              />


                <!-- menu notes -->
    <!-- <menu-popup  :show="this.menuBoxShow"   @close=" this.menuBoxShow = !this.menuBoxShow" /> -->
  


    <jet-modal :show="showSettings" @close="showSettings = !showSettings" >
         <div class="flex  justify-center items-center mb-10">
                <menu-setting    :setting="this.menu_setting"   
                  />
         </div>
    </jet-modal>

    <jet-modal :show="showMenu" @close="showMenu = !showMenu" >
         <div class="flex  justify-center items-center mb-10">
               <create-menu
                        class="b-g-white"
                        :vendor="vendor"
                        :is_admin="true" >
                  </create-menu>
         </div>
    </jet-modal>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling
import MenuCard from "./MenuCard.vue"
import EmptySpace from "@/components/EmptySpace.vue";
// import Draggable from 'vuedraggable'
import { VueDraggableNext } from 'vue-draggable-next';
import CreateMenu from "@/components/vendor/VendorCreateMenu.vue";
import MenuSetting from "./MenuSetting.vue";
import axios from 'axios';


export default {
  components: {
    VendorLayout,
    MenuCard,
    EmptySpace,
    draggable: VueDraggableNext,
    CreateMenu,
    MenuSetting
  },
  props: {
    title: String,
    filters: Object,
    menu_setting: Object,
  },
  data() {
    return {
      showMenu:false,
      showSettings: false,
      drag:false,
      indexLink: route('vendors.menus'),
      menuCategoryBoxShow:false,
      menuItemBoxShow:false,
      isShowingAgain: false,
      menuBoxShow:false,
      models: {
        data: [],
        links: [],
        total: 0,
      },
      loading: false,
      loaded: false,
      vendor: this.$page.props.auth.vendor,
      search:null,
      params: {},
      actionData: {},
      approved: this.filters.approved,
      showEmailModal: false,
      showTextModal: false,
      columns: [
        {
          name: "id",
          db_name: "id",
          sortable: true,
        },
        {
          name: "name",
          db_name: "name",
          sortable: true,
        },

        {
          name: "description",
          db_description: "description",
          sortable: true,
        },
     
      ],
      actions: [
        {
         value:"delete",
         name: "Delete all selected rows"
         }
       ],
      birthday: this.filters.birthday,
      birthday_date: this.filters.birthday_date,
      menuPageLink:  route('menu', { id : this.$page.props.auth.vendor.slug }),
      barcodeLink:''
    };
  },
  computed: {
    rows() {
      var modelRows = [];

      this.models.data.forEach((el, index) => {
        modelRows.push([
          el.id,
          el.name,
          el.description,
          index
        ]);
      });

      return modelRows;
    },
  },
  methods: {


    handleChange(event){
        this.models.data.map((item, index) => {  item.index = index + 1 });
        axios.put(route("vendors.sorting-menu"), {
         menus: this.models.data
        }).then((result) => {
          toastr.success(result.data.message);
        });
 },

  
    filterData() {
      this.loading = true
      this.$inertia.get(
        this.indexLink,
        {
          search: this.search,
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

    openMenuBox(){
      
      const menuvendorId = localStorage.getItem('menuvendorId');
        if(!menuvendorId  && menuvendorId  != this.$page.props.auth.vendor.id){
          this.menuBoxShow = true;
           this.showingSidebar = false;
        }
      },



   

    copyToClipboard(id) {
          var copyText = document.getElementById(id);
            copyText.select();
            document.execCommand("copy");
            toastr.success('Link copied to clipboard');
       },

  
   fireAction(data) {
      var action = data.action;
      var ids = data.ids;
      var all = data.all;

      if (!action) {
        return;
      }

      if (ids.length <= 0) {
        alert("Please select a menu to delete");
        return;
      }


      if(confirm("Are you sure you want to delete ?")){      
       this.$inertia.post(
          route("vendors.menu-action") + "?without_async=true",
          {
            ids: ids,
            action: action,
            action_model: "menu",
          },
          {
            onFinish: (visit) => {
              this.loadData();
            },
          }
        ); 
      }

           
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
      };

      axios
        .get(route("data.vendors.menus", { ...this.params }))
        .then((result) => {
          this.models = result.data.models;
          this.vendor = result.data.vendor;
          this.loading = false;
          this.loaded = true;
          this.loadReservationsCount();
        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
    loadReservationsCount() {
      this.models.data.forEach((el) => {
        axios
          .get(
            route("data.vendors.count-guest-reservations", {
              guest_id: el.id,
            })
          )
          .then((result) => {
            el.reservations_count = result.data.count;
          })
          .catch((err) => {
            console.log(err);
          });
      });
    },
  },
  mounted() {
    this.barcodeLink =`https://chart.googleapis.com/chart?cht=qr&chs=400x400&chl=${this.menuPageLink}`;
    
    this.loadData();
    tippy("#copyLink", {
       content: "Copy To Clipboard",
   });

   tippy('#qrcodeImage', {
       content: "Click To View/Right Click To Save As Image"
   });

   this.openMenuBox();

  //  console.log("this.menu_setting:", this.menu_setting)

  },
};
</script>