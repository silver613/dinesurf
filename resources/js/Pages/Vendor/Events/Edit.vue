<template>
    <vendor-layout>
       <template #header>
            <header-text :title="title"  />
       </template>
   
       <div class="bg-white md:pl-8  pl-4  flex md:justify-center  flex-col md:flex-row">
      <ul
        class="
          text-base
          font-bold
          text-center text-gray-500
          rounded-lg
          flex
          spacing-2
          dark:divide-gray-700 dark:text-gray-400
          md:w-[45%]
          w-ful
          mt-5 md:mt-0
        "
      >
        <li class="w-full mr-4">
          <a
            href="javascript:void(0)"
            @click="goToView('details')"
            class="
              inline-block
              w-full
              focus:outline-none
              rounded-none
              text-xs
              md:text-sm
              pb-2
            "
            :class="
              view == 'details'
                ? 'text-black active border-b-2 font-bold  border-b-black'
                : 'text-gray-400 font-light'
            "
            aria-current="page"
            >Details </a
          >
        </li>
        <li class="w-full mr-4  ">
          <a
            href="javascript:void(0)"
            @click="goToView('report')"
            class="
            inline-block
              w-full
              focus:outline-none
              rounded-none
              text-xs
              md:text-sm
              pb-2
            "
            :class="
              view == 'report'
                ? 'text-black active border-b-2 font-bold  border-b-black'
                : 'text-gray-400 font-light'
            "
            >Report</a
          >
        </li>
     </ul>
   </div>

   <div v-if="view === 'details'">
       <vendor-edit-event :event="eventData"  :vendor="this.vendor"/>
   </div>

   <div v-if="view === 'report'"  class="mt-16">
       <vendor-event-report :event="eventData"  :vendor="this.vendor" />
       
   </div>



</vendor-layout>
</template>




<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import VendorEditEvent from "@/components/vendor/VendorEditEvent.vue";
import VendorEventReport from "@/components/vendor/VendorEventReport.vue";
export default {
components: {
 VendorLayout,
 VendorEditEvent,
 VendorEventReport
},
props: {
 title: String,
 event: Array,
 vendor: Array
},

data(){
        return{
            view: "details",
            params:{},
            eventData: this.event
        }
    },

methods:{
        goToView(view) {
        
          this.$inertia.get(
                  route('vendors.edit-event-page') + '/' + this.eventData.id,
                    {
                    view: view,
                    },
                    {
                    replace: true,
                    }
              );
         },

       load(){
            const urlSearchParams = new URLSearchParams(window.location.search);
            this.params = Object.fromEntries(urlSearchParams.entries());
            this.view = this.params.view ?  this.params.view : 'details';

       }
    },

    mounted(){
        this.load();
        console.log("eventData:", this.eventData);

    }
}
</script>