<template>
    <div class="container-fluid">
        <div class="row  flex-column chat-container">
            <div class="col position-relative">

                <div class="row gx-3 chat-frame flex-nowrap">
                    <div class="col overflow-auto" ref="scrollable" @click="$refs.message.focus()">
                        <chat-message
                            v-for="message in messages"
                            :message="message"
                            :key="`message${message.id}`"
                        />
                    </div>

                    <div class="col-3 col-xxl-2 list-group bg-light overflow-auto">

                        <div class="m-3">Сейчас онлайн: {{ users.length }}</div>

                        <div class="list-group mx-3">
                        <transition-group name="list" tag="div">
                            <chat-user
                                class="list-item list-group-item"
                                v-for="user in users"
                                :user="user"
                                :key="`user${user.id}`"
                            />
                        </transition-group>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-auto bg-light p-2">
                <form action="#" method="post" @submit.prevent="sendMessage" ref="form">
                    <div class="row align-items-center">
                        <div class="col">
                            <errors v-if="errors.message" class="mb-2" :errors="errors.message"/>
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{'is-invalid': errors.message}"
                                    name="message"
                                    id="message"
                                    ref="message"
                                    required
                                    placeholder="Введите сообщение..."
                                >
                                <label for="message">Введите сообщение...</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary" :disabled="isSending">
                                Отправить
                            </button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-link" @click="leave">
                                Выйти
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {computed, nextTick} from "vue";
import {useStore} from "vuex";
import axios from "../axios";
import ChatMessage from "../components/ChatMessage.vue";
import ChatUser from "../components/ChatUser.vue";
import Errors from "../components/Errors.vue";

export default {
    components: {
        ChatUser,
        ChatMessage,
        Errors,
    },

    data() {
        return {
            errors: {},
            isSending: false
        }
    },

    computed: {
        messagesCount() {
            return this.messages.length;
        }
    },

    setup() {
        const store = useStore();
        return {
            loadChat: () => store.dispatch('loadChat'),
            logout: () => store.dispatch('logout'),
            messages: computed(() => store.state.messages),
            users: computed(() => store.state.users),
        }
    },

    methods: {
        leave() {
            this.logout();
        },

        scrollToBottom() {
            nextTick(() => {
                this.$refs.scrollable.scroll({
                    top: this.$refs.scrollable.scrollHeight,
                    behavior: 'smooth'
                });
            });
        },

        sendMessage() {
            this.isSending = true;
            axios.post('/api/chat/message', new FormData(this.$refs.form))
                .then(() => {
                    this.errors = {};
                    this.$refs.message.value = '';
                })
                .catch(error => {
                    if (error.response) {
                        switch (error.response.status) {
                            case 422:
                                this.errors = error.response.data.errors;
                        }
                    }
                })
                .finally(() => this.isSending = false);
        }
    },

    mounted() {
        this.loadChat();
    },

    watch: {
        messagesCount() {
            this.scrollToBottom()
        }
    }
}
</script>

<style lang="scss" scoped>
.chat-container {
    height: 100vh !important;
    max-height: 100vh;
    overflow: hidden;
}

.chat-frame {
    position: absolute;
    top: 0;
    left: 1rem;
    right: 0;
    bottom: 0;
}

</style>
