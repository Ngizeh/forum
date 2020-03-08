<script>
import Replies from '../components/Replies.vue'
import SubscribeButton from '../components/SubscribeButton.vue'
export default {
   components : { Replies, SubscribeButton },
    props : ['initialReplyCount'],
      data(){
      	return{
      		repliesCount : this.initialReplyCount
      	}
      }
}
</script>
