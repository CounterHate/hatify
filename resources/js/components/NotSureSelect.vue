<template>
    <div v-if="show" style="padding-top: 4px">
        <hr />
        <b> Dlaczego klasyfikacja jest dla Ciebie niejasna?</b>
        <select class="form-select form-select-sm" v-model="selected_reason">
            <option v-for="(option, index) in options" :key="index">{{
                option
            }}</option>
        </select>
        <input
            class="form-control"
            v-if="selected_reason == 'Inny powód: …'"
            v-model="other_reason"
            type="text"
            placeholder="Inny powód..."
        />
        <div style="padding-top: 4px;">
            <button
                type="button"
                class="btn btn-primary"
                @click="processNotSure"
            >
                Zapisz
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        show: Boolean
    },
    emits: ['process_not_sure'],
    data () {
        return {
            options: [
                'Brak kontekstu (np. post jest komentarzem do posta głównego, który został już usunięty)',
                'Niejasny kontekst',
                'Niejasne znaczenie, w jakim autor/autorka posta używa danego słowa',
                'Niejasne, czy dane określenie jest dostatecznie obraźliwe, by stanowić samoistny przypadek mowy nienawiści',
                'Inny powód: …'
            ],
            selected_reason:
                'Brak kontekstu (np. post jest komentarzem do posta głównego, który został już usunięty)',
            other_reason: ''
        }
    },
    methods: {
        processNotSure () {
            this.$emit('process_not_sure', {
                selected_reason: this.selected_reason,
                other_reason: this.other_reason
            })
        }
    }
}
</script>

<style></style>
