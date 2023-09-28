<template>
    <div v-if="isLoading" class="flex items-center w-full justify-center">
        <span class="text-xs">open hours</span>
        <loading-icon />
    </div>
    <div v-else>
        <div v-if="customDays">
            <div v-if="customDays.length > 0">
                <div
                    v-if="!isDaysOpened"
                    class="cursor-pointer"
                    @click="isDaysOpened = !isDaysOpened"
                >
                    <div class="font-bold" v-if="getHourStat()">
                        <span class="text-xs font-bold mt-0 text-green-900">
                            Open
                        </span>
                        <span v-if="currentDay">
                            <span v-if="currentDay.pivot.close_time === null">
                                - 24/7
                                <i
                                    v-if="!this.noDropDown"
                                    class="fa fa-angle-down"
                                ></i>
                            </span>
                            <span v-else>
                                - Close
                                {{ getTime(currentDay.pivot.close_time) }}
                                <i
                                    v-if="!this.noDropDown"
                                    class="fa fa-angle-down"
                                ></i>
                            </span>
                        </span>
                    </div>

                    <div v-else>
                        <span class="text-xs font-bold mt-0 text-red-900">
                            Close
                        </span>

                        <span v-if="currentDay" class="capitalize">
                            - {{ currentDay.name }}
                            {{ getTime(currentDay.pivot.open_time) }}
                            <i
                                v-if="!this.noDropDown"
                                class="fa fa-angle-down"
                            ></i>
                        </span>
                    </div>
                </div>

                <div
                    v-if="isDaysOpened"
                    class="cursor-pointer"
                    @click="isDaysOpened = !isDaysOpened"
                >
                    <div
                        v-for="(item, index) in customDays"
                        :key="index"
                        class="flex mb-1"
                    >
                        <span class="text-xs font-bold mt-0 capitalize w-20"
                            >{{ item.name }}
                        </span>
                        <span
                            class="ml-3 text-[11px]"
                            v-if="
                                item.pivot.open_time === null &&
                                item.pivot.close_time === null
                            "
                            >24/7</span
                        >
                        <span class="ml-3 text-[11px]" v-else>
                            {{ getTime(item.pivot.open_time) }} -
                            {{ getTime(item.pivot.close_time) }}</span
                        >
                    </div>
                </div>
            </div>
            <span v-else class="text-xs mt-0">{{ vendor.hours }}</span>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import axios from "axios";
export default {
    props: ["vendor", "noDropDown"],
    data() {
        return {
            isLoading: true,
            days: [],
            currentDay: null,
            nextDay: null,
            isDaysOpened: false,
            is_same_time:
                !this.vendor.is_same_time &&
                this.vendor.days?.length < 1 &&
                this.vendor.open_time != null
                    ? true
                    : this.vendor.is_same_time,
            customDays: [],
            checkDate:
                this.vendor.open_time === null &&
                this.vendor.close_time === null &&
                this.vendor.days?.length === 0,
        };
    },

    methods: {
        getDays() {
            var vn = this;
            if (vn.is_same_time || vn.checkDate) {
                axios(route("days"))
                    .then((result) => {
                        vn.days = result.data.data.days;
                        vn.isLoading = false;
                        vn.setDays();
                    })
                    .catch((err) => {
                        this.handleApiError(err);
                    });
            } else {
                vn.days = vn.vendor.days;
                vn.setDays();
                vn.isLoading = false;
            }
        },

        getTime(time) {
            if (time === null) return;
            const momentDate = moment(time, "h A");
            return momentDate.format("h A");
        },

        getHourStat() {
            const today = this.customDays?.find(
                (item) =>
                    item.name === moment().format("dddd").toLocaleLowerCase()
            );
            this.currentDay = today;
            const open_time = moment(today?.pivot?.open_time, "h:mm:ss A");
            const close_time = moment(today?.pivot?.close_time, "h:mm:ss A").add(1, 'day');
            const current_time = moment(
                moment().format("h:mm:ss A"),
                "h:mm:ss A"
            );
            if (
                today?.pivot?.open_time === null &&
                today?.pivot?.close_time === null
            ) {
                return true;
            }

            if (moment(current_time).isBetween(open_time, close_time))
                return true;
            else return false;
        },

        commingDay() {
            const Tomorrow = moment().add(1, "days").format("dddd");
            const NextDay = this.customDays?.find(
                (item) => item.name === Tomorrow.toLocaleLowerCase()
            );

            if (NextDay) {
                this.nextDay = NextDay;
            }
        },

        setDays() {
            if (this.is_same_time || this.checkDate) {
                const reDays = this.days?.map((item) => ({
                    ...item,
                    pivot: {
                        open_time: this.vendor.open_time,
                        close_time: this.vendor.close_time,
                    },
                }));

                this.customDays = reDays;
            // console.log("this.customDays: ",this.customDays);

            } else {
                this.customDays = this.vendor.days;
            // console.log("this.customDays: ",this.customDays);

            }


            this.getHourStat();
            this.commingDay();
        },
    },
    mounted() {
        this.getDays();
    },
};
</script>
