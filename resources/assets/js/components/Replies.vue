<template>
	<div>
		<div v-for="(reply , index) in items" :key="reply.id">
			<reply :reply="reply" @deleted="remove(index)"></reply>
		</div>
		<paginator :dataSet="dataSet" @changed="fetch"></paginator>
		<new-reply @created="add" v-if="!$parent.locked"></new-reply>
		<p v-else class="text-center">
			This thread has been locked. No more replies are allowed
		</p>
	</div>
</template>
<script>
	import Reply from './Reply.vue';
	import NewReply from './NewReply.vue';
	import collection from '../mixins/Collection.js'

	export default {
		components : { Reply, NewReply },
		// CRUD Operation functions are on the collection mixins
		mixins : [collection ],
		data() {
			return {
				endpoint : location.pathname + '/replies',
				dataSet : false
			}
		},
		created() {
			this.fetch();
		},
		methods : {
			fetch(page) {
				axios.get(this.url(page)).then(this.refresh);
			},
			url(page){
				if(!page) {
					let query = location.search.match(/page=(\d+)/);
					page = query ? query[1] : 1;
				}
				return `${this.endpoint}?page=${page}`;
			},
			refresh({data}) {
				//dataset is the response data from Ajax
				this.dataSet  = data;
				this.items = data.data;
				window.scrollTo(0, 0);
			},
		},
	}
</script>
