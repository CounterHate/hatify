export const hco = [
    "Osoby należące do mniejszości etnicznych lub narodowych w Polsce",
    "Osoby z doświadczeniem migracji",
    "Osoby o innym kolorze skóry",
    "Osoby LGBTQ+",
    "antysemityzm",
    "Romowie",
    "Osoby należące do mniejszości religijnych w Polsce",
    "Kobiety",
    "Osoby starsze",
    "Osoby z niepełnosprawnościami",
];

export const size_options = [
    { value: "10", text: "10" },
    { value: "50", text: "50" },
    { value: "100", text: "100" },
    { value: "500", text: "500" },
    { value: "1000", text: "1000" },
    { value: "5000", text: "5000" },
    { value: "10000", text: "10000" },
];

export const media_options = [
    { value: "twitter", text: "twitter" },
    { value: "facebook", text: "facebook" },
];

export const sort_options = [
    { value: "ascending", text: "Od najstarszych" },
    { value: "descending", text: "Od najnowszych" },
    { value: "ascending_score", text: "Od najmniejszej dokładności" },
    { value: "descending_score", text: "Od największej dokładności" },
]

export const columns = [
    {
      label: "Czy jest mową nienawiści",
      field: "is_hate_speech",
    },
    {
      label: "Czy to retweet",
      field: "is_retweet",
    },
    {
      label: "Login autora",
      field: "author_username",
    },
    {
      label: "Autor",
      field: "author_name",
    },
    {
      label: "Treść",
      field: "content",
    },
    {
      label: "Data wysłania",
      field: "posted_utime",
      dataFormat: dateFormat,
    },
    {
      label: "Słowa kluczowe",
      field: "keywords",
      dataFormat: keywordsFormat,
    },
    {
      label: "Link",
      field: "url",
    },
  ]

function dateFormat(value) {
    return new Date(+value);
}

function keywordsFormat(value) {
    if (value == null) return value;
    return value.toString();
}