<template>
    <tr v-if="data">
        <td>
            <input v-if="edit" class="form-control" type="text" size="5" v-model="name">
            <span v-else style="text-transform: capitalize;">
                <a :href="'/threads/' + data.name "> {{name}}</a>
            </span>
        </td>
        <td>
            <textarea v-if="edit" class="form-control" col="30" row="10" type="text" v-model="description" v-text="description"></textarea>
            <span v-else>{{description.slice(0, 80)+'...' }}</span>
        </td>
        <td>{{data.ThreadsCount }}</td>
        <td>
            <div v-if="!edit">
                <button class="btn btn-sm size btn-primary" @click="editChannel">Edit</button>
                <button class="btn btn-sm size" :class="[archive ? 'btn-secondary' : 'btn-success']"
                @click.prevent="toggleChannel" v-text="archive ? 'Archived' : 'Active' "></button>
            </div>
            <div v-else>
                <button  class="btn btn-sm size btn-success" @click.prevent="updateChannel">Update</button>
                <button class="btn btn-sm size btn-link" @click="cancelEdit" >Cancel</button>
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
            data : this.channel,
            name : this.channel.name,
            id : this.channel.id,
            slug : this.channel.slug,
            archive : this.channel.archive,
            description : this.channel.description,
            oldData : '',
        }
    },
    computed : {
         path() {
          return `/admin-archive/${this.slug}`;
        }
    },
    methods : {
        updateChannel(){
            axios.patch('/admin/channels/' + this.slug,{
                name : this.name,
                description : this.description,
            }).catch(error => {
                flash(error.response.data.message,'danger');
            });
            this.edit = false;
            flash('Updated Channel');
        },
        toggleChannel() {
             this.archive ? this.archiveChannel(): this.activeChannel()  ;
        },
        activeChannel(){
            axios.post(this.path)
            this.archive = true
            flash('Activated Channel', 'primary');
        },
        archiveChannel(){
            axios.delete(this.path)
            this.archive = false
            flash('Archived Channel', 'secondary');
        },
        editChannel(){
            this.edit = true;
        },
        cancelEdit(){
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
