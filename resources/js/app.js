require('./bootstrap');

require('alpinejs');

import { createApp } from 'vue';
import ShowTweets from './components/ShowTweets.vue';
import RandomTweet from './components/RandomTweet.vue';

const app = createApp({ components: { ShowTweets, RandomTweet } });

app.mount('#app');
