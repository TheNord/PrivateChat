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
            <p class="card-text" v-for="chat in chats" :key="chat.message">
                {{ chat.message }}
            </p>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Write your message here"
                :disabled="session_block">
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props:['friend'],
        data() {
            return {
                chats:[],
                session_block: false
            }
        },
        methods: {
            send() {
                console.log('test');
            },
            close() {
                this.$emit('close');
            },
            clear() {
                this.chats = []
            },
            block() {
                this.session_block = !this.session_block
            }
        },
        created() {
            this.chats.push(
                {message: 'Heyy'},
                {message: 'test 2'},
                {message: 'test 3'},
                {message: 'test 4'},
                {message: 'test 5'},
                {message: 'test 6'},
                {message: 'test 7'},
                {message: 'test 8'},
                {message: 'test 9'},
                {message: 'test 10'},
                {message: 'test 11'},
                {message: 'test 12'},
                {message: 'test 13'},
                {message: 'test 14'},
                {message: 'test 15'}
                )
        }
    }
</script>

<style>
    .chat-box {
        height: 400px;

    }

    .card-body {
        overflow-x: scroll;
    }

</style>
