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
                                    <Link :href="route('reservations')" class="   mr-5 flex justify-between ">
                                        <span class="text-xs font-bold  mb-3">Restaurant Reservations</span>
                                    </Link>
                                     <div class="flex justify-between ">
                                        <div   class="border-b-2 border-red-500 cursor-pointer">
                                        <span class="text-xs font-bold text-red-500 mb-3"> Event Reservations</span>
                                        </div>
                                     </div>
                            </div>   



                            <div class="cardContainer  border mt-10">
                              <div class=" w-full flex  justify-between border-b-2 p-5 ">
                                  <h4 class="font-bold">  Reservations ({{ reservationsData.length }})</h4>
                                  <!-- <Link 
                                      class="ml-5 see-all-link" 
                                      :href="route('all-reservations', { name: 'upcoming' })">
                                       <i class="fas fa-angle-down"></i>
                                  </Link> -->
                            
                              </div>

                                 <template v-if="reservationsData.length > 0">
                                  <div
                                        class="w-full"
                                        v-for="(
                                            reservation, index
                                        ) in reservationsData"
                                        :key="index">
                                       <ReservationComp 
                                       :reservation="reservation"
                                ></ReservationComp>
                            </div>
                        </template>
                        <div class="w-full  p-20 text-center" v-else>
                            No  Reservations found
                        </div>

                             
                              
                           </div>
                       </div>

        </div>

       <Footer />
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import ReservationComp from "@/components/EventReservation.vue";
import Footer from "@/components/Footer.vue"
// import SingleReservation from "@/page/Reservations/Home"

export default {
    props: [
        "reservations",
    ],
    data() {
        return {
            reservationsData: this.reservations,
        };
    },
    components: {
        AppLayout,
        ReservationComp,
        Footer
    },
    mounted() {
        document.title = "Reservations - Dinesurf";

        console.log("reservations:", this.reservations)
    },
};
</script>
