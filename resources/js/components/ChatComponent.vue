<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-default">
                    <div class="card-header">Private chat</div>
                    <ul class="list-group">
                        <li class="list-group-item"
                            v-for="friend in friends"
                            :key="friend.id"
                            @click.prevent="openChat(friend)">
                            <a href="">
                                {{ friend.name }}
                                <span v-if="friend.session && (friend.session.unreadCount > 0)" class="text-danger">{{ friend.session.unreadCount }}</span>
                            </a>
                            <i class="fas fa-circle float-right text-success" v-if="friend.online"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <span v-for="friend in friends" :key="friend.id" v-if="friend.session">
                   <message-component
                           v-if="friend.session.open"
                           @close="close(friend)"
                           :friend="friend"
                   >
                </message-component>
                </span>

            </div>
        </div>
    </div>
</template>

<script>
    import MessageComponent from './MessageComponent';

    export default {
        data() {
            return {
                friends: []
            };
        },
        methods: {
            // закрытие диалога с переданным пользователем
            close(friend) {
                friend.session.open = false
            },
            // получаем список пользователей
            getFriends() {
                axios.post('/getFriends').then(response => {
                    this.friends = response.data.data;
                    // проходим циклом по всем пользователям и подписываем на событие
                    // получения нового сообщения и при получении увеличиваем количество непрочитанных
                    this.friends.forEach(friend => {
                        friend.session ? this.listenForEverySession(friend) : ''
                    });
                });
            },
            openChat(friend) {
                if (friend.session) {
                    // перед открытием новой сессии закрываем все ранее открытые
                    this.friends.forEach(
                        friend => (friend.session ? friend.session.open = false : '')
                    );
                    // открываем диалоговое окно
                    friend.session.open = true;
                    // обнуляем счетчик непрочитанных сообщений
                    friend.session.unreadCount = 0;
                } else {
                    // создаем новую сессию между пользователями
                    this.createSession(friend);
                }
            },
            createSession(friend) {
                // перед созданием новой сессии закрываем все ранее открытые
                this.friends.forEach(
                    friend => friend.session ? friend.session.open = false : ''
                );
                // создаем новую сессию
                axios.post('/session/create', {friend_id: friend.id}).then(response => {
                    friend.session = response.data.data;
                    friend.session.open = true;
                });
            },
            listenForEverySession(friend) {
                Echo.private(`Chat.${friend.session.id}`)
                    .listen(
                        'PrivateChatEvent',
                        e => (friend.session.open ? "" : friend.session.unreadCount++)
                    )
            }
        },
        created() {
            // получаем список пользователей
            this.getFriends();

            Echo.channel('Chat')
                .listen('SessionEvent', e => {
                    // находим среди всех пользователей, пользователя который создал сессию
                    let friend = this.friends.find(friend => friend.id === e.session_by);
                    // меняем информацию о созданной сессии
                    friend.session = e.session;
                    // подписываем сессию на счетчик непрочитанных сообщений
                    this.listeForEverySession(friend);
                });

            Echo.join('Chat')
            // получаем список онлайн пользователей
                .here((users) => {
                    this.friends.forEach(friend => {
                        users.forEach(user => {
                            if (user.id === friend.id) {
                                friend.online = true
                            }
                        })
                    })
                })

                // если пользователь вошел в чат отмечаем его в онлайне
                .joining((user) => {
                    // проходим циклом по списку пользователей
                    this.friends.forEach(friend => {
                        // если ид подключившегося к каналу пользователя равен ид пользователя в цикле
                        // помечаем его статус онлайн
                        user.id === friend.id ? friend.online = true : ''
                    })
                })

                .leaving((user) => {
                    this.friends.forEach(friend => {
                        user.id === friend.id ? friend.online = false : ''
                    })
                });
        },
        components: {
            MessageComponent
        }
    };
</script>
