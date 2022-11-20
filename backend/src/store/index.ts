import {createStore, Store} from "vuex";

const store: Store<any> = createStore({
    state: {
        user: {
            token: null,
            data: {}
        }
    },
    getters: {},
    actions: {},
    mutations: {},
});

export default store;
