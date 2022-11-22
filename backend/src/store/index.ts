import {createStore, Store} from "vuex";
import state from "./state";
import * as actions from "./actions";
import * as mutations from "./mutations";

const store: Store<any> = createStore({
    state,
    getters: {},
    actions,
    mutations,
});

export default store;
