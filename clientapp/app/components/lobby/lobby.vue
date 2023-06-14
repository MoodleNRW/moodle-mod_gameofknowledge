<template>
    <div class="lobby">
        <div v-if="isGameSetup" class="setup">
            <h2 class="title">Game of Knowledge</h2>
            <p v-if="!isGameError" class="welcome">Welcome to the new and exciting Game of Knowledge. <br>When you're ready,
                please click the "Start New
                Game" button below!</p>
            <div class="controls">
                <button class="btn btn-primary" @click.prevent="startGame">Start Game</button>
            </div>
            <p v-if="isGameError" class="error">Whoopsie-daisy! <br />We're sorry, but something didn't work as expected
                when
                starting your game. <br />Please give it a few seconds, then try again.</p>
        </div>
        <div v-if="isGameInitializing" class="waiting">
            <h2 class="title">Please wait for others to join</h2>
        </div>
        <div v-if="isGameFinished" class="finish">
            <h2 class="title">Game Over</h2>
            <p v-if="isSessionPlayerWinner">Congratulations <b>Player {{ gameWinnerId + 1 }}</b>, you won the game! <br>Fancy another?</p>
            <p v-else>Sorry, but <b>Player {{ gameWinnerId + 1 }}</b> won the game! <br>Fancy another?</p>
            <button class="btn btn-primary" @click.prevent="finishGame">Finish</button>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();

const startGame = async () => {
    await store.dispatch("startGame")
}

const finishGame = async () => {
    await store.dispatch("finishGame")
}

const isGameSetup = computed(() => !isGameInitializing.value && !isGameFinished.value)

const isGameError = computed(() => {
    return store.getters.isGameError;
})

const isGameInitializing = computed(() => {
    return store.getters.isGameInitializing
})

const isGameFinished = computed(() => {
    return store.getters.isGameFinished
})

const gameWinnerId = computed(() => {
    return store.state.winningPlayerId
})

const isSessionPlayerWinner = computed(() => {
    return gameWinnerId.value == store.state.sessionPlayerId
})

</script>

<style lang="scss">
.lobby {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1 1 auto;

    border: 1px solid black;
    border-radius: 0.25rem;
    padding: 1rem;

    .title {
        text-align: center;
    }

    .welcome {
        text-align: center;
    }

    .finish {
        display: flex;
        flex-direction: column;
        flex: 0 1 auto;
        justify-content: center;
        margin-top: 1rem;
        text-align: center;
    }

    .controls {
        display: flex;
        flex-direction: row;
        flex: 1 1 auto;
        justify-content: center;
        margin-top: 1rem;
    }

    .error {
        text-align: center;
        color: red;
        margin: 1rem 0;
    }
}
</style>