require('./bootstrap');

require('alpinejs');

import { createApp } from 'vue';
import ShowTweets from './components/ShowTweets.vue';
import RandomTweet from './components/RandomTweet.vue';
import VerifyTweet from './components/VerifyTweet.vue';
import SimilarTweets from './components/SimilarTweets.vue';
import UserTweets from './components/UserTweets.vue';
import AiCheck from './components/AiCheck.vue';

const app = createApp({ components: { ShowTweets, RandomTweet, VerifyTweet, SimilarTweets, UserTweets, AiCheck } });

app.mount('#app');