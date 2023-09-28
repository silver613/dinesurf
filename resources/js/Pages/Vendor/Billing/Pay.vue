<template>
    <vendor-layout>
        <template #header>
            <header-text   :title="'Make Payment'" />
        </template>
        <payment
            :vendor="vendor"
            :type="type"
            :plan="plan"
            :plans="plans"
            :paystackkey="paystackkey"
            :stripekey="stripekey"
        ></payment>
    </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import Payment from "@/components/vendor/Payment.vue";

export default {
    props: ["vendor", "type", "plan", "plans","paystackkey","stripekey"],
    components: {
        VendorLayout,
        Payment,
    },
};
</script>
