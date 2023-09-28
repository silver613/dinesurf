<template>
  <div
    class="
      bg-white
      rounded-lg
      shadow
      relative
      px-6
      py-4
      min-h-40
      h-full
    "
  >
    <div class="h-6 flex items-center mb-4">
      <h3 class="mr-3 leading-tight text-sm font-bold">{{ title }}</h3>
    </div>
    <p class="flex items-center text-4xl mb-4">{{ value }}</p>
    <div
      ref="chart"
      class="absolute inset-0 rounded-b-lg ct-chart"
      style="top: 60%"
    ></div>
  </div>
</template>

<script>
import Chartist from "chartist";
import "chartist-plugin-tooltips";
import "chartist/dist/chartist.min.css";
import "chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css";

export default {
  props: {
    title: null,
    url: null,
    linkTo: {
      type: String,
      default: "javascript:void(0)",
    },
  },
  data() {
    return {
      loading: true,
      loaded: false,
      value: null,
      increaseOrDecreaseLabel: null,
      growthPercentage: 0,
      chartData: {
        labels: [],
        series: [],
      },
      chartist: null,
    };
  },
  watch: {
    chartData: function (newData, oldData) {
      this.renderChart();
    },
  },
  methods: {
    renderChart() {
      this.chartist.update(this.chartData);
    },
    loadData() {
      this.loaded = false;
      this.loading = true;

      axios
        .get(this.url)
        .then((result) => {
          this.value = result.data.value;
          this.loading = false;
          this.loaded = true;

          var data = result.data.series;

          var arr = Object.entries(data);
          var labels = [];
          var series = [];

          arr.forEach((el, key) => {
            // labels.push(el[0]);
            series.push({ meta: el[0], value: el[1] });
          });

          this.chartData = {
            labels: labels,
            series: [series],
          };

        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
    mountChart() {
      const low = Math.min(...this.chartData.series);
      const high = Math.max(...this.chartData.series);

      // Use zero as the graph base if the lowest value is greater than or equal to zero.
      // This avoids the awkward situation where the chart doesn't appear filled in.
      const areaBase = low >= 0 ? 0 : low;

      var options = {
        lineSmooth: Chartist.Interpolation.none(),
        fullWidth: true,
        showPoint: true,
        showLine: true,
        showArea: true,
        chartPadding: {
          top: 10,
          right: 0,
          bottom: 0,
          left: 0,
        },
        low,
        high,
        areaBase,
        axisX: {
          showGrid: false,
          showLabel: true,
          offset: 0,
        },
        axisY: {
          showGrid: false,
          showLabel: true,
          offset: 0,
        },
        plugins: [
          Chartist.plugins.tooltip({
            anchorToPoint: true,
            transformTooltipTextFnc: (value) => {
              return this.numberWithCommas(value);
            },
          }),
        ],
      };

      this.chartist = new Chartist.Line(
        this.$refs.chart,
        this.chartData,
        options
      );
    },
  },
  mounted() {
    this.mountChart();
    this.loadData();
  },
};
</script>



<style>
/* .ct-series-a .ct-bar,
.ct-series-a .ct-line,
.ct-series-a .ct-point {
  stroke: #8cc63f !important;
  stroke-width: 2px;
}

.ct-point {
  stroke: #8cc63f !important;
  stroke-width: 6px !important;
  stroke-linecap: round;
}

.chartist-tooltip {
  background: #fff;
  color: #8cc63f;
  min-width: 130px;
  border-radius: 8px;
}
.chartist-tooltip:before {
  display: none;
} */
</style>