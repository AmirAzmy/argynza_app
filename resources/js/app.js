window.Vue = require('vue');
require('./bootstrap');
require('axios');

import router from './router';
import swal from 'sweetalert';
import alertsMixin from "./mixins/alertsMixin";
import {PaginationPlugin, FormTagsPlugin} from 'bootstrap-vue'
import store from './store';
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
import Vuelidate from 'vuelidate';

import interceptorSetup from "./router/middleware/interceptor"

require('./store/subscriber');

//loader
Vue.use(VueLoaders);


Vue.mixin(alertsMixin);
Vue.use(PaginationPlugin);
Vue.use(FormTagsPlugin);
Vue.use(Vuelidate)

axios.defaults.baseURL = "/api";
// axios.defaults.baseURL = "https://haraj.el-abda3.online/api";
axios.defaults.headers.common = {
    'accept-language': 'ar',
    'app-version': '1',
    Accept: "application/json",
    'device-name': navigator.platform,
// 'device-os-version': '13',
    'device-udid': '1231321321321321321',
    'access-control-allow-origin': '*',
    'device-type': 'web',
    'device-push-token': 'Not Allowed',
    'Device-OS-Version': navigator.platform,
}

//interceptor();
interceptorSetup();

store.dispatch('auth/attempt', localStorage.getItem('token')).then(() => {
    const app = new Vue({
        el: '#app',
        store,
        router,
        swal
    });
});
