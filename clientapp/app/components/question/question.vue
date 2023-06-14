<template>
    <div class="question">
        <h3>Question</h3>
        <div class="content">
            <form @submit.prevent="submitMoodleForm">
                <div v-html="question.html"></div>
            </form>
        </div>
    </div>
</template>
<script setup>
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex"

const store = useStore();

const submitMoodleForm = ((el) => {
    console.log(el)
    let data = new FormData(el.target)
    console.log(data);
})

const question = computed(() => store.state.activeQuestion)

</script>
<style lang="scss">
.question {
    flex: 0 1 auto;
    display: flex;
    flex-direction: column;
    background-color: white;
    padding: 1rem;
    border-radius: 0.25rem;

    .content {
        .content {
            margin: 0;
        }

        .info {
            display: none;
        }
    }
}
</style>