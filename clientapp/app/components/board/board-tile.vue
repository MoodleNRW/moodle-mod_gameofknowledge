<template>
    <div class="tile" :class="classObj" @click.prevent="selectTile">
        {{ fieldContentPlaceholder }}
        <slot></slot>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from "vue";
import { useStore } from "vuex"

const store = useStore();

const props = defineProps({
    fieldData: {
        type: Object,
        default: () => { }
    },
    posX: {
        type: Number
    },
    posY: {
        type: Number
    },
})

const emit = defineEmits(["selectTile"])

const selectTile = (() => {
    if (isMovementAvailable.value) {
        store.dispatch("movePlayer", { posX: props.posX, posY: props.posY })
    }
});

const fieldType = computed(() => {
    return props.fieldData.type
})

const isSolved = computed(() => {
    return fieldType.value == 5
})

const isMovementAvailable = computed(() => {
    return store.getters.isMovementAllowed("question", props.posX, props.posY)
});

const fieldContentPlaceholder = computed(() => {
    return fieldType.value == 1 ? "?" : "✓"
});

const classObj = computed(() => ({
    "current": fieldType.value == 2,
    "inactive": fieldType.value == 4,
    "solved": fieldType.value == 5,
    "is-available": isMovementAvailable.value && !isSolved.value
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
    cursor: not-allowed;
    position: relative;

    &.inactive {
        opacity: 0;
        cursor: not-allowed !important;
    }

    &.solved {
        background-color: greenyellow;
        cursor: not-allowed !important;
    }

    &.is-available {
        background-color: #fae;
        cursor: pointer;
    }
}
</style>