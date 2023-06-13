<template>
    <div class="board">
        <div class="board-x">
            <div class="board-y" v-for="(xrow, indexX) in data.boardData">
                <tile v-for="(field, indexY) in xrow" :fieldData="field" :playerState="playerState" :posY="indexY" :posX="indexX"></tile>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import tile from "@/app/components/board/board-tile.vue"

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

</script>

<style lang="scss">
.board {
    display: flex;
    flex-direction: row;
    flex: 0 1 auto;
    aspect-ratio: 1 / 1;

    .board-x {
        display: flex;
        flex-direction: row;
        flex: 1 1 auto;
        gap: 0.25em;

        .board-y {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            gap: 0.25em;
        }
    }
}
</style>