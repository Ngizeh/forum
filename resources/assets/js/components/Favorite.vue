<template>
	<button class="btn" :class="showActive" @click="toggle">
		<span :title="title"><i class="fas fa-heart"><small class="pl-1" v-text="count"></small></i></span>
	</button>
</template>

<script>
	export default {

		name: 'Favorite',

		props : ['reply'],

		data () {
			return {
				count : this.reply.favoritedCount,
				active : this.reply.isFavorited,
			}
		},
		computed : {
			showActive() {
				return this.active ? 'btn-danger' : 'btn-link';
			},
			endpoint(){
				return '/replies/'+ this.reply.id +'/favorites';
			},
			title(){
				return this.active ? 'You can un favorite' : 'You can favorite a reply'
			}
		},
		methods : {
			toggle(){
				this.active ? this.destroy() : this.create() ;
			},
			create(){
				axios.post(this.endpoint);
				this.active = true;
				this.count++;
				flash('You have favourite a reply')
			},
			destroy(){
				axios.delete(this.endpoint);
				this.active = false;
				this.count--;
				flash('You have unfavourited a reply', 'info')
			}
		}
	}
</script>
