<template>
    <div class="notifications" v-if="isActive">
        <div class="notifications-panel">
            <p style="font-weight: bold;" class="notifications-message">{{ title }}</p>
        </div>
        <div class="notifications-bottom">
            <p class="notifications-message">{{ message }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props:['user_id'],
    data(){
        return {
            title: '',
            message: '',
            isActive: false,
            typingTimer: false,
        }
    },
    computed: {
        channel(){
            return window.Echo.private('notifications.' + this.user_id);
        }
    },
    mounted() {
        this.channel
            .listen('NotificationEvent', (e) => {
                this.isActive = true;
                this.title = e.title;
                this.message = e.message;

                if(this.typingTimer) clearTimeout(this.typingTimer)

                this.typingTimer = setTimeout(() => {
                    this.isActive = false;
                }, 4000);
            });
    },
}
</script>
