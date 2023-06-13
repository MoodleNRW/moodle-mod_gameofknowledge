<template>
    <div class="tile" :class="classObj" @click.prevent="selectTile">
        {{ fieldContentPlaceholder }}
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from "vue";

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
    playerState: {
        type: Object,
        default: () => { }
    }
})

const emit = defineEmits(["selectTile"])

const selectTile = (() => {
    if (isMovementAvailable) {
        emit("selectTile", props.posX, props.posY)
    }
});

const fieldType = computed(() => {
    return props.fieldData.type
})

const isMovementAvailable = computed(() => {
    const playerX = props.playerState.currentPosition.posX;
    const playerY = props.playerState.currentPosition.posY;

    return fieldType.value != 5 && ( 
    (props.posY == playerY && (props.posX == playerX - 1 || props.posX == playerX + 1)) ||
        (props.posX == playerX && (props.posY == playerY - 1 || props.posY == playerY + 1)))
});

const fieldContentPlaceholder = computed(() => {
    return fieldType.value == 1 ? "?" : "✓"
});

const classObj = computed(() => ({
    "current": fieldType.value == 2,
    "inactive": fieldType.value == 4,
    "solved": fieldType.value == 5,
    "is-available": isMovementAvailable.value
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