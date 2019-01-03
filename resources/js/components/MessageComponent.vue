<template>
    <div class="card card-default chat-box">
        <div class="card-header">
            <b :class="{'text-danger':session.block}">
                {{ friend.name }}
                <span v-if="session.block">(Blocked)</span>
            </b>
            <div class="dropdown float-right mr-3">
                <a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="" class="dropdown-item" @click.prevent="close">
                        Close
                    </a>
                    <a class="dropdown-item" href="#" v-if="session.block && can" @click.prevent="unblock">
                        UnBlock
                    </a>
                    <a class="dropdown-item" href="#" v-if="!session.block" @click.prevent="block">
                        Block
                    </a>
                    <a class="dropdown-item" href="#" @click.prevent="clear">Clear Chat</a>
                </div>
            </div>
        </div>
        <div class="card-body" v-chat-scroll>
            <p class="card-text"
               v-for="chat in chats"
               :key="chat.id"
               :class="{ 'text-right' : chat.type === 0, 'not-read' : readStatus(chat) }">
                {{ chat.message }}
                <br>
                <span style="font-size:8px">{{ chat.send_at }}</span>
            </p>

        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Write your message here"
                       :disabled="session.block" v-model="message">
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ["friend"],
        data() {
            return {
                chats: [],
                message: '',
            };
        },
       computed: {
            // сокращение для краткого обращения к сессии
            session() {
               return this.friend.session;
           },
           can() {
                return this.session.blocked_by === authId;
           }
       },
        methods: {
            send() {
                if (this.message) {
                    this.pushToChat(this.message);
                    axios
                        .post(`/send/${this.friend.session.id}`, {
                            message: this.message,
                            to_user: this.friend.id
                        })
                        .then(res => {
                            // добавляем id из ответа к сообщению
                            this.chats[this.chats.length - 1].id = res.data;
                        });
                    this.message = '';
                }
            },
            pushToChat(message) {
                this.chats.push({
                    message: message,
                    type: 0,
                    read_at: null,
                    send_at: '1 second ago'
                });
            },
            close() {
                this.$emit('close');
            },
            clear() {
                axios
                    .post(`/chats/${this.friend.session.id}/clear`)
                    .then(res => (this.chats = []));
            },
            block() {
                this.session.block = true;
                axios
                    .post(`/chats/${this.friend.session.id}/block`)
                    .then(res => (this.session.blocked_by = authId));
            },
            unblock() {
                this.session.block = false;
                axios
                    .post(`/chats/${this.friend.session.id}/unblock`)
                    .then(res => (this.session.blocked_by = null));
            },
            getAllMessages() {
                axios
                    .post(`/chats/${this.friend.session.id}`)
                    .then(response => this.chats = response.data.data);
            },
            read() {
                axios.post(`/chats/${this.friend.session.id}/read`);
            },
            readStatus(chat) {
                if (chat.read_at === null && chat.type === 0) {
                    return true;
                }
            }
        },
        created() {
            this.getAllMessages();
            this.read();

            // слушаем приватный канал сессии на получение событий
            Echo.private(`Chat.${this.friend.session.id}`)
            // событие - получение нового сообщения
                .listen('PrivateChatEvent', (e) => {
                    // помечаем сообщение прочитанным
                    this.friend.session.open ? this.read() : '';
                    // добавляем сообщение в список сообщений
                    this.chats.push({
                        message: e.content,
                        type: 1,
                        send_at: '1 second ago'
                    });
                })
                // событие - прочтение сообщения
                .listen('MsgReadEvent', (e) => {
                    // проходим циклом по всем сообщениям текущей сессии
                    this.chats.forEach(chat => {
                        // находим нужное сообщение по ид
                        if (chat.id === e.chat.id) {
                            // помечаем прочитанным
                            chat.read_at = e.chat.read_at
                        }
                    })
                })
                // событие - блокировка пользователя
                .listen('BlockEvent', (e) => {
                    this.session.block = e.blocked;
                })
        }
    };
</script>

<style>
    .chat-box {
        height: 400px;

    }

    .card-body {
        overflow-x: scroll;
    }

    .not-read {
        background-color: #dedede;
    }
</style>
