<template>
    <span class="rate-stars">
        <span
            v-for="(n, nIndex) in 5"
            :key="nIndex"
            class="fa fa-star"
            :class="{ checked: stars >= n }"
        ></span>
    </span>
</template>

<script>
export default {
    props: ["stars"],
};
</script>
