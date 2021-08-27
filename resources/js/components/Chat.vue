<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Живой чатик | Создатель {{localChat.owner.name}}</div>

                    <div class="card-body">
                        <div ref="listChat" id="block" class="chat">
                            <div v-for="item in localChat.messages">
                                <template v-if="item.user_id == user.id">
                                    <div class="chat-message-my border border-secondary alert alert-light col-md-6 " role="alert">
                                        <div style="width: 2rem; height: 2rem;margin-top: 0.5rem">
                                            <template v-if="item.user.photo != null">
                                                <img :src="item.user.photo" width="40" height="auto" style="border-radius:50%">
                                            </template>
                                            <template v-else>
                                                <img src="https://pmdoc.ru/wp-content/uploads/default-avatar.png" width="40" height="auto" style="border-radius:50%">
                                            </template>
                                        </div>
                                        <div style="margin-left: 1rem">
                                            <div style="display: flex;justify-content: space-between;">
                                                <p style="margin: 0;">{{item.user.name}} {{item.created_at | moment}}</p>
                                                <p style="position:absolute;right:2%;color: #227dc7">✓</p>
                                                <div v-if="item.read">
                                                    <p style="position:absolute;right:4%;color: #227dc7">✓</p>
                                                </div>
                                            </div>
                                            <hr style="margin: 4px 0;">
                                            <p style="width: 17rem;word-wrap: break-word;" class="mb-0">{{item.message}}</p>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="chat-message-to border border-secondary alert alert-light col-md-6 " role="alert">
                                        <div style="width: 2rem; height: 2rem;margin-top: 0.5rem">
                                            <template v-if="item.user.photo != null">
                                                <img :src="item.user.photo" width="40" height="auto" style="border-radius:50%">
                                            </template>
                                            <template v-else>
                                                <img src="https://pmdoc.ru/wp-content/uploads/default-avatar.png" width="40" height="auto" style="border-radius:50%">
                                            </template>
                                        </div>
                                        <div style="margin-left: 1rem">
                                            <div style="display: flex;justify-content: space-between;">
                                                <p style="margin: 0;">{{item.user.name}} {{item.created_at | moment}}</p>
                                            </div>
                                            <hr style="margin: 4px 0;">
                                            <p style="width: 80%;word-wrap: break-word;" class="mb-0">{{item.message}}</p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="row g-3 align-items-center">
                            <div class="col-9">
                                <input v-model="textMessage" @keydown="actionUser" @keyup.enter="sendMessage" class="form-control" type="text" placeholder="Ваше величество напишите сообщение" required>
                            </div>
                            <div class="col-auto">
                                <button @click="sendMessage" type="submit" class="btn btn-primary">Отправить</button>
                            </div>
                            <span class="ml-3"v-if="isActive">{{isActive.name}} набирает сообщение...</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">В чате находится</div>
                <div class="card-body">
                    <ul style="padding-left: 15%">
                        <li v-for="user in activeUsers">{{user}}</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import moment from 'moment';

    export default {
        props:['chat', 'user'],
        data(){
            return {
                textMessage: '',
                activeUsers: [],
                isActive: false,
                typingTimer: false,
                localChat: this.chat,
                localUser: this.user,
            }
        },
        computed: {
            channel(){
                return window.Echo.join('chat.' + this.localChat.id);
            },
        },
        mounted() {
            this.channel
                .here((users) => {
                    this.activeUsers = users
                })
                .joining((user) => {
                    this.activeUsers.push(user)
                    this.$nextTick(() => {
                        if (this.activeUsers.length > 1) {
                            this.localChat.messages.forEach(function (item) {
                                if (!item.read) item.read = true;
                            })
                        }
                    })
                })
                .leaving((user) => {
                    this.activeUsers.splice(this.activeUsers.indexOf(user), 1)
                })
                .listenForWhisper('typing', (e) => {
                    this.isActive = e;

                    if(this.typingTimer) clearTimeout(this.typingTimer)

                    this.typingTimer = setTimeout(() => {
                       this.isActive = false;
                    }, 2000);
                })
                .listen('PrivateChatEvent', (e) => {
                    this.localChat.messages.push(e.data);
                    this.isActive = false;
                    this.$nextTick(() => {
                        this.scrollToEnd();
                    });
                });

            this.$nextTick(() => {
                this.scrollToEnd();
            });
        },
        methods: {
            async sendMessage() {
                axios.post('/chat/message', {chat_id: this.chat.id, message: this.textMessage}).then(response => {
                        this.chat.messages.push(response.data)
                        if(this.activeUsers.length > 1) {
                            this.chat.messages[this.chat.messages.length - 1].read = true;
                        }
                        this.$nextTick(() => {
                            this.scrollToEnd();
                        });
                    }
                )
                this.textMessage = '';
            },
            scrollToEnd() {
                this.$nextTick(() => {
                    let top = this.$refs.listChat.scrollHeight;
                    this.$refs.listChat.scrollTo(0, top);
                })
            },
            actionUser() {
                this.channel.whisper('typing', {
                    name: this.user.name
                })
            }
        },
        filters: {
            moment: function (date) {
                return moment(date).format('D MMMM YYYY, h:mm');
            }
        }
    }
</script>
