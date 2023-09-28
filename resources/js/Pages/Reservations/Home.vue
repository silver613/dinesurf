<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reservations
            </h2>
        </template>



         <div class="bg-white  " style="background-color:white">
      

                        <div class="w-auto paddingLR    mb-20 pt-10">
                            <div class="border-b  flex">
                                    <div class="border-b-2 border-red-500   mr-3  flex justify-between ">
                                        <span class="text-xs font-bold text-red-500 mb-3">Restaurant Reservations</span>
                                     </div>
                                     <div class="flex justify-between ">
                                        <Link :href="route('event-reservations')"   class="cursor-pointer">
                                        <span class="text-xs font-bold text mb-3"> Event Reservations</span>
                                       </Link>
                                     </div>
                            </div>  

                          <div class="cardContainer  border mt-10">
                              <div class=" w-full flex  justify-between border-b-2 p-5 ">
                                  <h4 class="font-bold">   Upcoming Reservations ({{ upcoming_reservations_count }})</h4>
                                  <!-- <Link 
                                      class="ml-5 see-all-link" 
                                      :href="route('all-reservations', { name: 'upcoming' })">
                                       <i class="fas fa-angle-down"></i>
                                  </Link> -->
                            
                              </div>

                                 <template v-if="upcoming_reservationsData.length > 0">
                                  <div
                                        class="w-full"
                                        v-for="(
                                            reservation, index
                                        ) in upcoming_reservationsData"
                                        :key="index">
                                       <ReservationComp 
                                       :reservation="reservation"
                                ></ReservationComp>
                            </div>
                        </template>
                        <div class="w-full  p-20 text-center" v-else>
                            No Upcoming Reservations found
                        </div>

                             
                              
                           </div>
                       </div>






                       <!-- past reservation -->


                       
                        <div class="w-auto paddingLR    mb-20 pt-10">
                           

                          <div class="cardContainer  border mt-10">
                              <div class=" w-full flex justify-between border-b-2 p-5 ">
                                  <h4 class="font-bold">  Past Reservations ({{ past_reservations_count }})</h4>
                                    <!-- <Link
                                      class="ml-5 see-all-link"
                                      :href="route('all-reservations', { name: 'upcoming' }) ">
                                          See all  <i class="fas fa-angle-right"></i>
                                    </Link> -->
                              </div>

                               <template v-if="past_reservationsData.length > 0">
                                    <div
                                        class="w-full"
                                        v-for="(
                                            reservation, index
                                        ) in past_reservationsData"
                                        :key="index"
                                    ><ReservationComp 
                                       :reservation="reservation"
                                ></ReservationComp>
                                    </div>
                                </template>
                                <div class="w-full p-20 text-center" v-else>
                                    No Past Reservations found
                                </div>

                             
                              
                           </div>
                       </div>
                             
                 
                    
                 </div>

       
             
       <footer />
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import ReservationComp from "@/components/Reservation.vue";
import Footer from "@/components/Footer.vue"
// import SingleReservation from "@/page/Reservations/Home"

export default {
    props: [
        "reservations",
        "upcoming_reservations",
        "past_reservations",
        "upcoming_reservations_count",
        "past_reservations_count",
    ],
    data() {
        return {
            reservationsData: this.reservations,
            upcoming_reservationsData: this.upcoming_reservations,
            past_reservationsData: this.past_reservations,
        };
    },
    components: {
        AppLayout,
        ReservationComp,
        Footer
    },
    mounted() {
        document.title = "Reservations - Dinesurf";
    },
};
</script>
