<template>
    <div class="lobby">
        <h2 class="title">Game of Knowledge</h2>
        <p v-if="!isGameError" class="welcome">Welcome to the new and exciting Game of Knowledge. <br>When you're ready,
            please click the "Start New
            Game" button below!</p>
        <div class="controls">
            <button class="btn btn-primary" @click.prevent="startGame">Start Game</button>
        </div>
        <p v-if="isGameError" class="error">Whoopsie-daisy! <br />We're sorry, but something didn't work as expected when
            starting your game. <br />Please give it a few seconds, then try again.</p>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();

const startGame = async () => {
    await store.dispatch("startGame")
}

const getState = async () => {
    await store.dispatch("requestGetState")
}

const performAction = async () => {
    await store.dispatch("requestPerformAction")
}

const isGameError = computed(() => {
    return store.getters.isGameError;
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