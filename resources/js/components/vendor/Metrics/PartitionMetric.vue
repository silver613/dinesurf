<template>
  <LoadingCard2
    class="bg-white rounded-lg shadow relative px-6 py-4 min-h-40 h-full jump"
    :trigger="trigger"
    :url="url"
    :title="title"
    @setData="setData"
    :paginated="true"
  >
    <Link :href="linkTo">
      <h3 class="h-6 flex mb-3 text-sm font-bold">
        {{ title }}

        <span class="ml-auto font-semibold text-gray-400 text-xs">
          ({{ prefix }}{{ formattedTotal }} total)</span
        >
      </h3>

      <div class="min-h-[90px] flex">
        <div
          class="
            overflow-hidden overflow-y-auto
            max-h-[90px]
            flex-grow
            max-w-[65%]
          "
        >
          <ul>
            <li
              v-for="item in formattedItems"
              :key="item.color"
              class="text-xs leading-normal"
            >
              <span
                class="inline-block rounded-full w-2 h-2 mr-2"
                :style="{
                  backgroundColor: item.color,
                }"
              />{{ item.label }} ({{ item.value }} - {{ item.percentage }}%)
            </li>
          </ul>
        </div>

        <div
          ref="chart"
          :class="chartClasses"
          style="
            width: 90px;
            height: 90px;
            right: 20px;
            bottom: 30px;
            top: calc(50% + 15px);
          "
        />
      </div>
    </Link>
  </LoadingCard2>
</template>

<script>
import map from "lodash/map";
import sumBy from "lodash/sumBy";
import "./css/index.css";
import { PieChart } from "chartist";
import { Link } from "@inertiajs/inertia-vue3";
import LoadingCard2 from "@/components/LoadingCard2.vue";

const colorForIndex = (index) =>
  [
    "#F5573B",
    "#F99037",
    "#F2CB22",
    "#8FC15D",
    "#098F56",
    "#47C1BF",
    "#1693EB",
    "#6474D7",
    "#9C6ADE",
    "#E471DE",
  ][index];

export default {
  components: {
    Link,
    LoadingCard2,
  },
  props: {
    title: String,
    prefix: null,
    url: null,
    linkTo: {
      type: String,
      default: "javascript:void(0)",
    },
  },

  data: () => ({
    trigger: 1,
    chartist: null,
    value: null,
    chartItems: [],
  }),

  watch: {
    chartData: function (newData, oldData) {
      this.renderChart();
    },
    url(newVal, oldVal) {
      this.trigger++;
    },
  },

  computed: {
    chartClasses() {
      return [
        "vertical-center",
        "rounded-b-lg",
        "ct-chart",
        "mr-4",
        this.currentTotal <= 0 ? "invisible" : "",
      ];
    },

    chartData() {
      return { labels: this.formattedLabels, series: this.formattedData };
    },

    formattedItems() {
      return map(this.chartItems, (item, index) => {
        return {
          label: item.label,
          value: this.numberWithCommas(item.value),
          color: this.getItemColor(item, index),
          percentage: this.numberWithCommas(String(item.percentage)),
        };
      });
    },

    formattedLabels() {
      return map(this.chartItems, (item) => item.label);
    },

    formattedData() {
      return map(this.chartItems, (item, index) => {
        return {
          value: item.value,
          meta: { color: this.getItemColor(item, index) },
        };
      });
    },

    formattedTotal() {
      let total = this.currentTotal.toFixed(2);
      let roundedTotal = Math.round(total);

      if (roundedTotal.toFixed(2) == total) {
        return this.numberWithCommas(roundedTotal);
      }

      return this.numberWithCommas(total);
    },

    currentTotal() {
      return sumBy(this.chartItems, "value");
    },
  },
  methods: {
    renderChart() {
      if (this.chartist) {
        this.chartist.update(this.chartData);
      }
    },

    getItemColor(item, index) {
      return typeof item.color === "string" ? item.color : colorForIndex(index);
    },
    setData(data) {
      this.chartItems = data.value;
      var vm = this;
      setTimeout(
        () => {
          vm.mountChart();
        },
        1000,
        vm
      );
    },
    mountChart() {
      this.chartist = new PieChart(this.$refs.chart, this.chartData, {
        // donut: true,
        // donutWidth: 5,
        // donutSolid: true,
        startAngle: 270,
        showLabel: false,
      });

      this.chartist.on("draw", (context) => {
        if (context.type === "slice") {
          if (context.meta) {
            context.element.attr({
              style: `fill: ${context.meta.color} !important`,
            });
          }
        }
      });
    },
  },
  watch: {
    url(newVal, oldVal) {
      this.trigger++;
    },
  },
};
</script>
