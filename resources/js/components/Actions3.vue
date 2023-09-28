<template>
  <select :id="id">
    <option :value="null">Actions</option>
    <option>Email Users</option>
    <option>Text Users</option>
  </select>
</template>

<script>
export default {
  data() {
    return {
      action: null,
    };
  },
  props: {
    actions: {
      type: Array,
      default: [],
    },
    py: {
      type: String,
    },
    id: {
      required: true,
    },
  },
};
</script>
