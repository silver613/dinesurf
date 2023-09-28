<template>
  <vendor-auth-layout>
    <register-card></register-card>
  </vendor-auth-layout>
</template>

<script>
import VendorAuthLayout from "@/Layouts/VendorAuthLayout.vue";
import RegisterCard from "@/components/vendor/RegisterCard.vue";

export default {
  props: { appName: String },
  components: {
    VendorAuthLayout,
    RegisterCard,
  },
};
</script>