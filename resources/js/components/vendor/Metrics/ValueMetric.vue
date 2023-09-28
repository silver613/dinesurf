<template>
  <LoadingCard
    class="bg-white rounded-lg shadow px-6 py-4 min-h-40 h-full jump"
    :loading="loading"
    :title="title"
    :loaded="loaded"
    @loadData="loadData()"
  >
    <Link :href="linkTo">

       <div   class="flex flex-col">
           <div  class="flex  justify-between">
                     <div  class="w-10 h-10 p-3 bg-gray-100  flex items-center  justify-center rounded-full"> 
                      
                              <slot name="icon" > </slot>
                     </div>
                     <div>
                          <span  class="bg-gray-200 p-2 rounded text-xs  capitalize">{{ categoryBadge }}</span>
                     
                     </div>

           </div>
           <div class="mt-4 flex justify-between">
              <span class="text-gray-500 text-xs">
                {{ title }}
              </span>

              <!-- <span class="text-gray-500 text-xs" v-if="subCount > 0">
                Total Page Views
              </span> -->
           </div>
           
           <div  class="flex justify-between">
                      <div>
                                      <span class="text-3xl">  {{ prefix }}{{  this.title.toLowerCase().includes('revenue') ?  (Math.round(value * 100) / 100).toLocaleString() : nFormatter(value) }} {{ suffix }}</span>
                        </div>

                        <div v-if="increaseOrDecreaseLabel" >
                                  <span v-if="growthPercentage!==0" class="capitalize  "
                                  :class="increaseOrDecreaseLabel == 'increase' ? 'text-dinesurf-green-400' : 'text-dinesurf-red-500'"
                                  >
                                   {{ increaseOrDecreaseLabel == 'increase' ? '+' : '-' }}  {{ numberWithCommasDec(growthPercentage) }}%
                                   
                                  </span>
                        </div>

                        <div  v-if="subCount > 0">
                                 <span class="text-3xl">{{ nFormatter(subCount) }}</span>
                        </div>
           </div>
       </div>
      
    </Link>
  </LoadingCard>
</template>

<script>
export default {
  props: {
    title: null,
    url: null,
    prefix: null,
    suffix: null,
    linkTo: {
      type: String,
      default: "javascript:void(0)",
    },
    categoryBadge:null,
    iconLabel: null
  },
  data() {
    return {
      loading: true,
      loaded: false,
      value: null,
      increaseOrDecreaseLabel: null,
      growthPercentage: 0,
      subCount: 0,
    };
  },
  methods: {
    loadData() {
      this.loaded = false;
      this.loading = true;

      axios
        .get(this.url)
        .then((result) => {

          // console.log("result:", result);
          this.value = result.data.value;
          this.subCount = result.data.visitValue;
          this.increaseOrDecreaseLabel = result.data.increaseOrDecreaseLabel;
          this.growthPercentage = result.data.growthPercentage;
          this.loading = false;
          this.loaded = true;
        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
  },
  mounted() {
    this.loadData();
  },
};
</script>
