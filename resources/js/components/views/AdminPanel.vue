<template >
    Stream status <span class="badge badge-pill bg-success" v-if="this.stream_alive == true">Aktywny</span>
    <span class="badge badge-pill bg-danger" v-if="this.stream_alive == false">Wyłączony</span>
    <button class="btn btn-primary btn-sm" style="margin-left: 8px" v-if="this.stream_alive == false"
        @click="toggleStream">Uruchom</button>
    <button class="btn btn-primary btn-sm" style="margin-left: 8px" v-if="this.stream_alive == true"
        @click="toggleStream">Zatrzymaj</button>
    <br />
    <br />
    <table class="table" v-show="this.phrases.length > 0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Słowo</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(phrase, index) in this.phrases" :key="index">
                <th scope="row">{{index + 1}}</th>
                <td>{{phrase.phrase}}</td>
                <td><button class="btn btn-danger btn-sm" @click="deletePhrase(phrase.id, index)">Usuń</button></td>
            </tr>
        </tbody>
    </table>
</template>
<script>
import { checkStreamAlive, getPhrasesFromIndex, updateStreamAlive, deletePhraseFromIndex } from "../../es.js";
export default {
    data() {
        return {
            url: process.env.MIX_ES,
            phrases_index: process.env.MIX_PHRASES_INDEX,
            alive_index: process.env.MIX_ALIVE_INDEX,
            auth: {
                username: process.env.MIX_ES_USER,
                password: process.env.MIX_ES_PASS,
            },
            stream_alive: null,
            phrases: []
        }
    },
    methods: {
        checkStreamAlive,
        getPhrasesFromIndex,
        updateStreamAlive,
        deletePhraseFromIndex,
        async toggleStream() {
            await updateStreamAlive(
                this.url + "/" + this.alive_index,
                this.auth,
                !this.stream_alive
            )
            await checkStreamAlive(
                this.url + "/" + this.alive_index,
                this.auth
            ).then((data) => {
                this.stream_alive = data.is_alive
            })

        },
        async deletePhrase(id, index) {
            await deletePhraseFromIndex(
                this.url + "/" + this.phrases_index,
                this.auth,
                id
            ).then(response => {
                this.toggleStream()
                this.phrases.splice(index, 1)
            })


        }
    },
    async mounted() {
        await checkStreamAlive(
            this.url + "/" + this.alive_index,
            this.auth
        ).then((data) => {
            this.stream_alive = data.is_alive
        })

        await getPhrasesFromIndex(
            this.url + "/" + this.phrases_index,
            this.auth
        ).then((data) => {
            this.phrases = data
        })

    }
}
</script>
<style>
</style>