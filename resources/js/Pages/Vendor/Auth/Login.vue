<template>
  <vendor-auth-layout>
    <login-card :status="status"></login-card>
  </vendor-auth-layout>
</template>

<script>
import VendorAuthLayout from "@/Layouts/VendorAuthLayout.vue";
import LoginCard from "@/components/vendor/LoginCard.vue";

export default {
  props: { appName: String, status: String },
  components: {
    VendorAuthLayout,
    LoginCard,
  },
};
</script>