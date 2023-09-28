<template>
  <div class="">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div
        v-if="vendor"
        class="bg-white overflow-hidden  p-4 sm:rounded-lg mb-3 pb-8"
      >
        <div
          v-if="billing_msg"
          class="mb-4 font-bold text-2xl"
          :class="{
            'text-green-700': billing_msg_status == 'success',
            'text-red-500': billing_msg_status == 'failed',
          }"
        >
          {{ billing_msg }}
        </div>
        <div  class="w-full flex flex-col  items-center">
          <h5 class="text-xl font-light mb-2">Get more from Dinesurf</h5>
           <p class="text-center">
            <span class="text-dinesurf-green-400  text-bold font-bold" v-if="vendor.free_trial">
              You're currently on Free Trial,  <span class="mr-3">Free Trial Starts:</span>
              <span>{{ vendor.free_trial_starts }}, </span>
              <span class="mr-3">  And Ends:</span>
              <span>{{ vendor.free_trial_ends }}</span>
              <br />
            </span>

            <span class="text-dinesurf-red-400"  v-if="!vendor.free_trial && vendor.free_trial_end">
              Your Free Trial has ended on {{ vendor.free_trial_ends }}
              <br />
            </span>

            <span class="text-dinesurf-red-400" v-if="!vendor.free_trial">
              You are currently not subscribed to a plan
              <br />
            </span>

             To get the most of our features, 
            upgrade your account to maximize table reservations and  communication with customers! 
           </p>
      
        </div>
     
         <subscription-plans :plans="plans" :url="route('vendors.pay')" />
      </div>
    </div>
  </div>
</template>

<script>
import SubscriptionPlans from "./SubscriptionPlans.vue";
export default {
  props: ["plans", "billing_msg", "billing_msg_status", "vendor"],
  components: {
    SubscriptionPlans,
  },
};
</script>
