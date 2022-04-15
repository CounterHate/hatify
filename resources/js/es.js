import axios from "axios";

export async function getRandomFBdata(size, url, auth, author_id) {
    var query;
    if (author_id) {
        query = {
            size: 100,
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
        if (username_to_anotation != '') {
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
                                    {
                                        match: {
                                            author_username: username_to_anotation
                                        }
                                    }
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
        } else {
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
        }

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

export async function getSimilarFbData(url, auth, data_id, content, index) {
    var query;
    var fb_content;
    var again = false
    if (content == null) {
        if (index == 'fb_posts') {
            query = {
                query: {
                    match: {
                        post_id: data_id,
                    },
                }
            };
        } else {
            query = {
                query: {
                    match: {
                        comment_id: data_id,
                    },
                }
            };
        }

        console.log("query: ", query)
        console.log("url: ", url + "/" + index)
        await axios.post(url + "/" + index + "/_search", query, {
            auth: auth,
        }).then((response) => {
            console.log(response.data)
            if (response.data.hits.total == 0) again = true;
            else fb_content = response.data.hits.hits[0]._source.content
        }).catch((error) => {
            again = true
        })
    } else {
        fb_content = content
    }

    if (index == 'fb_posts') index = 'fb_comments';
    else index = 'fb_posts'

    if (again) {
        if (content == null) {
            if (index == 'fb_posts') {
                query = {
                    query: {
                        match: {
                            post_id: data_id,
                        },
                    }
                };
            } else {
                query = {
                    query: {
                        match: {
                            comment_id: data_id,
                        },
                    }
                };
            }

            console.log("query: ", query)

            await axios.post(url + "/" + index + "/_search", query, {
                auth: auth,
            }).then((response) => {
                console.log(response.data)
                if (response.data.hits.total == 0) again = true;
                else fb_content = response.data.hits.hits[0]._source.content
            }).catch((error) => {
                console.log(error)
            })
        } else {
            fb_content = content
        }
    }


    query = {
        query: {
            match: {
                content: fb_content,
            },
        }
    }
    var fb_data = []

    await axios.post(url + "/" + index + "/_search", query, {
        auth: auth,
    }).then((response) => {
        console.log(response.data)
        response.data.hits.hits.forEach(t => {
            fb_data.push(t._source)
        })
    }).catch((error) => {
        console.log(error)
    })
    return fb_data
}

export async function getSimilarTweets(url, auth, tweet_id, content) {
    var query;
    var tweet_content;
    if (content == null) {
        query = {
            query: {
                size:100,
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
