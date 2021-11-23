<template>
    <div class="row">
        <div class="col">
            <h2>Czym jest mowa nienawiści</h2>
            Mowa nienawiści to wszelkie formy nienawistnych wypowiedzi
            kierowanych do ludzi ze względu na ich daną (przyrodzoną,
            niezbywalną) cechę ( kolor skóry, język, religię lub wyznanie,
            pochodzenie etniczne czy narodowe, wiek, niepełnosprawność, płeć,
            społeczno-kulturowa tożsamość płciowa i orientacja psychoseksualna
            ).
            <br />
            <br />
            <h2>
                Jeśli masz problem ze stwierdzenie, czy dana wypowiedź to mowa
                nienawiści, to zastanów się czy:
            </h2>
            <ul>
                <li>
                    odnosi się do grupy/osoby w związku jakąś jej cechą, -
                    zawiera obraźliwe określenia grup/osób,
                </li>
                <li>nawołuje do agresji, podżega do nienawiści,</li>
                <li>zawiera groźby,</li>
                <li>zawiera wyrazy nienawiści,</li>
                <li>zawiera wyrazy obrzydzenia,</li>
                <li>
                    zawiera wyrazy obrzydzenia,pogardy, dehumanizacji,
                    znieważenia,
                </li>
                <li>
                    zawiera negatywne stereotypy, tezy oczerniające lub
                    szkalujące,
                </li>
                <li>
                    usprawiedliwia agresję, dyskryminację, gorsze traktowanie
                    jakieś osoby/grupy, - wyraża agresywny nacjonalizm
                </li>
            </ul>
        </div>
        <div class="col">
            <tweet
                v-if="this.tweet != null"
                :random="true"
                :data="this.tweet"
                @process_tweet="handleProcessTweet"
                @skip_tweet="handleSkipTweet"
            ></tweet>
        </div>
    </div>
</template>

<script>
import Tweet from "./Tweet.vue";
import { getRandomTweets } from "../es.js";
export default {
    components: { Tweet },
    props: {
        user: Number,
    },
    data() {
        return {
            is_loading: false,
            tweet: null,
            url: process.env.MIX_ES,
            index: process.env.MIX_INDEX,
            auth: {
                username: process.env.MIX_ES_USER,
                password: process.env.MIX_ES_PASS,
            },
        };
    },
    async mounted() {
        await getRandomTweets(1, this.url + "/" + this.index, this.auth).then(
            (result) => (this.tweet = result[0])
        );
    },
    methods: {
        getRandomTweets,
        async handleSkipTweet() {
            await getRandomTweets(1, this.url, this.auth).then(
                (result) => (this.tweet = result[0])
            );
        },
        async handleProcessTweet(data) {
            if (data.is_hate_speech) {
                var tweet = {
                    tweet_id: data.tweet.tweet_id,
                    author: data.tweet.author_username,
                    content: data.tweet.content,
                    date: (data.tweet.posted_utime * 1000).toString(),
                    topics: JSON.stringify({
                        user: this.user,
                        topics: data.topics,
                    }),
                };
                axios
                    .post("api/tweets", tweet)
                    .then((response) => console.log(response.data))
                    .catch((error) => console.error(error));
            }

            await getRandomTweets(1, this.url, this.auth).then(
                (result) => (this.tweet = result[0])
            );
        },
    },
};
</script>

<style></style>
