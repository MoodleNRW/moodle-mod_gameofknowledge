<template>
    <div class="lobby">
        <h2>Game of Knowledge</h2>
        <button @click.prevent="startGame">Start New Game</button>
    </div>
</template>

<script setup>
import { useStore } from "vuex";

const store = useStore();

const startGame = async () => {
    await store.dispatch("requestGameStart")
    console.log("Start")
}
</script>

<style lang="scss">
.lobby {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
</style>