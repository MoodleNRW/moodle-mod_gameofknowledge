<template>
    <div class="mod-gameofknowledge">
        <board v-if="isGameActive" @movePlayer="movePlayer" :boardData="boardState.boardData">
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

const boardState = reactive({
    boardData: [
        [{ question: "Question", type: 5 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 4 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 1 }, { question: "Question", type: 4 }, { question: "Question", type: 1 }, { question: "Question", type: 4 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 4 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }]]
})

onMounted(async () => {
    store.commit("setContextId", { contextid: props.contextid })
    store.commit("setCourseModuleId", { coursemoduleid: props.coursemoduleid })
});

const isGameActive = computed(() => {
    return store.getters.isGameActive
})
</script>

<style lang="scss"></style>
