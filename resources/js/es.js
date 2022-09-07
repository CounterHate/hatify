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

export async function getTweets(url, auth, size = 10, content = null, author_username = null, from_date = null, to_date = null, min_score = 0) {
    var query = {
        size: size,
        min_score: min_score,
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

    var gte;
    var lte;

    if (from_date) gte = new Date(from_date).getTime() / 1000;
    if (to_date) lte = new Date(to_date).getTime() / 1000;

    if (content) query.query.bool.must.push({ match: { content: content } })
    if (author_username) query.query.bool.must.push({ match: { author_username: author_username } })
    if (from_date || to_date) query.query.bool.must.push({
        range: {
            posted_utime: {
                gte: gte,
                lte: lte
            }
        }
    })

    var tweets = []

    await axios.post(url + "/_search", query, {
        auth: auth,
    }).then((response) => {
        response.data.hits.hits.forEach(t => {
            var new_tweet = t._source;
            new_tweet.score = t._score;
            new_tweet.url = "https://twitter.com/" + new_tweet.author_username + "/status/" + new_tweet.tweet_id
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

export async function getHateCategories(url, auth) {
    var hate_categories = []

    await axios.get(url + "/_search", {
        auth: auth,
    }).then((response) => {
        response.data.hits.hits.forEach(hc => {
            hate_categories.push(hc._source);
        })
    }).catch((error) => {
        console.log(error)
    })

    return hate_categories
}

export async function countTweetsFromCategory(url, auth, category = null, from_date = null, to_date = null) {
    var query = {
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

    var gte;
    var lte;

    if (category) {
        // convert category array to string
        var hate_words_string = ""
        category.words.forEach(w => hate_words_string += (w + ', '))
        query.query.bool.must.push({
            match: {
                keywords: hate_words_string,
            },

        })
    }
    if (from_date) gte = new Date(from_date).getTime() / 1000;
    if (to_date) lte = new Date(to_date).getTime() / 1000;

    if (from_date || to_date) query.query.bool.must.push({
        range: {
            posted_utime: {
                gte: gte,
                lte: lte
            }
        }
    })

    console.log(category.category)
    console.log(query)
    var result = 0
    await axios.post(url + "/_count", query, {
        auth: auth,
    }).then((response) => {
        result = response.data.count
    }).catch((error) => {
        console.log(error)
    })

    return result

}

export async function countTweetsWithWord(url, auth, word, from_date = null, to_date = null) {
    var query = {
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

                }, {
                    match: {
                        keywords: word,
                    },

                }],

            },
        }
    }

    var gte;
    var lte;

    if (from_date) gte = new Date(from_date).getTime() / 1000;
    if (to_date) lte = new Date(to_date).getTime() / 1000;

    if (from_date || to_date) query.query.bool.must.push({
        range: {
            posted_utime: {
                gte: gte,
                lte: lte
            }
        }
    })

    var result = 0
    await axios.post(url + "/_count", query, {
        auth: auth,
    }).then((response) => {
        result = response.data.count
    }).catch((error) => {
        console.log(error)
    })

    return result
}

export async function getAuthorsForCategory(url, auth, category, from_date = null, to_date = null) {
    var hate_words_string = ""
    category.words.forEach(w => hate_words_string += (w + ', '))
    var query = {
        size: 10000,
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

                }, {
                    match: {
                        keywords: hate_words_string,
                    },

                }],

            },
        }
    }

    var gte;
    var lte;

    if (from_date) gte = new Date(from_date).getTime() / 1000;
    if (to_date) lte = new Date(to_date).getTime() / 1000;

    if (from_date || to_date) query.query.bool.must.push({
        range: {
            posted_utime: {
                gte: gte,
                lte: lte
            }
        }
    })

    var data = []
    await axios.post(url + "/_search", query, {
        auth: auth,
    }).then((response) => {
        response.data.hits.hits.forEach(t => {
            if (!data.includes(t._source.author_username))
                data.push(t._source.author_username)
        })
    }).catch((error) => {
        console.log(error)
    })

    return data
}

export async function countAuthorTweetsForCategory(url, auth, author, category, from_date = null, to_date = null) {
    var hate_words_string = ""
    category.words.forEach(w => hate_words_string += (w + ', '))
    var query = {
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

                    }, {
                        match: {
                            keywords: hate_words_string,
                        },

                    },
                    {
                        match: {
                            author_username: author,
                        },

                    }
                ],

            },
        }
    }

    var gte;
    var lte;

    if (from_date) gte = new Date(from_date).getTime() / 1000;
    if (to_date) lte = new Date(to_date).getTime() / 1000;

    if (from_date || to_date) query.query.bool.must.push({
        range: {
            posted_utime: {
                gte: gte,
                lte: lte
            }
        }
    })

    var result = 0
    await axios.post(url + "/_count", query, {
        auth: auth,
    }).then((response) => {
        result = response.data.count
    }).catch((error) => {
        console.log(error)
    })
    return result
}

export async function countAuthorTweetsForWord(url, auth, author, word, from_date = null, to_date = null) {
    var query = {
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

                    }, {
                        match: {
                            keywords: word,
                        },

                    },
                    {
                        match: {
                            author_username: author,
                        },

                    }
                ],

            },
        }
    }

    var gte;
    var lte;

    if (from_date) gte = new Date(from_date).getTime() / 1000;
    if (to_date) lte = new Date(to_date).getTime() / 1000;

    if (from_date || to_date) query.query.bool.must.push({
        range: {
            posted_utime: {
                gte: gte,
                lte: lte
            }
        }
    })

    var result = 0
    await axios.post(url + "/_count", query, {
        auth: auth,
    }).then((response) => {
        result = response.data.count
    }).catch((error) => {
        console.log(error)
    })
    return result
}

export async function getCategoryGrowth(url, auth, category, from_date = null, to_date = null) {
    var query = {
        size: 10000,
        query: {
            match: {
                entity_value: category,
            },
        }
    }

    var gte;
    var lte;

    if (from_date) gte = new Date(from_date).getTime() / 1000;
    if (to_date) lte = new Date(to_date).getTime() / 1000;

    if (from_date || to_date) query.query.bool.must.push({
        range: {
            posted_utime: {
                gte: gte,
                lte: lte
            }
        }
    })

    var data = []
    await axios.post(url + "/_search", query, {
        auth: auth,
    }).then((response) => {
        response.data.hits.hits.sort(
            (a, b) => a._source.date - b._source.date
        )
        response.data.hits.hits.forEach(d => {
            var tmp = d._source
            tmp.date = new Date(tmp.date * 1000).toLocaleDateString()
            if (d._source.entity_value == category) data.push(tmp)
        })
    }).catch((error) => {
        console.log(error)
    })
    return data

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