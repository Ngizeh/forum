<template>
    <input type="file" accept="image/*" @change="onChange">
</template>

<script>
    export default {
        name: "UploadPhoto",
        methods : {
            onChange(e){
                if(!e.target.files.length) return ;
                let file
                    = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = e => {
                    let src =  e.target.result;
                    //ES6 in Action
                    this.$emit('loaded', { src,file})
                }
            },

        }
    }
</script>

<style scoped>

</style>
