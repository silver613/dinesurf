<template>
    <div class="auth-card">
        <div class="auth-card-header flex flex-col items-center">
            <a :href="route('index')">
                <img
                    class="
                        fill-current
                        block
                        md:hidden
                        h-7
                        w-auto
                        object-contain
                        mb-5
                    "
                    src="/images/company_logo.png"
                />
            </a>
            <h3>Reset Password</h3>
            <p>Set a new password for your account</p>
            <jet-validation-errors class="mb-4" />

            <div v-if="status" class="mt-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>
        </div>
        <div class="auth-card-body text-center">
            <form @submit.prevent="submit" class="flex flex-col w-full">
                <div class="flex flex-col items-start">
                    <jet-label for="email" value="Email" />
                    <jet-input
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                    />
                </div>

                <div class="mt-4 flex flex-col items-start">
                    <jet-label for="password" value="New Password" />
                    <jet-input
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        autofocus
                    />
                </div>

                <div class="mt-4 flex flex-col items-start">
                    <jet-label
                        for="password_confirmation"
                        value="Confirm Password"
                    />
                    <jet-input
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <button
                    type="submit"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        px-4
                        py-2
                        bg-red-800
                        border border-transparent
                        rounded-md
                        font-semibold
                        text-sm text-white
                        uppercase
                        tracking-widest
                        hover:bg-red-700
                        active:bg-red-900
                        focus:outline-none
                        focus:border-red-900
                        focus:ring
                        focus:ring-red-300
                        disabled:opacity-25
                        transition
                        my-6
                        btn-md btn-auth btn-red btn-reset
                    "
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
    },

    props: {
        status: String,
        initEmail: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: this.initEmail,
                password: "",
                password_confirmation: "",
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("vendors.reset-password.update"), {
                onFinish: () =>
                    this.form.reset("password", "password_confirmation"),
            });
        },
    },
};
</script>
