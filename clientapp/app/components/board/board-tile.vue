<template>
    <div class="tile" :class="classObj" @click.prevent="selectTile">
        <div class="description">
            {{ fieldContentPlaceholder }}
        </div>
        <slot></slot>
    </div>
</template>

<script setup>
import { defineProps, computed } from "vue";
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

const selectTile = (() => {
    if (isMovementAvailable.value) {
        store.dispatch("activateQuestion", { index: props.fieldData.questionindex })
        store.dispatch("movePlayer", { posX: props.posX, posY: props.posY })
    }
});

const fieldType = computed(() => {
    return props.fieldData.type
})

const isStart = computed(() => fieldType.value == "start")

const isEmpty = computed(() => fieldType.value == "empty")

const isNone = computed(() => fieldType.value == "none")

const isQuestion = computed(() => fieldType.value == "question")

const isSolved = computed(() => fieldType.value == "solved")

const isGoal = computed(() => fieldType.value == "goal")

const isMovementAvailable = computed(() => {
    return store.getters.isMovementAllowed(fieldType.value, props.posX, props.posY)
});

const fieldContentPlaceholder = computed(() => {
    if (isQuestion.value) {
        return "?"
    } else if (isSolved.value) {
        return "✓"
    } else if (isEmpty.value) {
        return "Keep Going"
    } else if (isStart.value) {
        return "Start"
    } else if (isGoal.value) {
        return "Finish"
    }
    else {
        return " "
    }
});

const classObj = computed(() => ({
    "current": fieldType.value == 2,
    "inactive": isNone.value,
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

    .description {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: absolute;
        width: 100%;
        height: 100%;
    }
}
</style>