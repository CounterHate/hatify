require('./bootstrap');

require('alpinejs');

import { createApp } from 'vue';
import ShowTweets from './components/ShowTweets.vue';
import RandomTweet from './components/RandomTweet.vue';
import VerifyTweet from './components/VerifyTweet.vue';

const app = createApp({ components: { ShowTweets, RandomTweet, VerifyTweet } });

app.mount('#app');
