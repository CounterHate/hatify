import axios from "axios";

export async function getRandomTweets(size, url, auth) {
    {
        var query = {
            size: size,
            query: {
                function_score: {
                    random_score: {},
                    query: {
                        bool: {
                            must: [{
                                    match: {
                                        lang: "pl",
                                    },
                                },
                                {
                                    match: {
                                        is_retweet: false,
                                    },
                                },
                            ],
                            must_not: [{
                                    match: {
                                        is_hate_speech: true,
                                    },
                                },
                                {
                                    match: {
                                        is_hate_speech: false,
                                    },
                                },
                            ],
                        },
                    },
                },
            },
        };
        var data;
        await axios
            .post(url + "/_search", query, {
                auth: auth,
            })
            .then((response) => {
                var tweets = [];
                response.data.hits.hits.forEach(t => {
                    tweets.push(t._source);
                    // console.log(t._source.tweet_id)
                });
                data = tweets
            })
            .catch((error) => {
                console.error(error);
                data = [];
            });
        return data
    }
}

export async function getUserTweets(url, auth, author_username) {
    var query = {
        size: 10000,
        query: {
            bool: {
                must: [{
                        match: {
                            lang: "pl",
                        },
                    },
                    {
                        match: {
                            author_username: author_username,
                        },
                    },
                ],

            },
        }
    };
    var tweets = []
    await axios.post(url + "/_search", query, {
        auth: auth,
    }).then((response) => {
        response.data.hits.hits.forEach(t => {
            tweets.push(t._source)
        })
    }).catch((error) => {
        console.log(error)
    })
    return tweets
}


export async function getSimilarTweets(url, auth, tweet_id, content) {
    var query;
    var tweet_content;
    if (content == null) {
        query = {
            query: {
                bool: {
                    must: [{
                            match: {
                                lang: "pl",
                            },
                        },
                        {
                            match: {
                                tweet_id: tweet_id,
                            },
                        },
                    ],

                },
            }
        };
        await axios.post(url + "/_search", query, {
            auth: auth,
        }).then((response) => {
            tweet_content = response.data.hits.hits[0]._source.content
        }).catch((error) => {
            console.log(error)
        })
    } else {
        tweet_content = content
    }

    query = {
        query: {
            bool: {
                must: [{
                        match: {
                            lang: "pl",
                        },
                    },
                    {
                        match: {
                            content: tweet_content,
                        },
                    },
                ],

            },
        }
    }
    var tweets = []

    await axios.post(url + "/_search", query, {
        auth: auth,
    }).then((response) => {
        response.data.hits.hits.forEach(t => {
            tweets.push(t._source)
        })
    }).catch((error) => {
        console.log(error)
    })
    return tweets
}

export async function updateInIndex(url, auth, tweet, is_hate_speech) {
    tweet.is_hate_speech = is_hate_speech;
    await axios
        .post(url + "/_doc/" + tweet.tweet_id, tweet, {
            auth: auth,
        })
        .then((response) => {
            console.log(response)
        })
        .catch((error) => {
            console.error(error);
        });
}