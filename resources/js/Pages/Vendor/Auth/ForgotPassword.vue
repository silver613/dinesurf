<template>
    <vendor-auth-layout>
        <forgot-password-card :status="status"></forgot-password-card>
    </vendor-auth-layout>
</template>

<script>
import VendorAuthLayout from "@/Layouts/VendorAuthLayout.vue";
import ForgotPasswordCard from "@/components/vendor/ForgotPasswordCard.vue";

export default {
    props: { appName: String, status: String },
    components: {
        VendorAuthLayout,
        ForgotPasswordCard,
    },
};
</script>
