<template>
    <div class="board">
        <modalQuestion v-if="isModalActive">
            <question v-if="isQuestionActive"></question>
            <div v-if="!isSessionPlayerTurn" class="other-turn">
                <h3>Please wait for the other player to finish their turn</h3>
            </div>
        </modalQuestion>
        <div class="board-x">
            <div class="board-y" v-for="(xrow, indexX) in boardTiles">
                <tile v-for="(field, indexY) in xrow" :fieldData="field" :posY="indexY" :posX="indexX">
                    <template v-for="(player, indexP) in players">
                        <player v-if="isPlayerPos(indexX, indexY, player.number)"></player>
                    </template>
                </tile>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, defineProps } from "vue";
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
    }
})

const boardTiles = computed(() => {
    return store.state.tiles;
})

const isQuestionActive = computed(() => {
    return store.getters.isQuestionActive;
})

const isSessionPlayerTurn = computed(() => store.getters.isSessionPlayerTurn)

const isModalActive = computed(() => isQuestionActive.value || !isSessionPlayerTurn.value)

const players = computed(() => { return store.state.players })

const isPlayerPos = computed(() => {
    return (posX, posY, id) => store.getters.isPlayerPos(posX, posY, id)
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

    .other-turn {
        flex: 0 1 auto;
        display: flex;
        flex-direction: column;
        background-color: white;
        padding: 1rem;
        border-radius: 0.25rem;
        z-index: 1;

        h3 {
            margin: 0;
        }
    }
}
</style>