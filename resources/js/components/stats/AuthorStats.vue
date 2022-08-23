<template>
    <h1>{{ this.author }}</h1>
    <div>
        <!-- visualization test -->
        <Responsive>
            <template #main="{ width }">
                <Chart direction="circular" :size="{ width, height: 500 }" :data="this.data" :margin="{
                    left: Math.round((width - 360) / 2),
                    top: 40,
                    right: 0,
                    bottom: 40,
                }" :axis="axis" :config="{ controlHover: false }">
                    <template #layers>
                        <Pie :dataKeys="['author', 'count']" :pie-style="{ innerRadius: 50, padAngle: 0.05, colors: ['#fcba03', '#2f578f', '#8d2f8f', '#32a852', '#db4f1d', '#1dd2db'] }" />
                    </template>
                    <template #widgets>
                        <Tooltip :config="{
                            name: { label: 'kategoria' },
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
                <th scope="col">Kategoria</th>
                <th scope="col">Liczba wyników</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(category, index) in this.data" :key="index">
                <th scope="row">{{ index + 1 }}</th>
                <td><a :href="'/stats/hate_category=' + category.name" target="_blank">{{ category.name }}</a></td>
                <td>{{ category.count }}</td>
            </tr>
        </tbody>
    </table>
</template>
<script>
import { Chart, Responsive, Pie, Tooltip } from "vue3-charts";

export default {
    props: { data: Array, author: String },
    components: { Chart, Responsive, Pie, Tooltip },
};
</script>
<style>
</style>