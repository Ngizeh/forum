<script>
import Replies from '../components/Replies.vue'
import SubscribeButton from '../components/SubscribeButton.vue'
export default {
   components : { Replies, SubscribeButton},
    props : ['thread'],
      data(){
      	return{
      		repliesCount : this.thread.reply_count,
            locked : this.thread.locked,
            title : this.thread.title,
            body : this.thread.body,
            form : {},
            editing: false,
      	}
      },
      created() {
        this.resetForm();
      },
      methods : {
        toggleLock(){
            axios[this.locked ? 'delete' : 'post'](`/lock-thread/${this.thread.slug}`);
            this.locked = ! this.locked;
        },
        resetForm(){
            this.editing = false;
            this.form.title = this.thread.title;
            this.form.body = this.thread.body;
        },
        updateForm() {
          axios.patch(`/threads/${this.thread.channel.slug}/${this.thread.slug}`, this.form)
                .catch(() => {
                   flash(error.response.data.message,'danger');
                })
                .then(() => {
                    flash('Your thread was updated successfully');
                    this.title = this.form.title;
                    this.body = this.form.body;
                    this.editing = false;
                });
      },
  }
}
</script>
