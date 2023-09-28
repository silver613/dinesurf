<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ resource_name }}
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-if="vendors">
                <div
                    v-if="vendors.data"
                    class="
                        flex flex-col
                        restaurant-comp
                        bg-white
                        overflow-hidden
                        shadow-xl
                        py-3
                        px-4
                        sm:rounded-lg
                    "
                >
                    <div
                        class="
                            flex flex-col
                            md:flex-row
                            flex-wrap
                            justify-around
                        "
                    >
                        <template v-if="vendors.data.length > 0">
                            <div
                                class="w-full md:w-96 m-1"
                                v-for="(item, index) in vendors.data"
                                :key="index"
                            >
                                <single-restaurant
                                    :restaurant="item"
                                ></single-restaurant>
                            </div>
                        </template>
                        <div class="w-full" v-else>
                            No Favourite Restaurants found
                        </div>
                    </div>
                    <pagination class="mt-6" :links="vendors.links" />
                </div>
                <div
                    v-else
                    class="
                        flex flex-col
                        restaurant-comp
                        bg-white
                        overflow-hidden
                        shadow-xl
                        py-3
                        px-4
                        sm:rounded-lg
                    "
                >
                    <div
                        class="
                            flex flex-col
                            md:flex-row
                            flex-wrap
                            justify-around
                        "
                    >
                        <!-- <template v-if="vendors.length > 0"> -->
                            <div
                                class="w-full md:w-96 m-1"
                                v-for="(item, index) in vendors"
                                :key="index"
                            >
                                <single-restaurant
                                    :restaurant="item"
                                ></single-restaurant>
                            </div>
                        <!-- </template>
                        <div class="w-full" v-else>
                            No {{ resource_name }} found
                        </div> -->
                    </div>
                    <!-- <pagination class="mt-6" :links="vendors.links" /> -->
                </div>
            </div>
        </div>




          <!-- <div class="bg-white  " style="background-color:white">
      

                        <div class="w-auto paddingLR    mb-20 pt-10">
                            <div class="border-b">
                                    <div class="border-b-2 border-red-500 w-32   flex justify-between ">
                                        <span class="text-xs font-bold text-red-500 mb-3">Favorite Restaurants</span>
                                     </div>
                            </div>  

                           <div class="cardContainer  border mt-10">
                              <div class=" w-full border-b-2 p-5  ">
                                  <h4>Favorite  Restaurants</h4>
                              </div>

                               <template v-if="vendors.data.length > 0">
                                 

                               <div
                                v-for="(item, index) in vendors.data"
                                :key="index">
                                
                                   <favorite  :vendor="item"> </favorite>
                               </div>
                               </template>

                               <div class="w-full min-h-full p-20 text-center" v-else>
                                    No Favorite Founds
                                </div>
                              
                            </div>
                       </div>
                              
                    
                 </div> -->
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";

export default {
    props: ["resource_name", "vendors"],
    components: {
        AppLayout,
        Welcome,
    },
    mounted() {
        // console.log("vendors: ", this.vendors);
    },
    mounted() {
      console.log('notin',this.vendors);
        document.title = "Favourite Restaurants - Dinesurf";
    },
};
</script>
