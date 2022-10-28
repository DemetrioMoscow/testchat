import axios from "../axios";

export default {
    addChatMessage(state, chatMessage) {
        state.messages.push(chatMessage);
    },

    addUser(state, user) {
        state.users.push(user);
    },

    initChat(state, payload) {
        state.messages = payload.messages.reverse();
        state.users = payload.users;
    },

    logout(state) {
        state.user = null;
        window.localStorage.removeItem('token');
        delete axios.defaults.headers.common['Authorization'];
    },

    removeUser(state, user) {
        let index = state.users.findIndex(u => u.id === user.id);
        if (index !== -1) {
            state.users.splice(index, 1);
        }
    },

    setToken(state, token) {
        state.token = token;
        window.localStorage.setItem('token', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    },

    setUser(state, user) {
        state.user = user;
    }
}
