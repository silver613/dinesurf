<template >
    <div
      class="
       bg-white
        md:m-3
        mt-3
       rounded-lg
      border
      md:w-auto
      w-full
      border-t-4
    
      
      "
      :class=" order.status  === 'in-progress' || order.status  === 'pending' || order.status === 'open' ? 'border-t-amber-400' :  order.status === 'completed' || order.status === 'close' || order.status === 'closed' ? 'border-t-lime-400' : 'border-t-gray-400'"
      >
      <div class="px-3">
          <div class="flex justify-between pt-3 space-x-3">
                 <div class="rounded-full  w-[3rem] h-[3rem]  flex items-center justify-center"   :class=" order.status  === 'in-progress' || order.status  === 'pending' || order.status === 'open' ? 'bg-amber-400' :  order.status === 'completed' || order.status === 'close' || order.status === 'closed' ? 'bg-lime-400' : 'bg-gray-400'">
                     <span class="text-[8px]">Order {{  this.order.id }}</span>
                 </div>
                 <div class="flex flex-col">
                       <span class="text-slate-500">Table: {{ order.table_number }}</span>
                       <span class="text-gray-300">Customer: {{ order.name.length > 5 ? order.name.substr(0, 5) + '..' : order.name }}</span>
                       <!-- <span class="text-gray-300">Served By: </span> -->
                 </div>

                 <div class="flex items-start justify-start flex-col space-y-1">
                    <!-- <jet-dropdown align="right" class="w-full">
                    <template #trigger>
                       <button
                            type="button"
                            class="
                            inline-flex
                            items-center
                            border border-transparent
                            text-xs
                            md:text-sm
                            leading-4
                            font-normal
                            px-1
                            rounded-md
                            text-gray-500
                            bg-amber-50
                            hover:text-gray-700
                            focus:outline-none
                            transition
                            "
                        >
                         
                            <svg
                            class="ml-2 -mr-0.5 h-4 w-4 text-amber-400"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                            </svg>
                        </button>
                    </template>

                  <template #content>
                     <div class="py-3 flex flex-col  space-y-1">
                          <span class="underline hover:bg-gray-200  w-full  cursor-pointer p-1 px-3">In progress</span>
                          <span class="hover:bg-gray-200  w-full  cursor-pointer p-1 px-3">Completed</span>
                          <span class="hover:bg-gray-200  w-full  cursor-pointer p-1 px-3">Closed</span>
                          <span class="hover:bg-gray-200  w-full  cursor-pointer p-1 px-3">Open</span>
                     </div>
                  </template>
                </jet-dropdown> -->
                <span class=" capitalize"   :class=" order.status  === 'in-progress' || order.status  === 'pending' || order.status === 'open' ? 'text-amber-400' :  order.status === 'completed' || order.status === 'close' || order.status === 'closed' ? 'text-lime-400' : 'text-gray-400'">{{ order.status }}</span>
                 <span class="text-gray-400 text-xs font-semibold">Order total: ₦{{ nFormatter(order.amount) }}</span>    
                 </div>
          </div>

          <div  class="border-t  pt-5 pb-4 mt-2">
                <div class="flex justify-between">
                    <span class="font-normal text-[12px] text-slate-500">Amount Paid</span>
                    <span class="font-semibold text-[#383838]  text-[12px]">₦{{ nFormatter(order.amount) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-normal text-[12px] text-slate-500">Balance</span>
                    <span class="font-semibold text-[#383838]  text-[12px]">₦{{ nFormatter(order.amount) }}</span>
                </div>
          </div>
         
         </div>
         
         
         
          <div class="bg-slate-800 flex justify-between px-5 p-3">
                <div class="flex space-x-2">
                         <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.66667 6.625C4.7474 6.625 4 7.35371 4 8.25V14.75C4 15.6463 4.7474 16.375 5.66667 16.375H17.3333C18.2526 16.375 19 15.6463 19 14.75V8.25C19 7.35371 18.2526 6.625 17.3333 6.625H5.66667ZM7.33333 14.75H5.66667V13.125C6.58594 13.125 7.33333 13.8537 7.33333 14.75ZM5.66667 9.875V8.25H7.33333C7.33333 9.14629 6.58594 9.875 5.66667 9.875ZM15.6667 14.75C15.6667 13.8537 16.4141 13.125 17.3333 13.125V14.75H15.6667ZM17.3333 9.875C16.4141 9.875 15.6667 9.14629 15.6667 8.25H17.3333V9.875ZM11.5 9.0625C12.163 9.0625 12.7989 9.31931 13.2678 9.77643C13.7366 10.2335 14 10.8535 14 11.5C14 12.1465 13.7366 12.7665 13.2678 13.2236C12.7989 13.6807 12.163 13.9375 11.5 13.9375C10.837 13.9375 10.2011 13.6807 9.73223 13.2236C9.26339 12.7665 9 12.1465 9 11.5C9 10.8535 9.26339 10.2335 9.73223 9.77643C10.2011 9.31931 10.837 9.0625 11.5 9.0625Z" fill="#F8FAFC"/>
                            <rect x="0.479167" y="0.479167" width="22.0417" height="22.0417" rx="11.0208" stroke="white" stroke-width="0.958333"/>
                         </svg>
                     <span class="text-white">₦ {{ nFormatter(order.amount) }}</span>
                </div>

                <div>
                    <button  @click="seeDetails()"  class="text-white   flex space-x-1 items-center">
                       <span class="underline">View details</span> 
                        <i class="fa fa-angle-right" ></i>
                    </button>
                </div>
          </div>
      </div>
 
  </template>
  <script>

import JetDropdown from "@/Jetstream/Dropdown.vue";

  export default {

    components:{
        JetDropdown
    },
    props: ['order'],
    data() {
      return {
       };
    },

    methods:{
    seeDetails(){
        this.$emit("openOrder")
      }
    },
  
    mounted() {

    },
  };
  </script>
 