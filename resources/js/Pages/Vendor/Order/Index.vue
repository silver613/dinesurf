<template>
    <vendor-layout>
      <template #header>
          <header-text :title="'Order Management'"  />
      </template>
  
      <!-- <template   #rightContent>
          <div class="flex relative items-center justify-center  md:pr-8  pr-3">
            <span class="text-[#00000080] ">Live</span>
            <label class="relative inline-flex items-center cursor-pointer mx-5 mb-5"  >
                 <input  type="checkbox"  value="" class="sr-only peer" />
                 <div class="w-11 h-6 mt-5 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-dinesurf-green-300 dark:peer-focus:ring-dinesurf-green-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1.4rem] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-dinesurf-green-600"></div>
            </label>                                   
         </div>
      </template> -->
  
  

               
    <div class="bg-white md:px-8  px-4  flex  flex-col md:flex-row">
      <ul
        class="
         flex
          spacing-x-5
          w-full
          mt-5 md:mt-0
        "
      >
        <li class=" mr-4" v-for="navigation in navigations">
          <a
            href="javascript:void(0)"
            @click="goToView(navigation.route)"
            class="
              inline-block
              w-full
              focus:outline-none
              rounded-none
              text-xs
              md:text-sm
              pb-2
              font-normal
            "
            :class="
              view == navigation.route
                ? 'text-dinesurf-green-400 active border-b-2    border-b-dinesurf-green-400'
                : 'text-gray-400 '
            "
            aria-current="page"
            >{{ navigation.name }} </a
          >
        </li>
      </ul>
     


        <div  class="flex  flex-col  md:mt-0  mt-8  md:w-[25rem]">
            <div class="flex relative  ">
              <div class="w-full">
                  <input type="text" placeholder="Search order" v-model="search" @keyup="filterData()" class="search block text-gray-400 text-sm  w-full form-control "  style=" padding-left:30px"  />
              </div>
              <span class="absolute inset-y-0 left-0 flex items-center ">
                    <button type="submit"  class="p-1 focus:outline-none focus:shadow-outline" >
                        <svg
                            fill="none"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            class="w-6 h-4 text-gray-400">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
               </span>
               <div class="absolute inset-y-0 right-2 flex items-center  border-l-2  py-2 my-2">
                   <button class="px-2">
                           <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M0.843384 2.14246C2.69505 4.51663 6.11422 8.91663 6.11422 8.91663V14.4166C6.11422 14.9208 6.52672 15.3333 7.03088 15.3333H8.86422C9.36838 15.3333 9.78088 14.9208 9.78088 14.4166V8.91663C9.78088 8.91663 13.1909 4.51663 15.0426 2.14246C15.1476 2.00713 15.2126 1.84503 15.23 1.6746C15.2474 1.50417 15.2167 1.33227 15.1412 1.17847C15.0657 1.02466 14.9486 0.895145 14.8031 0.804655C14.6577 0.714164 14.4897 0.66634 14.3184 0.666627H1.56755C0.806718 0.666627 0.375884 1.53746 0.843384 2.14246Z" fill="black"/>
                            </svg>
                   </button>
               </div>
           </div>
        </div>
    </div>


    <div v-if="view === 'reports'">
         <div>
              <span>Hello there!</span>
         </div>
    </div>

   <div  v-else class="flex flex-wrap px-5 mt-5">
      <div v-if="this.loading"  class="flex items-center w-full justify-center">
        <span class="px-7 text-3xl py-8">Loading Orders</span>
         <loading-icon />
      </div>
      <div  v-for="(model) in models.data" :key="model.id" >
             <order-card :order="model"   @openOrder="oderDetails(model)"/>    
       </div> 
     

       <div  v-if="this.loaded && models.data.length === 0" class="flex w-full items-center content-center justify-center mt-10" >
               
               <empty-space 
                 :title="'Oops!... Its Empty'"
                 :description="'all your orders will appear here'"
                 :page="'order'"
                 :image="'/images/empty_images/4.png'"
                 :noButton="true"
                   />
        </div> 


       <div  class=" w-full flex  items-center justify-center ">
        <div class="bg-white p-5 pt-0"> 
          <pagination class="mt-10 pl-5 " :links="models.links" v-if="models.data.length > 5" />
        </div>     
     </div>
    </div>





    <jet-modal :show="showDetailsModal" @close="showDetailsModal = !showDetailsModal"  :maxWidth="'sm'">
         <div class="flex  flex-col justify-center w-full items-center mt-10 mb-10">
          <div class="">

            <div class="rounded-full  w-[5rem] h-[5rem] mb-3 flex items-center justify-center"   :class=" singleOrder.status  === 'in-progress' || singleOrder.status  === 'pending' || singleOrder.status === 'open' ? 'bg-amber-400' :  singleOrder.status === 'completed' || singleOrder.status === 'close' || singleOrder.status === 'closed' ? 'bg-lime-400' : 'bg-gray-400'">
                     <span class="text-[8px]">Order {{ singleOrder.id }}</span>
             </div>
            <div class="flex  space-x-10 justify-start w-full">
                <span>Status:</span>
                <span class=" capitalize"   :class=" singleOrder.status  === 'in-progress' || singleOrder.status  === 'pending' || singleOrder.status === 'open' ? 'text-amber-400' :  singleOrder.status === 'completed' || singleOrder.status === 'close' || singleOrder.status === 'closed' ? 'text-lime-400' : 'text-gray-400'">{{ singleOrder.status }}</span>
                
            </div>
            <div class="flex  space-x-10 justify-start w-full">
                <span class="font-bold">Name:</span>
                <span>{{ singleOrder?.name }}</span>
            </div>
            <div class="flex  space-x-10 justify-start w-full">
                <span class="font-bold">Email:</span>
                <span>{{ singleOrder?.email }}</span>
            </div>
            <div class="flex  space-x-10 justify-start w-full">
                <span class="font-bold"> Table:</span>
                <span>{{ singleOrder?.table_number }}</span>
            </div>
            <div class="flex  space-x-10 justify-start w-full">
                <span class="font-bold">Order Amount:</span>
                <span>{{ singleOrder?.amount }}</span>
            </div>

           
        </div>
                  
         </div>
    </jet-modal>

  </vendor-layout>
 </template>
  
  <script>
  import VendorLayout from "@/Layouts/VendorLayout.vue";
  import OrderCard from "@/components/vendor/OrderCard.vue";
  export default {
    components: {
      VendorLayout,
      OrderCard
    },
    props: {
      title: String,
   },
    data() {
      return {
        showDetailsModal: false,
        view: '',
        navigations: [
            {
                name: "In-Kitchen",
                route: 'in-kitchen'
            },
            {
                name: "Order History",
                route: 'order-history'
            },
            {
                name: "Invoices",
                route: 'invoices'
            },
            // {
            //     name: "Reports",
            //     route: 'reports'
            // }
        ],
        models: {
        data: [],
        links: [],
        total: 0,
      },
      loading: false,
      loaded: false,
      search:null,
      params: {},
      singleOrder: null
      }
    },

    computed: {

    },
    methods: {

      oderDetails(order){
        this.showDetailsModal = !this.showDetailsModal
        this.singleOrder = order;
          // console.log("order:", order);
      },
        goToView(view) {   
            
            this.$inertia.get(
                route("vendors.management.order"),
                {
                tab: view,
                },
                {
                replace: true,
                }
            );
        },
          
    filterData() {
      this.loading = true
      this.$inertia.get(
        route("vendors.management.order"),
        {
          tab: this.view,
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

    load(){
            const urlSearchParams = new URLSearchParams(window.location.search);
            this.params = Object.fromEntries(urlSearchParams.entries());
            this.view = this.params.tab ?  this.params.tab : 'in-kitchen';
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
        .get(route("data.vendors.orders", { ...this.params }))
        .then((result) => {
          this.models = result.data.models;
          this.loaded = true;
          this.loading = false;

        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
    },
    mounted() {
        this.load();
        this.loadData();
    },
  };
  </script>