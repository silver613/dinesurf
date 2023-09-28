<template>
  <vendor-layout>
    <template #header>
      <!-- <h2 class="font-semibold text-xl text-gray-800 leading-tight">Billing</h2> -->  
      <header-text   :title="'Billing'" />
    </template>
    <subscribed
      v-if="vendor.subscribed"
      :subscription="subscription"
      :vendor="vendor"
      :plans="plans"
    ></subscribed>
    <un-subscribed
    v-else
      :vendor="vendor"
      :plans="plans"
     
      :billing_msg="billing_msg"
      :billing_msg_status="billing_msg_status"
    ></un-subscribed>

    <subscriptions :subscriptions="subscriptions"></subscriptions>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import UnSubscribed from "@/components/vendor/UnSubscribed.vue";
import Subscribed from "@/components/vendor/Subscribed.vue";
import Subscriptions from "@/components/vendor/Subscriptions.vue";

export default {
  props: [
    "vendor",
    "plans",
    "subscriptions",
    "subscription",
    "billing_msg",
    "billing_msg_status",
  ],
  components: {
    VendorLayout,
    UnSubscribed,
    Subscribed,
    Subscriptions,
  },
};
</script>
