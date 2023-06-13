<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="shadow rounded bg-white p-3">
                    <h2>Vue Boilerplate</h2>
                    <div>
                        <ul>
                            <li v-for="category in respData" :key="category.id">
                                {{ category.name }}
                            </li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary rounded">Schließen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, inject, onMounted } from "vue";

export default {
    setup() {
        let respData = ref([]);

        onMounted(async () => {
            await getData();
        });

        const getData = async () => {
            try {
                respData.value = [{ id: 1, name: "Test" }];
            } catch { }
        };

        return {
            respData,
            getData,
        };
    },
};
</script>

<style></style>
