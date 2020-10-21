<template>
	<div>
		<button class="btn" :class="classes" @click="subscribe"
		v-text="subscribeText"
		:title="moreInfo"
		v-if="signedIn"
		></button>
	</div>
</template>

<script>
	export default {
		name: 'SubscribeButton',
		props : ['active'],
		data(){
			return {
				showActive : this.active
			}
		},
		computed : {
			classes() {
				return this.showActive ? 'btn-primary' : 'btn-secondary';
			},
			subscribeText(){
				return this.showActive ? 'subscribed' : 'subscribe';
			},
			moreInfo(){
				return this.showActive ? 'You can click to unsubscribe from this thread' :
				'subscribe for notification';
			},
			signedIn() {
				return window.App.signedIn;
			}
		},
		methods : {
			subscribe() {
				let requestType =  this.showActive ? 'delete' : 'post';
				axios[requestType](`${location.pathname}/subscriptions`);
				this.showActive = !this.showActive;
			}
		}
	}
</script>

<style lang="css" scoped>
</style>
