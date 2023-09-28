<template>
  <vendor-layout>
    <template #header>
           <header-text :title="title"  />
    </template>
    <div class="py-5 md:py-12 px-3">
      <div class="mx-auto max-w-7xl sm:px-2 lg:px-8">
        <div class="flex w-full flex-col">
          
          <template v-if="models.data.length > 1">
            <h5 class="mb-3">All Reviews</h5>
            <div
              class="
                text-grey-dark
                leading-normal
                text-sm
                flex
                items-start
                mb-5
              "
              v-for="(item, index) in models.data"
              :key="index"
            >
              <div class="pr-2">
                <img
                  class="h-8 w-auto rounded-full object-cover"
                  :src="item.avatar"
                  :alt="item.name"
                />
              </div>
              <div class="flex-grow">
                <div>
                  <span class="font-bold mr-2">{{ item.name }}</span>

                  <ratings :stars="item.stars"></ratings>
                </div>

                <span class="font-normal block">{{ item.content }}</span>
                <span class="mx-1 text-xs block mt-2 text-gray-500">{{
                  item.date
                }}</span>
              </div>
            </div>
          </template>
          <div v-else  class="mx-auto max-w-7xl">

            <empty-space 
                  :title="'Looks like you havent received any reviews yet!'"
                  :description="'Our apologies for not being able to show any reviews at the moment. We understand how important diner feedback is and how it can help in improving the overall dining experience at the restaurant. In the meantime, keep delighting your diners with delicious food and excellent service. We\'re sure that they\'ll be happy to share their thoughts about their experience soon.'"
                  
                  :image="'/images/empty_images/3.png'"
               />
          </div>
        </div>
      </div>
      <div class="mx-auto max-w-7xl"  v-if="models.data.length > 8">
                 <pagination class="mt-10" :links="models.links" :aTag="true" />
      </div>
      
    </div>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";

export default {
  components: {
    VendorLayout,
  },
  props: {
    models: Object,
    title: String,
  },
};
</script>
