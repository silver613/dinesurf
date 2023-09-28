<template>
  <div v-if="plans" class="mt-10">
    <!-- <h5 class="text-left">Plans</h5> -->
    <div class="shadow-mp-4 mt-6 pt-0 ">
       <div  class="flex  mt-4 justify-center items-center w-full">
          <span class="font-normal text-2xl  ">Start from here, choose a plan</span>
       </div>
       <div class="flex  space-x-3 mt-3 mb-3 justify-center items-center w-full  ">

            <div class="mr-5 cursor-pointer"  v-for="(plan, index)  in this.sPlans " :key="index" @click="planSelected = plan">
                   <label class="pr-2  text-sm  text-gray-400">
                          {{plan.name}} 
                   </label>
                      <input
                        class="
                           border-dinesurf-green-600 bg-white
                          checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600
                          focus:border-dinesurf-green-600 focus:bg-dinesurf-green-600
                          radio
                        "
                         :value="plan"
                         :checked=" planSelected?.id == plan.id"
                         v-model="planSelected"
                         @change="checked(plan)"
                        type="radio"
                        ref="input"
                        name="duration"
                      />
            </div>
          
     </div>
     </div>
    <div class="grid lg:grid-cols-3  md:grid-cols-2  gap-4   md:mt-8">

      <div
        v-for="(item, index) in planSelected?.plan_frequencies"
        :key="index"
        class="
          jump
          bg-white
          overflow-hidden
          shadow-md
          border
          p-4
          sm:rounded-lg
          mb-3
          w-full
          lg:w-auto
        "
      >
        <template v-if="planSelected">
          <div>
            <div class="flex  flex-col">
              <div  class="flex items-center">
                 <div class="ml-4">
                     <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <rect width="46" height="46" rx="16" fill="dinesurf-green-600"/>
                          <path d="M23.0002 10.8613C19.7808 10.8613 16.6932 12.1402 14.4167 14.4167C12.1402 16.6932 10.8613 19.7808 10.8613 23.0002C10.8613 26.2197 12.1402 29.3072 14.4167 31.5837C16.6932 33.8602 19.7808 35.1391 23.0002 35.1391L23.0002 23.0002V10.8613Z" fill="#66CC66"/>
                          <path d="M22.9998 35.1387C26.2192 35.1387 29.3068 33.8598 31.5833 31.5833C33.8598 29.3068 35.1387 26.2192 35.1387 22.9998C35.1387 19.7804 33.8598 16.6928 31.5833 14.4163C29.3068 12.1398 26.2192 10.8609 22.9998 10.8609L22.9998 22.9998L22.9998 35.1387Z" fill="#7CE97C"/>
                      </svg>
                  </div>
                  <div class="flex  flex-col">
                     <span class="text-gray-400 text-lg">  {{
                        numberWithCommas(
                          item.price,
                         item.currency
                        )
                       }}/ {{ item?.duration_text }}  </span>
                  </div>
              </div>

              <div
                    class="flex items-start justify-start p-2 mt-1 pl-7"
                    v-for="(feature, index) in planSelected?.plan_features"
                    :key="index"
                  >
                    <svg
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      class="text-[#66CC66] opacity-75 w-5 h-5"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                    <div class="ml-2 text-sm text-gray-600">
                      {{ feature.name }}
                    </div>
                  </div>
                </div>
            <div class="flex justify-center w-full">
              <a
                class="jet-btn active:bg-gray-900 mx-10 mt-2 text-sm jet-btn-lg"
                :href="
                  payUrl({
                    plan_id: planSelected.id,
                    plan_frequency_id: item?.id,
                  })
                "
              >
                Set As New Plan
              </a>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: ["plans", "url"],
  data() {
    return {
      durationSelected:  null,
      durations:[],
      sPlans:this.plans,
      planSelected: this.plans[0]
    };
  },
  methods: {
    payUrl(data) {
      data.type = "subscription";

      var params = new URLSearchParams(data);
      return this.url + "?" + params;
    },
    setPlanData() {

      let getAllduration = [];
      this.plans.forEach((el) => {
        getAllduration.push(el.plan_frequencies)
      });


      console.log("getAllduration:", getAllduration);
      let filterDuration = [];

      for(var i = 0; i < getAllduration.length; i++) {
          for(var j = 0; j < getAllduration[i].length; j++) {
            if(filterDuration[j]?.duration_text !== getAllduration[i][j].duration_text){
                filterDuration.push(getAllduration[i][j]);
              }
          }
        }

        this.durations = filterDuration;
        this.durationSelected = filterDuration[0];
    },

    checked(plan){  
      //  this.durationSelected = this.durations[val];

       console.log("planSelected:", plan);
    },

    getCurrentPlan(id){
      const findPlan = this.plans.find((item) => item.id === id);
      const getCurrentDuration = findPlan.plan_frequencies.find((item) => item.duration_text === this.durationSelected.duration_text);
      // console.log("getCurrentDuration: ", getCurrentDuration);
      return {
        currency: getCurrentDuration?.currency,
        price: getCurrentDuration?.price
      }

    }

    
  },
  mounted() {
    this.setPlanData();
    // console.log("plans durations:", this.durationSelected);
    console.log("plans:", this.plans );
    // console.log("this.durations:", this.durations)
  },
};
</script>