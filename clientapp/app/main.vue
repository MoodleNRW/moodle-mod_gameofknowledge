<template>
    <div class="mod-gameofknowledge">
        <board v-if="isGameActive" @movePlayer="movePlayer" :boardData="boardState.boardData" :playerState="playerState">
        </board>
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

const playerState = reactive({
    currentPosition: {
        posX: 0,
        posY: 0
    }
})

const boardState = reactive({
    boardData: [
        [{ question: "Question", type: 5 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 4 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 1 }, { question: "Question", type: 4 }, { question: "Question", type: 1 }, { question: "Question", type: 4 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 4 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }]]
})
let respData = ref([]);

onMounted(async () => {
    await getData();

    store.commit("setContextId", { contextid: props.contextid })
    store.commit("setCourseModuleId", { coursemoduleid: props.coursemoduleid })
});

const movePlayer = (posX, posY) => {
    const newPlayerState = { ...playerState, currentPosition: { posX: posX, posY: posY } }

    Object.assign(playerState, newPlayerState)
}

const getData = async () => {
    try {
        respData.value = [{ id: 1, name: "Test" }];
    } catch { }
};

const isGameActive = computed(() => {
    return store.getters.isGameActive
})
</script>

<style lang="scss"></style>
