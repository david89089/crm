<template>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Имя</th>
                <th scope="col">Действия</th>
                <th scope="col">Статус</th>
            </tr>
        </thead>
        <tbody>
            <template v-for="item in locUsers.data">
                <tr>
                    <th scope="row">{{item.id}}</th>
                    <td>{{item.name}}</td>
                    <td>
                        <form v-bind:action="'/admin/users/show/' + item.id" method="GET">
                            <input type="hidden" name="id" v-model="item.id">
                            <button type="submit" class="btn btn-primary">Посмотреть анкету</button>
                        </form>
                    </td>
                    <td>
                        {{list_statuses[item.status]}}
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</template>

<script>

export default {
    props:['users', 'list_statuses'],
    data(){
        return {
            locUsers: this.users,
        }
    },
    computed: {
        channel(){
            return window.Echo.private('new-user');
        }
    },
    mounted() {
        this.channel
            .listen('NewUserEvent', (e) => {
                this.locUsers.data.unshift(e.data);
                if(this.locUsers.data.length > 10) {
                    this.locUsers.data.pop();
                }
            });
    }
}
</script>
