<template>
  <LoadingCard2
    class="bg-white rounded-lg shadow relative px-4 py-2 min-h-40 h-52"
    :trigger="trigger"
    :url="url"
    :title="title"
    @setData="setData"
    :paginated="true"
  >
    <div class="h-6 flex items-center mb-4">
      <h3 class="mr-3 leading-tight text-sm font-bold">{{ title }}</h3>
    </div>
    <div class="flex items-center justify-center w-full">
      <canvas :id="id" class="w-auto mt-1"></canvas>
    </div>
  </LoadingCard2>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import LoadingCard2 from "@/components/LoadingCard2.vue";
import Chart from "chart.js/auto";
import { max, min } from "lodash";

export default {
  props: {
    id: {
      required: true,
    },
    graphTitle: {
      required: true,
    },
    title: null,
    url: null,
    linkTo: {
      type: String,
      default: "javascript:void(0)",
    },
  },
  data() {
    return {
      myChart: null,
      trigger: 1,
    };
  },
  components: {
    Link,
    LoadingCard2,
  },
  methods: {
    setData(res) {
      this.value = res.value;
      var data = res.series;

      var arr = Object.entries(data);
      var labels = [];
      var series = [];

      arr.forEach((el, key) => {
        labels.push(el[0]);
        series.push(el[1]);
      });

      this.chartData = {
        labels: labels,
        series: series,
      };

      var vm = this;
      setTimeout(
        () => {
          vm.createChart(vm.id);
        },
        1000,
        vm
      );
    },
    createChart(chartId) {
      if (this.myChart) {
        this.myChart.destroy();
      }

      var c = document.getElementById(chartId);
      if (c != null) {
        var ctx = c.getContext("2d");
        const labels = this.chartData.labels;
        const series = this.chartData.series;

        var gradientFill = ctx.createLinearGradient(0, 200, 0, 1);
        gradientFill.addColorStop(0, "rgba(255, 255, 255,0.1)");
        gradientFill.addColorStop(1, "#8cc63f");

        this.myChart = new Chart(ctx, {
          type: "line",
          data: {
            labels: labels,
            datasets: [
              {
                // another line graph

                label: this.graphTitle,
                data: series,
                borderWidth: 2,
                borderColor: "#8cc63f",

                pointBorderColor: "#8cc63f",
                pointBackgroundColor: "#8cc63f",
                pointHoverBackgroundColor: "#8cc63f",
                pointHoverBorderColor: "#8cc63f",
                pointBorderWidth: 2,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 4,
                pointRadius: 2,
                fill: true,
                backgroundColor: gradientFill,
                lineTension: 0,
              },
            ],
          },
          options: {
            plugins: {
              legend: {
                display: false,
              },
            },

            maintainAspectRatio: false,
            responsive: true,
            lineTension: 1,
            defaultFontColor: "#8cc63f",
            defaultFontSize: 13,
            defaultFontFamily: "Sofia Pro, Regular",
            title: {
              display: false,
            },
            layout: {
              padding: {
                top: 10,
                right: 0,
                bottom: 0,
                left: 0,
              },
            },
            legend: {
              display: false,
            },
            tooltips: {
              callbacks: {
                label: function (tooltipItem) {
                  return tooltipItem.yLabel;
                },
              },
            },
            scales: {
              x: {
                grid: {
                  display: false,
                },
              },
              y: {
                grid: {
                  display: false,
                },
              },
            },
          },
        });
      }
    },
  },
  watch: {
    url(newVal, oldVal) {
      
      this.trigger++;
    },
  },
};
</script>