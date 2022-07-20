import axios from "axios";

export async function getRandomFBdata(size, url, auth, author_id) {
    var query;
    if (author_id) {
        query = {
            size: size,
            query: {
                function_score: {
                    random_score: {},
                    query: {
                        bool: {
                            must: [{
                                match: {
                                    author_id: author_id
                                }
                            }],
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
                        }
                    }
                }
            }
        }
    } else {
        query = {
            size: size,
            query: {
                function_score: {
                    random_score: {},
                    query: {
                        bool: {
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
                        }
                    }
                }
            }
        }
    }

    var data;
    await axios
        .post(url + "/_search", query, {
            auth: auth,
        })
        .then((response) => {
            var posts = [];
            response.data.hits.hits.forEach(p => {
                posts.push(p._source);
                // console.log(t._source.tweet_id)
            });
            data = posts
        })
        .catch((error) => {
            console.error(error);
            data = [];
        });
    return data
}

export async function getRandomTweets(size, url, auth, username_to_anotation) {
    {
        var query;

        query = {
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

        if (username_to_anotation != '') query.query.function_score.query.bool.must.push({
            match: {
                author_username: username_to_anotation
            }
        })

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

// probably can remove, need to double check
export async function getUserFbData(url, auth, author_id) {
    var query = {
        size: 10000,
        query: {
            match: {
                author_id: author_id,
            }
        }
    };

    var fb_data = [];
    await axios.post(url + "/_search", query, {
        auth: auth,
    }).then((response) => {
        console.log(response.data)
        response.data.hits.hits.forEach(d => {
            fb_data.push(d._source)
        })
    }).catch((error) => {
        console.log(error)
    })
    return fb_data
}

// probably can remove, need to double check
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
        console.log(response.data)
        response.data.hits.hits.forEach(t => {
            tweets.push(t._source)
        })
    }).catch((error) => {
        console.log(error)
    })
    return tweets
}

export async function updateInIndex(url, auth, data, is_hate_speech) {
    data.is_hate_speech = is_hate_speech;
    console.log(data)
    await axios
        .post(url, data, {
            auth: auth,
        })
        .then((response) => {
            // console.log(response)
        })
        .catch((error) => {
            console.error(error);
        });

}

export async function getTweets(url, auth, size = 10, content = null, author_username = null) {
    var query = {
        size: size,
        query: {
            bool: {
                must: [{
                    match: {
                        lang: "pl",
                    },

                }, {
                    match: {
                        is_retweet: false,
                    },

                }],

            },
        }
    }

    if (content) query.query.bool.must.push({ match: { content: content } })
    if (author_username) query.query.bool.must.push({ match: { author_username: author_username } })

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

export async function getTweet(url, auth, tweet_id) {
    let data;

    await axios.get(url + "/_doc/" + tweet_id, {
        auth: auth,
    }).then((response) => {
        data = response.data._source
    }).catch((error) => {
        console.log(error)
        data = null;
    })
    return data
}

export async function getFbData(url, auth, size = 10, content = null, author_id = null) {
    var query = {
        size: size,
        query: {
            bool: {
                must: []
            }
        }
    }

    if (content) query.query.bool.must.push({ match: { content: content } })
    if (author_id) query.query.bool.must.push({ match: { author_id: author_id } })

    console.log(query)
    var fb_data = []
    console.log(url + "/_search")
    await axios.post(url + "/_search", query, {
        auth: auth,
    }).then((response) => {
        response.data.hits.hits.forEach(t => {
            fb_data.push(t._source)
        })
    }).catch((error) => {
        console.log(error)
    })
    return fb_data
}

export async function getFbRecord(url, type, auth, record_id) {
    let data;

    await axios.get(url + "/" + type + "/" + record_id, {
        auth: auth,
    }).then((response) => {
        data = response.data._source
    }).catch((error) => {
        console.log(error)
        data = null;
    })
    return data
}