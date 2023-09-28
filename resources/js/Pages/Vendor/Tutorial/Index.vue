<template>
  <vendor-layout>
    <template #header>
   <header-text :title="title"  />
    </template>
    <div class="py-5 md:py-12 px-3 flex flex-row flex-wrap">
      <template v-if="tutorialList.length > 0">
        <section v-for="item in tutorialList" :key="item.title">
          <TutorialCard :tutorial="item" />
        </section>
      </template>
      <div v-else-if="this.loading"  class="flex items-center w-full justify-center">
        <span class="px-7 text-3xl py-8">Loading Tutorials</span>
        <loading-icon />
      </div>
      <div class="px-4 py-8 flex items-center w-full justify-center" v-else>
    

        <empty-space 
                  :title="' Oops!... Its Empty'"
                  :description="' Once there are Tutorials to show, they will be right here!.'"
                  
                  :image="'/images/empty_images/1.png'"
              />
      
      </div>
    </div>
  </vendor-layout>
</template>
  
  <script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import TutorialCard from "@/components/TutorialCard.vue";

export default {
  components: {
    VendorLayout,
    TutorialCard,
  },
  props: {
    title: String,
  },

  data() {
    return {
      tutorialList: [],
      loading: false
    };
  },
  methods: {
    loadData() {
      this.loading = true;
      axios
        .get(route("data.vendors.tutorial"))
        .then((result) => {
          this.tutorialList = result.data.tutorial;
        })
        .catch((err) => {
          console.log("Tutorial result error:", err);
        })
        .finally(() => this.loading = false);
    },
  },

  mounted() {
    this.loadData();
  },
};
</script>
  