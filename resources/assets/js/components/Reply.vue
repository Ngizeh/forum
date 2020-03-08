<template>
	<div :id="'data-'+id">
		<div class="card my-4">
			<div class="card-header">
				<div class="d-flex justify-content-between">
					<p>
                        <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"> </a> said
						<span v-text="ago"></span>...
					</p>
					<div v-if="signIn">
						<favorite :data="data"></favorite>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="from-group" v-if="editing">
                    <form @submit="update">
                        <textarea cols="30" rows="2" class="form-control mb-4" v-model="data.body" @keydown.enter="update" required></textarea>
                        <button class="btn btn-primary btn-sm">Update</button>
                        <button class="btn btn-link btn-sm" @click="editing = false" type="button">Cancel</button>
                    </form>
				</div>

				<div v-else="show" v-html="data.body"></div>

			</div>
            <div class="card-header d-flex">
			    <div v-if="canUpdate">
                    <button class="btn btn-secondary btn-sm mr-4" @click="editing = true">Edit</button>
                    <button class="btn btn-danger btn-sm mr-4" @click="destroy">Delete</button>
                </div>
                <button class="btn btn-outline-primary btn-sm mr-4 ml-auto" @click="markBestRepy">Best Reply?</button>
			</div>
		</div>
	</div>
</template>
<script>
	import Favorite from '../components/Favorite.vue';
	import moment from 'moment';
	export default {
		props : ['data'],
		components : { Favorite },
		data() {
			return {
				editing : false,
				id : this.data.id,
				body : this.data.body,
                errors : [],
                oldBody : ''
			}
		},
		computed : {
			signIn(){
				return window.App.singedIn;
			},
			canUpdate(){
				return this.authorize(user => this.data.user_id === user.id);
			},
			ago(){
				return moment(this.data.created_at).fromNow();
			}
		},
		methods : {
			update(){
			    this.oldBody = this.body;
				axios.patch('/replies/' + this.data.id,{
					body : this.data.body
				}).catch(error => {
                    flash(error.response.data.message,'danger');
                });
				this.editing = false;
				flash('Updated Reply');

			},
			destroy(){
				axios.delete('/replies/' + this.data.id);
				this.$emit('deleted', this.data.id);
                flash('Reply was successful deleted', 'info');
			},
            markBestRepy(){
			    axios.post('/replies/' + this.data.id/+ '/best')
            }
		},
    }
</script>
