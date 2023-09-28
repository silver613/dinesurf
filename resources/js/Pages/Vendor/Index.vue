<template>
  <vendor-auth-layout>
    <!-- <login-card></login-card> -->
    <register-card></register-card>
  </vendor-auth-layout>
</template>

<script>
import VendorAuthLayout from "@/Layouts/VendorAuthLayout.vue";
import LoginCard from "@/components/vendor/LoginCard.vue";
import RegisterCard from "@/components/vendor/RegisterCard.vue";

export default {
  props: { appName: String },
  components: {
    VendorAuthLayout,
    LoginCard,
    RegisterCard,
  },
};
</script>