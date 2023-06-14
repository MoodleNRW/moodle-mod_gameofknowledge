<template>
    <div class="lobby">
        <h2>Game of Knowledge</h2>
        <div class="controls">
            <button class="btn btn-primary" @click.prevent="test">Test</button>
            <button class="btn btn-primary" @click.prevent="startGame">Start Game</button>
            <button class="btn btn-primary" @click.prevent="getState">Get State</button>
            <button class="btn btn-primary" @click.prevent="performAction">Perform Action</button>
        </div>
    </div>
</template>

<script setup>
import { useStore } from "vuex";

const store = useStore();

const test = async () => {
    await store.dispatch("requestTest")
    console.log("Start")
}

const startGame = async () => {
    await store.dispatch("requestStartGame")
}

const getState = async () => {
    await store.dispatch("requestGetState")
}

const performAction = async () => {
    await store.dispatch("requestPerformAction")
}
</script>

<style lang="scss">
.lobby {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1 1 auto;

    .controls {
        display: flex;
        flex-direction: row;
        flex: 1 1 auto;
        justify-content: space-between;
        margin-top: 1rem;
    }
}
</style>