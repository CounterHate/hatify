require('./bootstrap');

require('alpinejs');

import { createApp } from 'vue';
import ShowTweets from './components/ShowTweets.vue';
import RandomTweet from './components/RandomTweet.vue';
import VerifyTweet from './components/VerifyTweet.vue';
import SearchContent from './components/SearchContent.vue';
import UserTweets from './components/UserTweets.vue';
import AiCheck from './components/AiCheck.vue';
import VueExcelXlsx from "vue-excel-xlsx";
import StatsView from './components/StatsView'

const app = createApp({ components: { ShowTweets, RandomTweet, VerifyTweet, SearchContent, UserTweets, AiCheck, StatsView } });

app.use(VueExcelXlsx);
app.mount('#app');