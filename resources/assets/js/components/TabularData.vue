<template>
    <tr v-if="data">
        <td>
            <input v-if="edit" class="form-control" type="text" size="5" v-model="data.name">
            <span v-else style="text-transform: capitalize;">
                <a :href="'/threads/' + data.name "> {{data.name}}</a>
            </span>
        </td>
        <td>
            <textarea v-if="edit" class="form-control" col="30" row="10" type="text" v-model="data.description" v-text="data.description"></textarea>
            <span v-else>{{data.description.slice(0, 80)+'...' }}</span>
        </td>
        <td>{{data.ThreadsCount }}</td>
        <td>
            <div v-if="!edit">
                <button class="btn btn-sm size btn-primary" @click="editChannel(data)">Edit</button>
                <button class="btn btn-sm size" :class="[archive ? 'btn-secondary' : 'btn-success']"
                @click.prevent="toggleChannel()" v-text="archive ? 'Archived' : 'Active' "></button>
            </div>
            <div v-else>
                <button  class="btn btn-sm size btn-success" @click.prevent="updateChannel(data.id)">Update</button>
                <button class="btn btn-sm size btn-link" @click="cancelEdit(data)" >Cancel</button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td>Nothing here.</td>
    </tr>
</span>
</template>

<script>
    export default {

      name: 'TabularData',
      props : ['channel'],
      data () {
        return {
            edit : false,
            path : `/admin/archive/${this.channel.id}`,
            data : this.channel,
            id : this.channel.id,
            name : this.channel.name,
            description : this.channel.description,
            archive : this.channel.archive,
            oldName : '',
        }
    },
    methods : {
        updateChannel(){
            this.oldName = this.name
            axios.patch(`/admin/channels/${this.id}`, {
                name : this.data.name,
                description : this.data.description,
            }).catch(error => {
                flash(error.response.data.message,'danger');
            });
            this.edit = false;
            flash('Updated Channel');
        },
        toggleChannel() {
                this.data.archive ?  this.archiveChannel() : this.activeChannel();
            },
        activeChannel(){
            axios.patch(this.path, {data : this.data })
            this.archive = true;
            flash('Activated Channel', 'primary');
        },
        archiveChannel(){
            axios.delete(this.path, {data : this.data });
            this.archive = false;
            flash('Archived Channel', 'secondary');
        },
        editChannel(channel){
            this.edit = true;
        },
        cancelEdit(channel){
            this.edit = false;
        },
    }
}

</script>

<style lang="css" scoped>
 .size {
    font-size: 0.7rem;
 }
</style>
