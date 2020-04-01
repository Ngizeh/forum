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
        <tbody>
            <tr v-if="channel" v-for="(channel, index) in items" :key="index">
                <td>
                    <input v-if="channel.edit" class="form-control" type="text" v-model="channel.name">
                    <span v-else style="text-transform: capitalize;">{{channel.name}}</span>
                </td>
                <td>
                  <textarea v-if="channel.edit" class="form-control" type="text" v-model="channel.description" v-text="channel.description"></textarea>
                  <span v-else>{{channel.description.slice(0,50)+ '...' }}</span>
              </td>
              <td v-if="!edit">{{channel.ThreadsCount }}</td>
              <td>
                  <div v-if="!channel.edit">
                    <button class="btn btn-sm btn-primary" @click="editChannel(channel)">Edit</button>
                    <button class="btn btn-sm btn-danger" @click.prevent="removeChannel(channel.id, index)">Del</button>
                </div>
                <div v-else>
                  <button  class="btn btn-sm btn-success" @click.prevent="updateChannel(channel.id, index)">Update</button>
                  <button class="btn btn-sm btn-link" @click="cancelEdit(channel)" >Cancel</button>
              </div>
          </td>
      </tr>
      <tr v-else>
        <td>Nothing here.</td>
    </tr>
</tbody>
</table>
<paginator :dataSet="dataSet" @changed="fetch"></paginator>
</div>
</template>

<script>
    export default {
      props : ['channels'],
      name: 'ChannelView',
      data() {
        return {
          chans : this.channels,
          edit : false,
          endpoint : location.pathname,
          dataSet : false,
          items : []
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
        window.scrollTo(50, 50);
    },
    removeChannel(channelId, index) {
      let channel = this.chans.find(channel =>  channel.id === channelId);
      axios.delete(`/admin/channels/${channel.id}`);
      this.items.splice(index, 1);
      flash('You have delete a channel', 'danger')
     },
    editChannel(channel){
       this.$set(channel, 'edit', true);
       this.edit = true;
     },
    cancelEdit(channel){
       this.$set(channel, 'edit', false);
       this.edit = false;
    },
    updateChannel(channelId){
        let channel = this.items.find(channel => channel.id === channelId);
        axios.patch(`/admin/channels/${channel.id}`, {
            name : channel.name,
            description : channel.description
        }).catch(error => {
            flash(error.response.data.message,'danger');
        });
        this.$set(channel, 'edit', false);
        this.edit = false;
        flash('Updated Channel');
    },
},
}
</script>

<style lang="css" scoped>
</style>
