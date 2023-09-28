<template>
    <div class=" flex flex-col w-auto  ">
        <jet-section-title >
            <template #title><slot name="title"></slot></template>
            <template #description><slot name="description"></slot></template>
        </jet-section-title>

        <div class="mt-15 md:mt-0  md:col-span-6 ">
            <form @submit.prevent="$emit('submitted')">
                <div class="px-4 py-5 bg-white sm:p-6 shadow-settings-page"
                    :class="hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'">
                    <!-- <div class="grid grid-cols-6 gap-6"> -->
                        <slot name="form"></slot>
                    <!-- </div> -->
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-white text-right sm:px-6 shadow-menu sm:rounded-bl-md sm:rounded-br-md" v-if="hasActions">
                    <slot name="actions"></slot>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import JetSectionTitle from './SectionTitle.vue'

    export default {
        emits: ['submitted'],

        components: {
            JetSectionTitle,
        },

        computed: {
            hasActions() {
                return !! this.$slots.actions
            }
        }
    }
</script>
