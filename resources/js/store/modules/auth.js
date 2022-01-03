import interceptor, {logoutFun} from "./../../router/middleware/interceptor"
export default {
    namespaced: true,
    state: {
        token: null,
        user: null
    },
    getters: {
        authenticated(state) {
            return state.token != null
        },
        user(state) {
            return state.user
        }
    },
    mutations: {
        SET_TOKEN(state, token) {
            state.token = token
        },
        SET_USER(state, user) {
            state.user = user
        }
    },
    actions: {
        async login({dispatch}, credentials) {
            // credentials['type'] = 1;
            let response = await axios.post('/admin/login', credentials);
            // dispatch('addName', response.data.data.name);
            return dispatch('attempt', response.data.data.token);
        },
        async attempt({commit, state}, token) {
            if (token) {
                commit('SET_TOKEN', token);
            }
            if (!state.token) {
                return
            }
            try {
                let res = await axios.get('/users/logged');
                commit('SET_USER', res.data.data)
            }catch {
                commit('SET_USER', null);
                commit('SET_TOKEN', null);
                console.log('unauth')
                interceptor();
            }

        },
        async addName({commit, state}, name) {
            if (name) {
                commit('SET_USER', name);
            }
            if (!state.name) {
                return
            }
        },
        logout({commit}) {
            return axios.post('/auth/logout')
                .then(() => {
                    commit('SET_TOKEN', null);
                    commit('SET_USER', null);
                });

        }
    }
}
