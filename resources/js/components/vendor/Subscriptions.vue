<template>
    <div class="flex-1" v-if="subscriptions">
        <div class="pb-6 lg:pb-24 lg:max-w-4xl lg:mx-auto">
            <div>
                <div class="mt-10">
                    <h1
                        class="
                            text-2xl
                            font-semibold
                            text-gray-700
                            px-4
                            sm:px-8
                        "
                    >
                        Billing History
                    </h1>
                    <div class="mt-3 sm:px-8">
                        <div
                            class="
                                bg-white
                                sm:rounded-lg
                                shadow-sm
                                divide-y divide-gray-100
                            "
                        >
                            <div class="flex items-center px-6 py-4 font-bold">
                                <div class="text-sm w-1/4">Plan</div>
                                <div class="text-sm w-1/4">Started</div>
                                <div class="text-sm w-1/4">Ends</div>
                                <div class="w-1/4 flex justify-around">
                                    <div class="ml-10 text-sm w-2/3">
                                        Amount
                                    </div>
                                    <div class="ml-10 text-sm w-1/3">
                                        Status
                                    </div>
                                </div>
                                <!-- <div class="ml-10">
                                       Receipt
                                    </div> -->
                            </div>
                            <template v-if="subscriptions.length > 0">
                                <div
                                    class="flex items-center px-6 py-4"
                                    v-for="(item, index) in subscriptions"
                                    :key="index"
                                >
                                    <div class="text-sm w-1/4">
                                        {{ item.plan.name }}
                                    </div>
                                    <div class="text-sm w-1/4">
                                        {{ item.start }}
                                    </div>
                                    <div class="text-sm w-1/4">
                                        {{ item.end }}
                                    </div>
                                    <div
                                        class="
                                            ml-10
                                            text-sm
                                            w-1/4
                                            flex
                                            justify-around
                                        "
                                    >
                                        <div class="ml-10 text-sm w-2/3">
                                            <span>{{
                                                numberWithCommas(
                                                    item.plan_frequency.price,
                                                    item.plan_frequency.currency
                                                )
                                            }}</span>
                                        </div>
                                        <div class="ml-10 text-sm w-1/3">
                                            <span
                                                class="text-green-700"
                                                v-if="item.active"
                                                >active</span
                                            >
                                            <span v-else class="text-red-700"
                                                >in-active</span
                                            >
                                        </div>
                                    </div>
                                    <!-- <div class="ml-10">
                                        <a
                                            href="https://forge.laravel.com/spark/user/112539/receipts/in_1J1qtF2niB1UWjcEGdsJUHPG/download"
                                            target="_blank"
                                            class="
                                                underline
                                                text-sm text-gray-500
                                            "
                                            >Download Receipt</a
                                        >
                                    </div> -->
                                </div>
                            </template>
                            <div class="flex items-center px-6 py-4" v-else>
                                <div
                                    class="
                                        text-sm
                                        w-full
                                        text-center
                                        justify-center
                                        items-center
                                    "
                                >
                                    No Billing History Available
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!---->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        subscriptions: Array,
    },
};
</script>
