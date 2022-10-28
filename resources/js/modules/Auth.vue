<template>
    <div class="card">
        <div class="card-header">{{ isAuthentication ? 'Авторизация' : 'Регистрация' }}</div>
        <div class="card-body">
            <form action="#" method="post" @submit.prevent="submit" ref="form">
                <div class="form-floating">
                    <input
                        type="text"
                        class="form-control"
                        :class="{'is-invalid': errors.name}"
                        placeholder="Имя пользователя"
                        name="name"
                        id="name"
                    >
                    <label for="name">Имя пользователя</label>
                </div>
                <errors v-if="errors.name" :errors="errors.name"/>

                <div class="form-floating mt-3">
                    <input
                        type="password"
                        class="form-control"
                        :class="{'is-invalid': errors.password}"
                        placeholder="Имя пользователя"
                        name="password"
                        id="password"
                    >
                    <label for="password">Пароль</label>
                </div>
                <errors v-if="errors.password" :errors="errors.password"/>

                <template v-if="!isAuthentication">
                    <div class="form-floating mt-3">
                        <input
                            type="password"
                            class="form-control"
                            :class="{'is-invalid': errors.password_confirmation}"
                            placeholder="Подтверждение"
                            name="password_confirmation"
                            id="password_confirmation"
                        >
                        <label for="password_confirmation">Подтверждение</label>
                    </div>
                    <errors v-if="errors.password_confirmation" :errors="errors.password_confirmation"/>
                </template>

                <button type="submit" class="btn btn-primary mt-3 w-100">
                    <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                    <span v-else>{{ isAuthentication ? 'Войти' : 'Зарегистрироваться и войти' }}</span>
                </button>

                <button type="button" class="btn btn-link mt-2 w-100" @click="isAuthentication = !isAuthentication">
                    {{ isAuthentication ? 'Регистрация' : 'Войти с паролем' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from "../axios";
import {defineAsyncComponent} from "vue";
import {useStore} from 'vuex';

export default {
    components: {
        Errors: defineAsyncComponent(() => import('../components/Errors.vue'))
    },

    data() {
        return {
            errors: {},
            isAuthentication: true,
            loading: false,
        }
    },

    setup() {
        const store = useStore();
        return {
            setToken: token => store.commit('setToken', token),
            setUser: user => store.commit('setUser', user),
        }
    },

    methods: {
        submit() {
            this.loading = true;
            axios.post(`/api/auth/${this.isAuthentication ? 'login' : 'register'}`, new FormData(this.$refs.form))
                .then(response => {
                    this.setToken(response.data.token);
                    this.setUser(response.data.data);
                })
                .catch(error => {
                    if (error.response) {
                        switch (error.response.status) {
                            case 422:
                                this.errors = error.response.data.errors;
                        }
                    }
                })
                .finally(() => this.loading = false);
        }
    },

    watch: {
        isAuthentication() {
            this.errors = {};
        }
    }
}
</script>
