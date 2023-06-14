<template>
    <div class="player">
        <div class="token" :class="{ 'is-player': isSessionPlayer }">
            <div class="name">
                PLAYER {{ player.number + 1 }}
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, defineProps } from 'vue';
import { useStore } from "vuex";

const store = useStore();

const props = defineProps({
    id: {
        type: Number
    }
})

const player = computed(() => store.getters.getPlayerById(props.id))

const isSessionPlayer = computed(() => store.state.sessionPlayerId == player.value.number)

</script>
<style lang="scss">
.player {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: absolute;
    width: 100%;
    height: 100%;

    .token {
        width: 75%;
        height: 75%;
        border-radius: 50%;
        background-color: red;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        &.is-player {
            background-color: orange;
        }

        .name {
            flex: 0 1 auto;
        }
    }
}
</style>