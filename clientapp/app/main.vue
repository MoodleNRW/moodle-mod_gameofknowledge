<template>
    <div class="mod-gameofknowledge">
        <template v-if="isGameActive">
        <div class="controls">
            <button class="btn btn-primary" @click.prevent="finishGame">End Game</button>
        </div>
        <board></board>
        </template>
        <lobby v-else></lobby>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, defineProps, computed } from "vue";
import { useStore } from "vuex";
import board from "@/app/components/board/board"
import lobby from "@/app/components/lobby/lobby"

const props = defineProps({
    coursemoduleid: {
        type: String
    },
    contextid: {
        type: Number
    }
})

const store = useStore();

onMounted(async () => {
    store.commit("setContextId", { contextid: props.contextid })
    store.commit("setCourseModuleId", { coursemoduleid: props.coursemoduleid })
    poll()
});

const poll = async () => {
    store.dispatch("getState")
    setTimeout(poll, 1000)
}

const finishGame = async () => {
    await store.dispatch("finishGame")
}

const isGameActive = computed(() => {
    return store.getters.isGameActive
})
</script>

<style lang="scss">
.controls {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    padding: 1rem 0;
}
</style>
