<template>
    <init-splash v-if="loading"/>
    <router-view v-else/>
    <div class="toast-container"></div>
</template>

<script>
import {useStore} from 'vuex';
import InitSplash from '../components/InitSplash.vue'

export default {
    components: {
        InitSplash
    },

    data() {
        return {
            loading: true
        }
    },

    setup() {
        const store = useStore();
        return {
            init: () => store.dispatch('init')
        }
    },

    mounted() {
        this.init()
            .then(() => {
                this.loading = false
            });
    }
}
</script>
