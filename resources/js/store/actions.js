import axios from '../axios';
import echo from '../echo';
import {USER_STATUS_OFFLINE, USER_STATUS_ONLINE} from "../constants";

export default {
    loadChat({commit}) {
        axios.get('/api/chat/init')
            .then(response => {
                commit('initChat', response.data);
            });

        echo.join('chat')
            .listen('ChatMessageSent', chatMessage => {
                commit('addChatMessage', chatMessage);
            })
            .listen('UserStatusUpdated', user => {
                if (user.status === USER_STATUS_ONLINE) {
                    commit('addUser', user);
                } else if(user.status === USER_STATUS_OFFLINE) {
                    commit('removeUser', user);
                }
            });
    },

    logout({commit}) {
        return new Promise(resolve => {
            echo.leave('chat');
            axios.post('/api/auth/logout')
                .then(() => {
                    commit('logout');
                    resolve();
                });
        })
    },

    init({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/user')
                .then(response => {
                    commit('setUser', response.data.data);
                    resolve();
                })
                .catch(error => {
                    switch (error.response.status) {
                        case 401:
                            commit('setUser', null);
                            resolve();
                            break;
                        default:
                            reject()
                    }
                });
        });
    }
}
