<template>
     <div>
         <li class="dropdown mr-4" v-if="notifications.length">
             <a class="dropdown-toggle" href="#" role="button"
                data-toggle="dropdown" v-pre>
                 Notification
             </a>
             <ul class="dropdown-menu dropdown-menu-lg-right length">
               <li v-for="notification in notifications">
                   <a :href="notification.data.link" class="dropdown-item"
                      v-text="notification.data.message" @click="markAsRead(notification)"></a>
               </li>
             </ul>
         </li>
     </div>
</template>

<script>
    export default {
        name: "UserNotification",
       data(){
            return {
                notifications : []
            }
       },
    created() {
        axios.get("/profile/" + window.App.user.name + "/notifications")
            .then(response =>  this.notifications = response.data)
        },
    methods : {
          markAsRead(notification){
              axios.delete(`/profile/${window.App.user.name}/notifications/${notification.id}`)
              this.notifications.splice(notification, 1)
          }
        }
    }

</script>

<style scoped>
 .length {
     width : 400px;
     overflow-x: hidden;
 }
</style>
