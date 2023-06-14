<template>
    <div class="player">
        <div class="token">
            <div class="name">
                PLAYER
            </div>
        </div>
    </div>
</template>
<script setup></script>
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

        .name {
            flex: 0 1 auto;
        }
    }
}
</style>