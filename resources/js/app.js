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

//interceptor();
interceptorSetup();
//loader
Vue.use(VueLoaders);

store.dispatch('auth/attempt', localStorage.getItem('token'));
store.dispatch('auth/addName', localStorage.getItem('name'));

Vue.mixin(alertsMixin);
Vue.use(PaginationPlugin);
Vue.use(FormTagsPlugin);
Vue.use(Vuelidate)

axios.defaults.baseURL = "/api";

axios.defaults.headers.common['accept-language'] = 'ar';
axios.defaults.headers.common['app-version'] = '1';
axios.defaults.headers.common['device-name'] = navigator.platform;
// axios.defaults.headers.common['device-os-version'] = '13';
axios.defaults.headers.common['device-udid'] = '1231321321321321321';
axios.defaults.headers.common['device-type'] = 'web';
axios.defaults.headers.common['device-push-token'] = 'Not Allowed';
axios.defaults.headers.common['Device-OS-Version'] = navigator.platform;


const app = new Vue({
    el: '#app',
    store,
    router,
    swal
});
