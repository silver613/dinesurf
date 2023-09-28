<template>
    <div class="index-bg">
        <div class="index-overlay flex items-center">
            <div class="flex flex-col items-center home-welcome w-full">
                <a :href="route('index')" class="mb-5">
                    <img
                        class="fill-current h-16 w-auto sm:h-20 object-contain"
                        src="/images/ds_logo.svg"
                    />
                </a>
                <h3>Welcome</h3>
                <p>Please sign in or create an account to continue.</p>
                <div class="mt-8">
                    <a
                        class="
                            btn btn-md
                            welcome-btn
                            btn-white-red
                            welcome-btn2
                        "
                        :href="route('dashboard')"
                    >
                        <span class="mr-3">Continue as Guest </span>
                        <i class="fas fa-sign-in-alt"></i>
                    </a>
                    <Link
                        class="btn btn-md welcome-btn btn-white-transparent"
                        :href="route('login')"
                    >
                        Sign in with Email
                    </Link>
                    <a
                        class="
                            btn btn-md
                            welcome-btn
                            btn-white-black
                            welcome-btn2
                        "
                        :href="route('google-redirect')"
                    >
                        <img
                            src="/images/google-favicon.png"
                            class="h-6 w-6 mr-3"
                        />
                        <span> Continue with Google</span>
                    </a>
                    <!-- <Link class="btn welcome-btn" :href="route('welcome')">
            <i class="fab fa-facebook-f"></i> Continue with Facebook
          </Link> -->
                    <Link
                        class="btn btn-md welcome-btn btn-white-transparent"
                        :href="route('register')"
                    >
                        Register with Dine Surf
                    </Link>
                    <div class="flex justify-center">
                        <a target="_blank" class="text-base font-bold jump" :href="route('vendors.dashboard')">Vendors <i class="fas fa-angle-double-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout.vue";

export default {
    props: { appName: String },
    components: {
        SiteLayout,
    },
    mounted() {
        document.title = "Dining Reservations Made Easy - Dinesurf";
    },
};
</script>
