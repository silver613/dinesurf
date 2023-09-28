<template>
  <vendor-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight capitalize">
        {{ this.$page.props.auth.vendor.type }} Dashboard
      </h2>
    </template>
    <div class="p-4 md:py-8 md:px-12">
      <div class="grid md:grid-cols-12 gap-6 mb-3 pb-3">
        <value-metric
          class="md:col-span-12 lg:col-span-6 xl:col-span-3"
          :title="'Total SMS Sent'"
          :url="
            route('data.vendors.metrics', {
              type: 'number-of-sms',
              ...params,
            })
          "
          :linkTo="'javascript:void(0)'"
        ></value-metric>

        <value-metric
          class="md:col-span-12 lg:col-span-6 xl:col-span-3"
          :title="'Total Emails'"
          :url="
            route('data.vendors.metrics', {
              type: 'number-of-emails',
              ...params,
            })
          "
          :linkTo="'javascript:void(0)'"
        ></value-metric>
      </div>
    </div>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import ValueMetric from "@/components/vendor/Metrics/ValueMetric.vue";
import TrendMetric2 from "@/components/vendor/Metrics/TrendMetric2.vue";
import PartitionMetric from "@/components/vendor/Metrics/PartitionMetric.vue";

export default {
  components: {
    VendorLayout,
    ValueMetric,
    TrendMetric2,
    PartitionMetric,
  },
  data() {
    return {
      params: {},
    };
  },
};
</script>
