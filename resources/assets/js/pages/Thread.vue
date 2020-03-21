<script>
import Replies from '../components/Replies.vue'
import SubscribeButton from '../components/SubscribeButton.vue'
export default {
   components : { Replies, SubscribeButton},
    props : ['thread'],
      data(){
      	return{
      		repliesCount : this.thread.reply_count,
            locked : this.thread.locked
      	}
      },
      methods : {
        toggleLock(){
            axios[this.locked ? 'delete' : 'post'](`/lock-thread/${this.thread.slug}`);

            this.locked = ! this.locked;
        }
      }
}
</script>
