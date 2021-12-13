import axios from 'axios';
import store from "../../store";
import router from "../index";

function logoutFun(cond) {
    if (cond) {
        console.log('arrival');
        store.dispatch('logout');
        router.push('/admin/login')
    }
}

export default function setup() {
    logoutFun(localStorage.getItem('token') == null);

    axios.interceptors.request.use(
        (config) => {
            store.commit('loading', true);
            return config;
        },
        (error) => {
            store.commit('loading', false);
            return Promise.reject(error);
        });


    axios.interceptors.response.use(
        function (response) {
            store.commit('loading', false);
            logoutFun(localStorage.getItem('token') == null);
            return response
        },
        function (error) {
            store.commit('loading', false);
            logoutFun(error.response.status == 401)
            return Promise.reject(error)
        });

}
