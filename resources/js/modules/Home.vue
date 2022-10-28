<template>
    <div class="container-sm mt-4 auth-container">

        <div v-if="user" class="card">
            <div class="card-header">Привет, {{ user.name }}</div>
            <div class="card-body">
                <router-link class="btn btn-primary w-100" :to="{name: 'chat'}">Войти в чат</router-link>
                <button type="button" class="btn btn-link w-100 mt-2" @click="$store.dispatch('logout')">Выйти</button>
            </div>
        </div>
        <auth v-else/>

    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from 'vuex';
import Auth from './Auth.vue';

export default {
    components: {
        Auth,
    },

    setup() {
        const store = useStore();
        return {
            user: computed(() => store.state.user)
        }
    }
}
</script>

<style lang="scss" scoped>
.auth-container {
    max-width: 400px;
}
</style>
