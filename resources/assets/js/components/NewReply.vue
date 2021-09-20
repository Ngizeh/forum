<template>
    <div class="mt-4">
        <div v-if="signedIn">
            <h5 class="py-3">Participate in the forum</h5>
            <div class="form-group py-2">
                <wysiwyg v-model="body" value="body" placeholder="Have something to say!" :shouldClear="completed"></wysiwyg>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit" @click="addReply">Post</button>
            </div>
        </div>
        <div v-else>
            <p class="text-center">
                Please
                <a href="/login">sign in</a> to participate in the discussion
            </p>
        </div>
    </div>
</template>

<script>
import "jquery.caret";
import "at.js";
export default {
    name: "NewReply",

    data() {
        return {
            body: "",
            errors: [],
            errorsFound: false,
            completed: false,
        };
    },
    computed: {
        singedIn() {
            return window.App.singedIn;
        },
    },
    mounted() {
        $("#body").atwho({
            at: "@",
            delay: 750,
            callbacks: {
                remoteFilter: function (query, callback) {
                    $.getJSON(
                        "/api/users",
                        { name: query },
                        function (usernames) {
                            callback(usernames);
                        }
                    );
                },
            },
        });
    },
    watch: {
        body() {
            if (this.body.length > 0) return (this.errorsFound = false);
        },
    },
    methods: {
        addReply() {
            let app = this;
            axios
                .post(location.pathname + "/replies", { body: this.body })
                .then((response) => {
                    this.completed = true;
                    this.errorsFound = false;
                    this.$emit("created", response.data);
                    flash("Your reply has been posted");
                    app.body = "";
                })
                .catch(({response}) => {
                    app.errorsFound = true;
                    this.completed = true
                    response.data.message ?  flash(response.data.message, "danger") :  flash(response.data, "danger");
                });
        },
    },
};
</script>

<style lang="css" scoped>
</style>
