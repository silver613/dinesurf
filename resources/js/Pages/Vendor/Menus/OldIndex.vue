<template>
    <vendor-layout>
      <template #header>
          <header-text :title="title"  />
      </template>
  
          <div class="bg-white p-2 mt-3  pl-8 w-auto  flex md:flex-row  flex-col  justify-between md:items-center  md:content-center">
            <div  class=" flex flex-row  mr-4    ">
                 <div  class="border  w-40 p-2 mr-3">
                        <div  class="icon bg-gray-200  w-8 h-8 p-2 rounded-full">
                          <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M5.87524 13.0625H13.7502V14.1875H5.87524V13.0625ZM2.03337 13.625L0.582117 15.0763L1.37524 15.875L3.62524 13.625L1.37524 11.375L0.576492 12.1681L2.03337 13.625ZM5.87524 7.4375H13.7502V8.5625H5.87524V7.4375ZM2.03337 8L0.582117 9.45125L1.37524 10.25L3.62524 8L1.37524 5.75L0.576492 6.54313L2.03337 8ZM5.87524 1.8125H13.7502V2.9375H5.87524V1.8125ZM2.03337 2.375L0.582117 3.82625L1.37524 4.625L3.62524 2.375L1.37524 0.125L0.576492 0.918125L2.03337 2.375Z" fill="#9DC64E"/>
                            </svg>
                        </div>
  
                        <div class="mt-3">
                           <span  class="font-bold">Categories</span>
                        </div>
                        <div class="mt-3">
                            <a href="#" as="button" @click="this.openCategoryBox()" class="flex " >
                                <span class="text-blue-600">
                                  View categories
                                </span>
                                <div  class="mt-1 ml-2">
                                   <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 7H13M7.75 1.75L13 7L7.75 12.25" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                   </svg>
                                </div>
                              </a>
                        </div>
                 </div>
  
  
  
                 <div  class="border  w-40 p-2">
                        <div  class="icon bg-gray-200  w-8 h-8 p-2 rounded-full">
                          <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.704 10.8224H5.82516L6.37139 9.70982L15.4472 9.69338C15.7541 9.69338 16.0171 9.47416 16.0719 9.1709L17.3288 2.13571C17.3617 1.9512 17.3124 1.76121 17.1918 1.61689C17.1322 1.54586 17.0579 1.48865 16.974 1.44922C16.8901 1.40978 16.7986 1.38908 16.7059 1.38853L5.16019 1.35017L5.06154 0.886151C4.99943 0.590202 4.73271 0.374634 4.42945 0.374634H1.60697C1.43594 0.374634 1.27192 0.442576 1.15098 0.563514C1.03004 0.684452 0.962097 0.848479 0.962097 1.01951C0.962097 1.19054 1.03004 1.35457 1.15098 1.47551C1.27192 1.59645 1.43594 1.66439 1.60697 1.66439H3.90697L4.33811 3.71411L5.39951 8.85303L4.03303 11.0836C3.96206 11.1794 3.91932 11.2931 3.90964 11.4119C3.89995 11.5307 3.92371 11.6499 3.97822 11.7559C4.08783 11.9733 4.30888 12.1103 4.55368 12.1103H5.70094C5.45636 12.4352 5.32425 12.8308 5.32461 13.2375C5.32461 14.2715 6.16496 15.1118 7.19895 15.1118C8.23295 15.1118 9.0733 14.2715 9.0733 13.2375C9.0733 12.8301 8.93811 12.4337 8.69697 12.1103H11.64C11.3954 12.4352 11.2633 12.8308 11.2637 13.2375C11.2637 14.2715 12.104 15.1118 13.138 15.1118C14.172 15.1118 15.0124 14.2715 15.0124 13.2375C15.0124 12.8301 14.8772 12.4337 14.636 12.1103H16.7059C17.0603 12.1103 17.3507 11.8217 17.3507 11.4654C17.3497 11.2946 17.2811 11.1311 17.1599 11.0106C17.0388 10.8901 16.8749 10.8225 16.704 10.8224ZM5.42874 2.62166L15.9477 2.65637L14.9174 8.42555L6.65455 8.44016L5.42874 2.62166ZM7.19895 13.8147C6.88108 13.8147 6.62167 13.5553 6.62167 13.2375C6.62167 12.9196 6.88108 12.6602 7.19895 12.6602C7.51682 12.6602 7.77624 12.9196 7.77624 13.2375C7.77624 13.3906 7.71542 13.5374 7.60715 13.6457C7.49889 13.7539 7.35206 13.8147 7.19895 13.8147ZM13.138 13.8147C12.8202 13.8147 12.5607 13.5553 12.5607 13.2375C12.5607 12.9196 12.8202 12.6602 13.138 12.6602C13.4559 12.6602 13.7153 12.9196 13.7153 13.2375C13.7153 13.3906 13.6545 13.5374 13.5462 13.6457C13.438 13.7539 13.2911 13.8147 13.138 13.8147Z" fill="#9DC64E"/>
                        </svg>
  
                        </div>
  
                        <div class="mt-3">
                           <span  class="font-bold">Items</span>
                        </div>
                        <div class="mt-3">
                            <a  href="#"   @click="this.openItemBox()" class="flex " >
                                <span class="text-blue-600">
                                  View items
                                </span>
                                <div  class="mt-1 ml-2">
                                   <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 7H13M7.75 1.75L13 7L7.75 12.25" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                   </svg>
                                </div>
                            </a >
                        </div>
                 </div>
               
  
  
            </div>
          
  
            <div  class=" flex flex-row  mr-4 md:mt-0  mt-20">
                <!-- <a :href="this.barcodeLink"   target="_blank"   class="mr-2 underline w-[6rem] mt-1  text-dinesurf-red-500">View QR code</a>         -->
                <a  :href="this.menuPageLink"   target="_blank" class="mr-1 ml-2 mt-1  w-[8rem] underline text-dinesurf-red-500">View Menu Page</a>  
                <button
                  type="button"
                  id="copyLink"
                  class="
                      flex
                      items-center
                      justify-center
                      h-8
                      min-w-14
                     
                      text-dinesurf-green-500
                      cursor-pointer
                  "
                  @click="copyToClipboard('ctn-link')"
              >
                  <i class="far fa-copy  "></i>
              </button>   
              <share-button :id="this.vendor.company_name" :menu="true" class="mt-0 "></share-button>
     
              <a :href="this.barcodeLink"  id="qrcodeImage"   class="
                      flex
                      items-center
                      justify-center
                      h-8
                      min-w-14
                      text-dinesurf-green-500
                      cursor-pointer
                  "    download="Barcode.png" target="_blank">   
                  
                     <img :src="this.barcodeLink"    width="120"  height="120"   />
                </a> 
            </div>
  
  
          </div>
  
          <input
                  type="text"
                  :value="this.menuPageLink"
                  name="ctn-link"
                  placeholder="copy link"
                  class="opacity-0 h-1 focus:outline-none"
                  id="ctn-link"
              />
  
  
      <data-table
        :models="models"
        :loading="loading"
        :loaded="loaded"
        :title="'Menu'"
        :filters="filters"
        :columns="columns"
        :actions="actions"
        :size="size"
        :rows="rows"
        @runAction="fireAction"
        @loadData="loadData"
        :createLink="route('vendors.create-menu-page')"
        :indexLink="route('vendors.menus')"
        :viewLink="route('vendors.edit-menu-page')"
        :externalFilters="{
          approved: approved,
          birthday: birthday,
          birthday_date: birthday_date,
        }"
      />
  
    <!-- menu notes -->
    <menu-popup  :show="this.menuBoxShow"   @close=" this.menuBoxShow = !this.menuBoxShow" />
  
    <category-popup  :show="this.menuCategoryBoxShow"   @close="this.menuCategoryBoxShow = !this.menuCategoryBoxShow"  />
    <item-popup  :show="this.menuItemBoxShow"   @close="this.menuItemBoxShow = !this.menuItemBoxShow"  />
    
    
    </vendor-layout>
  </template>
  
  <script>
  import VendorLayout from "@/Layouts/VendorLayout.vue";
  import tippy from "tippy.js";
  import "tippy.js/dist/tippy.css"; // optional for styling
  
  
  export default {
    components: {
      VendorLayout,
    },
    props: {
      title: String,
      filters: Object,
    },
    data() {
      return {
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
        menuPageLink:  route('menu', { id : this.$page.props.auth.vendor.company_name }),
        barcodeLink:''
      };
    },
    computed: {
      rows() {
        var modelRows = [];
  
        this.models.data.forEach((el) => {
          modelRows.push([
            el.id,
            el.name,
            el.description
          ]);
        });
  
        return modelRows;
      },
    },
    methods: {
  
      openMenuBox(){
        
        const menuvendorId = localStorage.getItem('menuvendorId');
          if(!menuvendorId  && menuvendorId  != this.$page.props.auth.vendor.id){
            this.menuBoxShow = true;
             this.showingSidebar = false;
          }
        },
  
      openCategoryBox(){
        
        const menucate = localStorage.getItem('menuCategoryvendorId');
          if(menucate  && menucate  == this.$page.props.auth.vendor.id){
            this.$inertia.get( route("vendors.menu-categories"));
          }else{
             this.menuCategoryBoxShow = true;
          }
        },
  
        openItemBox(){
        
        const menuItem = localStorage.getItem('menuItemvendorId');
          if(menuItem && menuItem == this.$page.props.auth.vendor.id){
            this.$inertia.get( route("vendors.menu-category-items"));
          }else{
             this.menuItemBoxShow = true;
          }
        },
     
  
      copyToClipboard(id) {
            var copyText = document.getElementById(id);
              copyText.select();
              document.execCommand("copy");
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
  
    },
  };
  </script>
  