<template>
  <vendor-layout>
          <template #header>
               <header-text :title="title"  />
          </template>

          <div  class="w-full  flex md:flex-row  flex-col md:p-10 px-5 justify-between">

             <div class="flex justify-between  md:flex-row  flex-col ">
                      <!-- <input type="search" placeholder="Search event" v-model="search" @keyup="filterData()" class="search block text-gray-400 text-sm h-9 w-full form-control "  style=" padding-left:30px"  /> -->
                      <div
                            class="
                              relative
                              rounded-lg
                              text-gray-600
                              focus-within:text-gray-400
                              mb-4
                              w-full
                              mr-4
                            "
                          >

                    <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                      <button
                        type="button"
                        class="p-1 focus:outline-none focus:shadow-outline"
                      >
                        <svg
                          fill="none"
                          stroke="currentColor"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          viewBox="0 0 24 24"
                          class="w-6 h-4"
                        >
                          <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                      </button>
                    </span>
                      <input
                        type="search"
                        name="q"
                        class="
                          pl-10
                          block
                          rounded-md
                          border-gray-300
                          shadow-sm
                          focus:ring-indigo-500 focus:border-indigo-500
                          sm:text-xs
                          lg:text-sm
                          w-full
                          h-9
                          
                          
                        "
                        autocomplete="off"
                        placeholder="Search event" v-model="search" @keyup="filterData()"
                      />

                  </div>
               
           
                <div class="w-full  md:mb-0 mb-5">
                   <select v-model="event_type"  @change="filterData()" name="event_type" class=" pb-1 form-control placeholder-red-400  bg-white w-full   text-black">
                    <option selected :value="null">-- Filter Event--</option>
                    <option value="live">Live Events</option>
                    <option value="past" >Past Events</option>
                    <option value="upcoming">Upcoming Events</option>
                   </select>
                </div>

                <div class="md:ml-3  md:mb-0 mb-5">
                  <Link  :href="route('vendors.events')"  class=" h-9 text-center justify-center bg-dinesurf-green-400 text-white flex items-center md:w-20 p-2 rounded ">
                        
                         <span class="text-center">
                            Show all
                         </span>
                    </Link  >
                </div>
              </div>






                <div class="w-auto">
                     <Link  :href="route('vendors.create-event-page')"  class="btn  justify-center  h-9 bg-dinesurf-green-400 text-white flex items-center p-2 rounded w-full">
                         <div class="mr-1">

                          <create-icon />
                          
                         </div>
                         <span>
                            Create Event
                         </span>
                    </Link  >

                </div>
          </div>


          <div class="flex flex-wrap p-5" >
            <div class="items-center flex justify-center content-center w-full" v-if="loading">
                    <span class="text-2xl  text-center">Loading Events</span>
                    <loading-icon />
               </div>
               <event-card  v-for="event in data"  :key="event.id" :event="event"   @onDelete="fireAction"/>

               <div class="items-center flex flex-col justify-center  w-full mt-10" v-if="loaded && params && data.length === 0 ">
                  
             

                <empty-space 
                  :title="' Oops!... Its Empty'"
                  :description="'Dinesurf events are all open to the diners with a well organized detail page for each event that you can share.'"
                  :page="'event'"
                  :link="route('vendors.create-event-page')"
                  :image="'/images/empty_images/4.png'"
                    />
                
               </div>
          </div>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import EventCard from "./EventCard.vue";

export default {
 components: {
    VendorLayout,
    EventCard
  },
props: {
    title: String,
  },

  data(){
    return {
      params: null,
      loading: false,
      loaded: false,
      data: [],
      search: null,
      event_type: null
    }
  },

  methods:{


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
          route("vendors.event-action") + "?without_async=true",
          {
            ids: ids,
          },
          {
            onFinish: (visit) => {
              this.loadData();
            },
          }
        ); 
      }

           
   },

    loadData(){
  
      const urlSearchParams = new URLSearchParams(window.location.search);
      this.params = Object.fromEntries(urlSearchParams.entries());

      this.loaded = false;
      this.loading = true;
     

      axios
        .get(route("data.vendors.events", { ...this.params }))
        .then((result) => {
          this.data = result.data.models.data;
          this.vendor = result.data.vendor;
        })
        .catch()
        .finally(()=> {
         this.loading = false;
          this.loaded = true;
        })
        ;
    },


    filterData() {
      this.loading = true
      this.$inertia.get(
        route('vendors.events'),
        {
          search: this.search,
          sort: this.event_type
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
  },

  

  mounted(){
    this.loadData();
    console.log(this.params?.search  )
  }
}
</script>

