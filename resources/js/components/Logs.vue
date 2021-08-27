<template>
    <div style="margin-top: 1rem">
        <div v-for="item in locLogs.data">
            <div v-bind:class="typeAlert(item.type)" style="display: flex" role="alert">
                <div v-if="item.user">
                    <template v-if="item.user.photo != null">
                        <img :src="item.user.photo" width="50" height="auto" style="border-radius:50%">
                    </template>
                    <template v-else>
                        <img src="https://pmdoc.ru/wp-content/uploads/default-avatar.png" width="50" height="auto" style="border-radius:50%">
                    </template>
                </div>
                <div>
                    <p style="margin: 3px 0 0; padding-left: 1rem">{{item.log}}</p>
                    <p style="font-size:0.7rem; padding-left: 1rem">{{item.created_at | moment}}</p>
                </div>
                <div style="position: absolute; right: 0.5rem; top: 0.5rem;">
                    <p>Manager: {{item.owner.name}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props:['logs'],
    data(){
        return {
            locLogs: this.logs,
        }
    },
    computed: {
        channel(){
            return window.Echo.private('logs');
        }
    },
    mounted() {
        this.channel
            .listen('LogsEvent', (e) => {
                console.log(e.logs);
                this.locLogs.data.unshift(e.logs);
            });
    },
    methods: {
        typeAlert(type) {
            switch (type)
            {
                case 1:{
                    return 'alert alert-success'
                }
                case 2:{
                    return 'alert alert-danger'
                }
                case 3:{
                    return 'alert alert-warning'
                }
                default: {
                    return 'alert alert-primary'
                }
            }
        },
    },
    filters: {
        moment: function (date) {
            return moment(date).format('D MMMM YYYY, h:mm');
        }
    }
}
</script>
