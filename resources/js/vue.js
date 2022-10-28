import {createApp, nextTick} from 'vue';
import {createRouter, createWebHistory} from 'vue-router';
import store from './store/store';

import Root from "./modules/Root.vue";

const Chat = () => import('./modules/Chat.vue');
const Home = () => import('./modules/Home.vue');
const PageNotFound = () => import('./modules/PageNotFound.vue');

const DEFAULT_TITLE = 'Test chat';
const router = createRouter({
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            meta: {
                title: 'Hello'
            }
        },
        {
            path: '/chat',
            name: 'chat',
            component: Chat,
            meta: {
                title: "Chat"
            }
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'pageNotFound',
            component: PageNotFound,
            meta: {
                title: "404 Page not found"
            }
        }
    ],
    history: createWebHistory()
});

const app = createApp(Root)
    .use(router)
    .use(store);

router.afterEach(async (to, from) => {
    await nextTick();

    if (to.meta && to.meta.title) {
        document.title = to.meta.title || DEFAULT_TITLE;
    }

    return true;
});

app.mount('#app');
app.config.devtools = true;

