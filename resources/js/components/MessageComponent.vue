<template>
    <div class="card card-default chat-box">
        <div class="card-header">
            <b :class="{'text-danger':session_block}">
                {{ friend.name }}
                <span v-if="session_block">(Blocked)</span>
            </b>
            <div class="dropdown float-right mr-3">
                <a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="" class="dropdown-item" @click.prevent="close">
                        Close
                    </a>
                    <a class="dropdown-item" href="#" @click.prevent="block">
                        <span v-if="session_block">UnBlock</span>
                        <span v-else>Block</span>

                    </a>
                    <a class="dropdown-item" href="#" @click.prevent="clear">Clear Chat</a>
                </div>
            </div>
        </div>
        <div class="card-body" v-chat-scroll>
            <p class="card-text" :class="{'text-right' : chat.type === 0}" v-for="chat in chats" :key="chat.message">
                {{ chat.message }}
            </p>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Write your message here"
                       :disabled="session_block" v-model="message">
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
                session_block: false
            };
        },
        methods: {
            send() {
                if (this.message) {
                    this.pushToChat(this.message);
                    axios.post(`/send/${this.friend.session.id}`, {
                        message: this.message,
                        to_user: this.friend.id
                    });
                    this.message = '';
                }
            },
            pushToChat(message) {
                this.chats.push({
                    message: message,
                    type: 0,
                    send_at: '1 секунду назад'
                });
            },
            close() {
                this.$emit('close');
            },
            clear() {
                this.chats = []
            },
            block() {
                this.session_block = !this.session_block
            },
            getAllMessages() {
                axios
                    .post(`/chats/${this.friend.session.id}`)
                    .then(response => this.chats = response.data.data);

            }
        },
        created() {
            this.getAllMessages();
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

</style>
