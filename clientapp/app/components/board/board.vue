<template>
    <div class="board">
        <modalQuestion v-if="isQuestionActive">
            <question></question>
        </modalQuestion>
        <div class="board-x">
            <div class="board-y" v-for="(xrow, indexX) in data.boardData">
                <tile v-for="(field, indexY) in xrow" @selectTile="movePlayer" :fieldData="field" :playerState="playerState"
                    :posY="indexY" :posX="indexX">
                    <player v-if="isPlayerPos(indexX, indexY)"></player>
                </tile>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, defineProps, defineEmits } from "vue";
import { useStore } from "vuex";
import tile from "@/app/components/board/board-tile"
import modalQuestion from "@/app/components/modal/modal-question"
import question from "@/app/components/question/question"
import player from "@/app/components/player/player"

const store = useStore();

const data = defineProps({
    boardData: {
        type: Array,
        default: () => []
    },
    playerState: {
        type: Object,
        default: () => { }
    }
})

const emit = defineEmits(["movePlayer"])

const movePlayer = (posX, posY) => {
    emit("movePlayer", posX, posY)
}

const isQuestionActive = computed(() => {
    return store.getters.isQuestionActive;
})

const isPlayerPos = computed(() => {
    return (posX, posY) => store.getters.isPlayerPos(posX, posY)
})

</script>

<style lang="scss">
.board {
    display: flex;
    flex-direction: row;
    flex: 0 1 auto;
    aspect-ratio: 1 / 1;
    position: relative;

    .board-x {
        display: flex;
        flex-direction: row;
        flex: 1 1 0;
        gap: 0.25em;

        .board-y {
            display: flex;
            flex-direction: column;
            flex: 1 1 0;
            gap: 0.25em;
        }
    }
}
</style>