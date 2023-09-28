<template>
    <input
        id="calendar"
        :class="{ hidden: hideinput }"
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>

<script>
export default {
    props: [
        "modelValue",
        "initialDate",
        "enabledDays",
        "minDate",
        "inline",
        "hideinput",
    ],

    emits: ["update:modelValue"],

    data() {
        return {
            fp: null,
            date: this.initialDate,
            endableDates: this.enabledDays
                ? this.enabledDays
                : [0, 1, 2, 3, 4, 5, 6],
        };
    },

    methods: {
        focus() {
            this.$refs.input.focus();
        },
        startcalendar() {
            var element = document.getElementById("flatpickrCalendar");
            if (element) {
                element.remove();
                console.log("removed calendar");
            }

            if (this.fp) {
                this.fp.destroy();
                console.log("destroyed calendar: ", this.fp);
            }

            const vm = this;

            console.log("starting calender...");

            this.fp = flatpickr("#calendar", {
                inline: this.inline,
                dateFormat: "Y-m-d",
                minDate: this.minDate,
                enable: [
                    function (date) {
                        // return true to enable
                        return vm.endableDates.includes(date.getDay());
                    },
                ],
            });

            setTimeout(() => {
                var elements =
                    document.getElementsByClassName("flatpickr-calendar");
                if (elements) {
                    elements[0]?.setAttribute("id", "flatpickrCalendar");
                }
            }, 1000);
        },
    },
    mounted() {
        this.startcalendar();
    },
};
</script>
