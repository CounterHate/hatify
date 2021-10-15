<template>
    <br />
    <div class="card">
        <!-- <div class="card-header">Quote</div> -->
        <div class="card-body">
            <h5 class="card-title">{{ data.author_username }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">
                {{ new Date(data.posted_utime * 1000) }}
            </h6>
            <p>{{ data.content }}</p>
            <a
                :href="
                    'https://twitter.com/' +
                    data.author_username +
                    '/status/' +
                    data.tweet_id
                "
                class="btn btn-primary"
                >Zobacz tweet</a
            >
        </div>
    </div>
</template>

<script>
export default {
    props: {
        data: Object,
    },
};
</script>

<style></style>
