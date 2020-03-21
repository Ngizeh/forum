<template>
   <div class="mt-4">
		<div v-if="signedIn">
			<div class="form-group">
				<textarea name="body" id="body" cols="30" rows="5" class="form-control"
                          placeholder="Have something to say?"
                          v-model="body">
                </textarea>
			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit" @click="addReply">Post</button>
			</div>
		</div>
		<div v-else>
	   		<p class="text-center">
	   			Please  <a href="/login">sign in</a> to participate in the discussion
	   		</p>
		</div>
   </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';
export default {

  name: 'NewReply',

  data () {
    return {
			body : '',
			errors : [],
			errorsFound : false
		}
  },
  computed : {
  	singedIn() {
  		return window.App.singedIn
  	}
  },
    mounted() {
      $('#body').atwho({
          at : "@",
          delay : 750,
          callbacks : {
              remoteFilter : function (query, callback) {
                  $.getJSON("/api/users", {name: query}, function (usernames) {
                        callback(usernames)
                  })
              }
          }
      });
    },
  watch: {
  		body() {
  		    if(this.body.length > 0) return this.errorsFound = false ;
  		}
  },
  methods: {
  	addReply(){
  	    let app = this;
  		axios.post(location.pathname + '/replies', {body : this.body})
  		     .then(response => {
  		     	this.body = '';
  		     	this.errorsFound = false;
                 this.$emit('created', response.data);
                 flash('Your reply has been posted');
  		     })
  		     .catch(error => {
                 app.errorsFound = true;
                 flash(error.response.data.message,'danger');
                 app.body = '';
             })
    }
  }
}
</script>

<style lang="css" scoped>
</style>
