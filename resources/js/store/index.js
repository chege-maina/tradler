import { createStore } from "vuex";

const store = createStore({
    state: {
        //define variables
        menu: localStorage.getItem("menu"),
        video: localStorage.getItem("video"),
    },

    mutations: {
        // update variable value
        UPDATE_MENU(state, payload) {
            state.menu = payload;
        },
        UPDATE_VIDEO(state, payload) {
            state.video = payload;
        },
    },

    actions: {
        // action to be performed
        dashMenus(context, payload) {
            localStorage.setItem("menu", payload);
            context.commit("UPDATE_MENU", payload);
        },
        setVideo(context, payload) {
            localStorage.setItem("video", payload);
            context.commit("UPDATE_VIDEO", payload);
        },
    },

    getters: {
        // get state variable value
        menu(state) {
            return state.menu;
        },
        video(state) {
            return state.video;
        },
    },
});

export default store;
