<template>
    <vendor-auth-layout>
        <reset-password-card
            :status="status"
            :initEmail="email"
        ></reset-password-card>
    </vendor-auth-layout>
</template>

<script>
import VendorAuthLayout from "@/Layouts/VendorAuthLayout.vue";
import ResetPasswordCard from "@/components/vendor/ResetPasswordCard.vue";

export default {
    props: { appName: String, status: String, email: String },
    components: {
        VendorAuthLayout,
        ResetPasswordCard,
    },
};
</script>
