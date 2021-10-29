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