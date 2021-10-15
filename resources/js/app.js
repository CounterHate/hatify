require('./bootstrap');

require('alpinejs');

import { createApp } from 'vue';
import ShowTweets from './components/ShowTweets.vue';

const app = createApp({ components: { ShowTweets } });

app.mount('#app');
