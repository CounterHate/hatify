<template>
    <h1>{{ this.hate_speech_category }}</h1>
    <div>
        <!-- visualization test -->
        <Responsive class="w-full">
            <template #main="{ width }">
                <Chart direction="circular" :size="{ width, height: 500 }" :data="this.data.slice(0, 20)" :margin="{
                    left: Math.round((width - 360) / 2),
                    top: 40,
                    right: 0,
                    bottom: 40,
                }" :axis="axis" :config="{ controlHover: false }">
                    <template #layers>
                        <Pie :dataKeys="['author', 'count']" :pie-style="{ innerRadius: 50, padAngle: 0.05, colors: ['#fcba03', '#2f578f', '#8d2f8f', '#32a852', '#db4f1d', '#1dd2db']}" />
                    </template>
                    <template #widgets>
                        <Tooltip :config="{
                            author: { label: 'autor' },
                            count: { label: 'liczba wpisów' },
                        }" hideLine />
                    </template>
                </Chart>
            </template>
        </Responsive>
        <!-- end of visualization -->
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Autor</th>
                <th scope="col">Liczba wyników</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(author, index) in this.data" :key="index">
                <th scope="row">{{ index + 1 }}</th>
                <td><a :href="'/stats/author=' + author.author" target="_blank">{{ author.author }}</a></td>
                <td>{{ author.count }}</td>
                <td><a :href="'https://twitter.com/' + author.author" target="_blank">Zobacz profil</a></td>
            </tr>
        </tbody>
    </table>
</template>
<script>
import { Chart, Responsive, Pie, Tooltip } from "vue3-charts";

export default {
    props: { data: Array, hate_speech_category: String },
    components: { Chart, Responsive, Pie, Tooltip },
};
</script>
<style>
</style>