<template>
    <div>
      <input id="trix" type="hidden" :name="name" :value="value">
      <trix-editor ref="trix" input="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>

<script>
import Trix from 'trix';

export default {
  props : ['name', 'value', 'placeholder', 'shouldClear'],
  name: 'Wysiwyg',
  mounted(){
    this.$refs.trix.addEventListener('trix-change', e => {
      this.$emit('input', e.target.innerHTML)
    })
  },
  watch : {
    shouldClear() {
      this.$refs.trix.value = '';
    }
  }
}
</script>

<style lang="css" scoped>
</style>
