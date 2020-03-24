<template>
	<div :id="'data-'+id">
		<div class="card my-4">
			<div class="card-header" :class="isBest ? 'bg-blue': ''">
				<div class="d-flex justify-content-between">
					<p>
                        <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"> </a> said
						<span v-text="ago"></span>...
					</p>
					<div v-if="signedIn">
						<favorite :reply="reply"></favorite>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="from-group" v-if="editing">
                    <form @submit="update">
                        <wysiwyg v-model="reply.body" value="Reply.body" class="py-2"></wysiwyg>
                        <button class="btn btn-primary btn-sm">Update</button>
                        <button class="btn btn-link btn-sm" @click="editing = false" type="button">Cancel</button>
                    </form>
				</div>

				<div v-else="show" v-html="reply.body"></div>

			</div>
            <div class="card-header d-flex" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
			    <div v-if="authorize('owns', reply)">
                    <button class="btn btn-secondary btn-sm mr-4" @click="editing = true">Edit</button>
                    <button class="btn btn-danger btn-sm mr-4" @click="destroy">Delete</button>
                </div>
                <div class="mr-4 ml-auto">
                    <button
                        v-if="authorize('owns', reply.thread) && !isBest"
                        class="btn btn-outline-primary btn-sm"
                        @click="markBestRepy"
                    >Best Reply?
                    </button>
                    <p v-if="isBest" class="text-sm">
                        This is the best Reply
                    </p>
                </div>
			</div>
		</div>
	</div>
</template>
<script>
	import Favorite from '../components/Favorite.vue';
    import Wysiwyg from '../components/Wysiwyg.vue';
	import moment from 'moment';
	export default {
		props : ['reply'],
		components : { Favorite, Wysiwyg },
		data() {
			return {
				editing : false,
				id : this.reply.id,
				body : this.reply.body,
                isBest : this.reply.isBest,
                errors : [],
                oldBody : ''
			}
		},
		computed : {
			ago(){
				return moment(this.reply.created_at).fromNow();
			}
		},
        created() {
		    window.events.$on('best-reply-selected', id => {
		        this.isBest = (id === this.id);
            });
        },
        methods : {
			update(){
			    this.oldBody = this.body;
				axios.patch('/replies/' + this.id,{
					body : this.reply.body
				}).catch(error => {
                    flash(error.response.data.message,'danger');
                });
				this.editing = false;
				flash('Updated Reply');

			},
			destroy(){
				axios.delete('/replies/' + this.id);
				this.$emit('deleted', this.id);
                flash('Reply was successful deleted', 'info');
			},
            markBestRepy(){
			    axios.post('/replies/' + this.id + '/best');
			    window.events.$emit('best-reply-selected', this.id)
            }
		},
    }
</script>

<style>
    .bg-blue {
      background-color : lightgreen
    }
</style>
