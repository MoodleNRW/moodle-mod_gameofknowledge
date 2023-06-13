<template>
    <div class="board">
        <div class="board-x">
            <div class="board-y" v-for="(xrow, indexX) in data.boardData">
                <tile v-for="(field, indexY) in xrow" @selectTile="movePlayer" :fieldData="field" :playerState="playerState"
                    :posY="indexY" :posX="indexX"></tile>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
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

const emit = defineEmits(["movePlayer"])

const movePlayer = (posX, posY) => {
    emit("movePlayer", posX, posY)
}

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