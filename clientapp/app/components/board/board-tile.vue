<template>
    <div class="tile" :class="classObj">
        {{ data.fieldData.question }}
    </div>
</template>

<script setup>
import { defineProps, computed } from "vue";

const data = defineProps({
    fieldData: {
        type: Object,
        default: () => { }
    }
})

const fieldType = computed(() => {
    return data.fieldData.type
})

const classObj = computed(() => ({
    "inactive": fieldType.value == 4
}))
</script>

<style lang="scss">
.tile {
    border: 1px solid black;
    border-radius: 0.25em;
    padding: 0.25em;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 1 1 auto;
    cursor: pointer;

    &.inactive {
        opacity: 0;
        cursor: not-allowed;
    }
}
</style>