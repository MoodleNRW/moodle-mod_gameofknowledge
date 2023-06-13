<template>
    <div class="mod-gameofknowledge">
        <board @movePlayer="movePlayer" :boardData="boardState.boardData" :playerState="playerState"></board>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import board from "@/app/components/board/board.vue"

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
</script>

<style lang="scss"></style>
