<template>
       <vendor-layout>
          <template #header>
               <header-text :title="title"  />
          </template>
       <vendor-create-event />




  </vendor-layout>
</template>




<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import VendorCreateEvent from "@/components/vendor/VendorCreateEvent.vue";

export default {
 components: {
    VendorLayout,
    VendorCreateEvent
  },
props: {
    title: String,
  },
}
</script>