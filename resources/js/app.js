require('./bootstrap');

require('alpinejs');

import { createApp } from 'vue';
import RandomTweet from './components/views/RandomTweet.vue';
import VerifyTweet from './components/views/VerifyTweet.vue';
import SearchContent from './components/views/SearchContent.vue';
import AiCheck from './components/AiCheck.vue';
import VueExcelXlsx from "vue-excel-xlsx";
import StatsView from './components/views/StatsView'
import AdminPanel from './components/views/AdminPanel.vue'

const app = createApp({ components: { RandomTweet, VerifyTweet, SearchContent, AiCheck, StatsView, AdminPanel } });

app.use(VueExcelXlsx);
app.mount('#app');

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})