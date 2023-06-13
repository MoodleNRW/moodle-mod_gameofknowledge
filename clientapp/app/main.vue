<template>
    <div class="mod-gameofknowledge">
        <board :boardData="boardState.boardData"></board>
    </div>
</template>

<script setup>
import { ref, reactive, inject, onMounted } from "vue";
import board from "@/app/components/board/board.vue"

const boardState = reactive({
    boardData: [
        [{ question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 4 }, { question: "Question", type: 1 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 1 }, { question: "Question", type: 4 }, { question: "Question", type: 1 }],
        [{ question: "Question", type: 1 }, { question: "Question", type: 1 }, { question: "Question", type: 4 }]]
})
let respData = ref([]);

onMounted(async () => {
    await getData();
});

const getData = async () => {
    try {
        respData.value = [{ id: 1, name: "Test" }];
    } catch { }
};
</script>

<style lang="scss"></style>
