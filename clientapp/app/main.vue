<template>
    <div class="mod-gameofknowledge">
        <board v-if="isGameActive">
        </board>
        <lobby v-else></lobby>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, defineProps, computed } from "vue";
import { useStore } from "vuex";
import board from "@/app/components/board/board"
import lobby from "@/app/components/lobby/lobby"

const props = defineProps({
    coursemoduleid: {
        type: String
    },
    contextid: {
        type: Number
    }
})

const store = useStore();

onMounted(async () => {
    store.commit("setContextId", { contextid: props.contextid })
    store.commit("setCourseModuleId", { coursemoduleid: props.coursemoduleid })
    poll()
});

const poll = async () => {
    store.dispatch("getState")
    setTimeout(poll, 1000)
}

const isGameActive = computed(() => {
    return store.getters.isGameActive
})
</script>

<style lang="scss"></style>
