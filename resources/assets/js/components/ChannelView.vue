<template>
	<div>
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th v-if="!edit">Threads</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody v-for="(channel, index) in items" :key="index">
				<tabular-data :channel="channel"></tabular-data>
			</tbody>
		</table>
		<paginator :dataSet="dataSet" @changed="fetch"></paginator>
	</div>
</template>

<script>
	import TabularData from './TabularData.vue';

	export default {
		props : ['channels'],
		name: 'ChannelView',
		components : { TabularData },
		data() {
			return {
				chans : this.channels,
				endpoint : location.pathname,
				edit : false,
				dataSet : false,
				items : [],
			}
		},
		created() {
			this.fetch();
		},
		methods : {
			fetch(page) {
				axios.get(this.url(page)).then(this.refresh);
				window.scrollTo(50, 50);
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
			},
			toggleChannel(channelId, index) {
				let channel = this.chans.find(channel =>  channel.id === channelId);
				channel.archive ?  this.archiveChannel(channel.id) : this.activeChannel(channel.id);
			},
	}
}
</script>

<style lang="css" scoped>
</style>
