<template>
    

       <div  class="flex flex-wrap w-full md:flex-row flex-col space-x-4 items-center justify-center md:px-20  px-5">
           <!-- <event-report-card 
             :title="' Target Revenue'"
             :value="numberWithCommas(revenue, 'NGN')"
           /> -->
           <event-report-card 
             :title="'Seats Left'"
             :value="this.seatLeft"
           />
           <event-report-card 
             :title="'Revenue Generated'"
             :value="numberWithCommas(this.payoutReceived, 'NGN')"
           />

           <!-- <event-report-card 
             :title="'Total payout'"
             :cap="'Revenue generated - 2% processing fee'"
             :value="numberWithCommas(this.totalPayout, 'NGN')"
           /> -->
       </div>

<!-- 
       <div class="w-full  md:px-20 px-5 mt-10">
        <span class="text-xl font-bold">
            Occupancy Percentage
        </span>
          <div class="bg-[#F4F4F4] h-3 w-[100%] mt-4  rounded">
             <div  :class="`bg-[#424242] h-3  relative rounded`" :style="`width:${goal}%`">
                <span  class="absolute w-1 bottom-0 -top-4 h-10 bg-[#BEBEBE] right-0"></span>
                <span class="absolute top-6 text-center font-bold    -right-3">Goal</span>
             </div>
          </div>
       </div> -->


       <div class="overflow-hidden md:pl-10  md:w-[60rem]  mt-20 md:pr-10 sm:rounded-lg">
      <div class="flex justify-center
                items-center">
        <div class="overflow-x-auto -my-2 -mx-2 lg:-mx-4">
          <div class="inline-block py-2  align-middle px-3 ">
            <div
              class="
                 border-gray-200
                sm:rounded-lg
                flex 
                justify-center
                items-center
                w-full
                
              "
            >
              <table  class="table table-auto  ">


                  <thead class="border-b  text-gray-400 ">
                    <tr>
                      <th
                        scope="col"
                        class="
                          pl-2
                          py-3
                          px-10
                          text-xs
                          font-semibold
                          tracking-wider
                          text-left text-black
                          capitalize
                        "
                        v-for="(column, index) in columns"
                        :key="index"
                      >
                      {{ column }}
                      </th>
                    </tr>
                </thead>

                <tbody>
                      <tr   v-for="(item, index) in reservations"
                        :key="index">
                          <td  class="
                            py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{ item.user.email }}</td>
                          <td  class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{ item.user.name }}</td>
                            <td  class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{ item.reserve_seat > 1 ? item.reserve_seat+' guests' : item.reserve_seat+ ' guest' }}</td>
                            <td  class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{ this.getFormatted(item.event_date) }}</td>
                            <td  class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{   new Date("1970-01-01T" + item.event_time).toLocaleString('en-US', { hour: 'numeric',minute: 'numeric', hour12: true }) }}</td>





                            <td  v-if="this.event.payment !== 'free' " class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{ numberWithCommas( item.total_price, "NGN") }}</td>

                            <td  v-if="this.event.payment !== 'free' " class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{ getPackageName(item) }}</td>

                            <td  v-if="this.event.payment !== 'free' " class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{ item.payment_ref }}</td>

                            <td  v-if="this.event.payment !== 'free' " class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{ item.transaction_ref }}</td>
                      
                            <td  v-if="this.event.payment !== 'free' " class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{ item.payment_status }}</td>
                      
                            <td  class="py-4
                            px-2
                            text-left
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap">{{  this.getFormatted(item.created_at)  }}</td>
                      
                    
                      
                          </tr>

                          <template v-if="reservations.length <= 0"  >
                            <tr>
                              <td class="py-2 px-2  text-center " :colspan="columns.length + 2">
                                No Reservation  Available
                              </td>
                            </tr>
                         </template>

                        
                </tbody>
              
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    


</template>

<script>

import EventReportCard from '@/components/vendor/EventReportCard.vue';
import moment from 'moment';
export default {
  props:['event', 'vendor'],

    components:{
        EventReportCard
    },

    data(){
        return{
     
          goal: this.event?.occupancy_goal,
          eventData: this.event,
          eventCapacity: this.event.slot === 'per_couple' ? this.event.capacity / 2 : this.event.capacity, 
          revenue:  0,
          columns:  this.event.payment === 'free' ?  ["Email", "Fullname", "Party Size", "Reserved Date", "Reserved Time", "Booked Date" ] : ["Email", "fullname", "Party Size", "Reserved Date", "Reserved Time", "Amount Paid","Package", "Reference ID", "Transaction ID", "Payment Status", "Booked Date" ],
          reservations: this.event?.reservations,
          payoutReceived: 0,
          seatLeft: this.event.capacity, 
          totalPayout: 0,
          // package: this.event?.reservations?.package ? JSON.parse(this.event?.reservations?.package) : null,
          eventPrice: this.event.payment === 'free' ? 0 : this.event.price,
      
        }
    },

    methods:{

         getPackageName(item) {
          if(item.package ===  null) return;
          return JSON.parse(item.package).name;
         }, 
        getProcess(){

          if(this.event.payment  !=='free'){
              const amountList = this.reservations?.map((item) => Number(item.total_price));
              const total  =  amountList.reduce((a, b) => a + b, 0);
              this.payoutReceived = total;


              }

              if(this.event.payment === 'paid'){
                // totalReservedSeat
                const amountList = this.reservations?.map((item) => Number(item.total_price));
                const total  =  amountList.reduce((a, b) => a + b, 0);
                const processFee = 0.02 *  total;
                const totalprocessFee = processFee > 2000 ? 2000 : processFee;
                const totalPayout = total - totalprocessFee;

                this.totalPayout =  totalPayout;

              }
        
              // seat left
              const reservedList = this.reservations?.map((item) => Number(item.reserve_seat));

              const  totalReservedSeat = reservedList.reduce((a,b) => a + b, 0);
              const left = this.eventCapacity - totalReservedSeat;

              this.seatLeft =  left + '/' +  this.eventCapacity;




             // revenue

           this.revenue = this.event?.payment !=='free' ? Number(this.eventPrice) * Number(this?.eventCapacity) : 0 ;
          
        },
        getFormatted(date){
        var m  = moment(date, "YYYY-MM-DD");  // explicit input format
         return m.format("ddd MMM DD YYYY");
      },
    }, 
    mounted(){

      this.getProcess();
        // console.log("event reservations:", this.reservations)

        // console.log("package:", this.package);
        // console.log("eventPrice:", this.eventPrice);
        // console.log("this.event:", this.event.reservations)
    }
    
}
</script>