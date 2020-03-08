<template>
    <div>
        <h2 class="panel-header">
            <small class="text-muted" v-text="user.name"></small>
        </h2>
        <div v-if="canUpdate">
            <img :src="avatar" alt="Profile Image" height="100" width="100" class="rounded mb-2">
            <form @submit="addPhoto" enctype="multipart/form-data">
               <upload-photo name="avatar" @loaded="onLoad"></upload-photo>
            </form>
        </div>
    </div>

</template>

<script>
    import UploadPhoto from "./UploadPhoto";
    export default {

        name: 'AvatarForm',
        components: {UploadPhoto},
        props : ['user'],
        data(){
            return {
                avatar : this.user.avatar_path
            }
        },
        computed : {
            canUpdate(){
                return this.authorize(user => user.id === this.user.id);
            },
        },
        methods : {
            addPhoto(){
                axios.post(`/api/users/${this.user.id}/avatar`)
            },
            onLoad(avatar){
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },

            persist(avatar){
                let data = new FormData;

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                     .then(() => flash('Avatar Upload'))
            }
        }
    }
</script>

<style scoped>

</style>
