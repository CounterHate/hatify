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

        // console.log('elo', query)
        // console.log('elo', url + "/_search")
        var data;
        await axios
            .post(url + "/_search", query, {
                auth: auth,
            })
            .then((response) => {
                var tweets = [];
                response.data.hits.hits.forEach(t => {
                    tweets.push(t._source);
                    // console.log('elo', t._source)
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

export async function getPhrasesFromIndex(url, auth) {
    var query = {
        size: 10000
    }

    var data = []
    await axios.post(url + "/_search", query, {
        auth: auth,
    }).then((response) => {
        console.log(response)
        response.data.hits.hits.forEach(d => {
            var tmp = {
                id: d._id,
                phrase: d._source.phrase
            }
            data.push(tmp)
        })
    }).catch((error) => {
        console.log(error)
    })
    return data
}

export async function checkStreamAlive(url, auth) {
    var data = []
    await axios.get(url + "/_doc/1", {
        auth: auth,
    }).then((response) => {
        data = response.data._source
    }).catch((error) => {
        console.log(error)
    })
    return data
}

export async function updateStreamAlive(url, auth, is_alive) {
    var query = {
        is_alive: is_alive
    }

    await axios.put(url + "/_doc/1", query, {
        auth: auth,
    }).then((response) => {
        console.log(response.data)
    }).catch((error) => {
        console.log(error)
    })
}

export async function deletePhraseFromIndex(url, auth, id) {
    await axios.delete(url + "/_doc/" + id, {
        auth: auth,
    }).then((response) => {
        console.log(response.data)
        return true
    }).catch((error) => {
        console.log(error)
        return false
    })
}

export async function getDataForQuery(url, auth, params) {
    // console.log(params)
    let query = {
        "size": 10000,
        "query": {
            "bool": {
                "must": [{
                        "match": {
                            "lang": "pl"
                        }
                    },
                    {
                        "match": {
                            "is_retweet": false
                        }
                    }
                ],
            }
        },
        "aggs": {
            "authors": {
                "terms": {
                    "field": "author_username.keyword",
                    "size": 10000
                }
            },
            "words": {
                "terms": {
                    "field": "keywords.keyword",
                    "size": 10000
                }
            },
            "dates": {
                "terms": {
                    "field": "date.keyword",
                    "size": 10000
                }
            },
            "categories": {
                "terms": {
                    "field": "hate_category.keyword",
                    "size": 10000
                }
            }
        }
    }

    if (params.lte || params.gte) {
        // setHours(0,0,0,0)
        let gte, lte
        if (params.gte) {
            gte = new Date(params.gte);
            gte = new Date(gte.getFullYear(), gte.getMonth(), gte.getDate()).getTime() / 1000;
        }
        if (params.lte) {
            lte = new Date(params.lte);
            lte = new Date(lte.getFullYear(), lte.getMonth(), lte.getDate() + 1).getTime() / 1000;
        }
        query.query.bool.filter = { range: { posted_utime: { lte: lte, gte: gte } } }
    }
    if (params.content != "" && params.declinations != "") query.query.bool.must.push({ match: { content: params.content + ', ' + params.declinations } })
    else if (params.content == "" && params.declinations != "") query.query.bool.must.push({ match: { content: params.declinations } })
    else if (params.content != "" && params.declinations == "") query.query.bool.must.push({ match: { content: params.content } })
    if (params.author_username) query.query.bool.must.push({ match: { author_username: params.author_username } })
    if (params.min_score) query.min_score = params.min_score
    if (params.hate_categories.length > 0) {
        let categories_string = ""
        params.hate_categories.forEach((el, index) => {
            if (index < params.hate_categories.length - 1) categories_string += (el + ", ")
            else categories_string += el
        });
        query.query.bool.must.push({ match: { hate_category: categories_string } })
    }

    let tweets = []
    let total_count = 0
    let stats = {}

    // console.log(query)

    await axios.post(url, query, {
        auth: auth,
    }).then((response) => {
        // console.log(response)
        total_count = response.data.hits.total
        stats = response.data.aggregations
        response.data.hits.hits.forEach(t => {
            var new_tweet = t._source;
            new_tweet.score = t._score;
            new_tweet.url = "https://twitter.com/" + new_tweet.author_username + "/status/" + new_tweet.tweet_id
            tweets.push(t._source)
        })
    }).catch((error) => {
        console.log(error)
    })
    return { total: total_count, tweets: tweets, stats: stats }
}

export async function getDeclinations(url, auth) {
    let declinations = {}
    let keywords = []
    let query = {
        size: 1000
    }
    await axios.post(url, query, {
        auth: auth,
    }).then((response) => {
        response.data.hits.hits.forEach(d => {
            // if (d._source.keyword != 'rusek') {
                keywords.push(d._source.keyword)
                declinations[d._source.keyword] = d._source.words
            // }
        })
    }).catch((error) => {
        console.log(error)
    })
    return { declinations: declinations, keywords: keywords }
}