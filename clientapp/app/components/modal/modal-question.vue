<template>
    <div class="modal-question">
        <slot></slot>
    </div>
</template>
<script setup></script>
<style lang="scss">
.modal-question {
    position: absolute;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
    opacity: 1;

    &::before {
        display: block;
        content: "";
        z-index: 1;
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0.5;
        background-color: black;
    }

    >.question {
        z-index: 3;
        max-width: 100%;
        max-height: 100%;
    }
}
</style>