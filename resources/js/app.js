import Vue          from 'vue'
import VueRouter    from 'vue-router'
import axios        from 'axios'
import BootstrapVue from 'bootstrap-vue'
import VueNoty      from 'vuejs-noty'
import swal         from 'sweetalert'
import VueOffline from 'vue-offline'
import moment from 'moment'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vuejs-noty/dist/vuejs-noty.css'

Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(VueNoty, {
    timeout: 3000,
    progressBar: true,
    layout: 'bottomRight'
});
Vue.use({
    install(Vue) {
        Vue.prototype.$api = axios.create()
        let token = localStorage.getItem('jwt')
        Vue.prototype.$api.defaults.headers.common['Content-Type'] = 'application/json'
        Vue.prototype.$api.defaults.headers.common['Authorization'] = 'Bearer ' + token

    }
});
Vue.use(VueOffline);

/* todo: move filters to separate area*/
Vue.filter('truncate', function (text, stop, clamp) {
    return text.slice(0, stop) + (stop < text.length ? clamp || '...' : '')
});

Vue.filter('date', function (date) {
    return moment(date).calendar()
});

import App          from './views/App'
import Login        from './views/Login'
import Register     from './views/Register'
import Dashboard    from './views/Dashboard'
import ItemCreate   from './views/Item/Create'
import ItemUpdate   from './views/Item/Update'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/item/create',
            name: 'itemCreate',
            component: ItemCreate,
        },
        {
            path: '/item/update/:id',
            name: 'itemUpdate',
            component: ItemUpdate,
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
        },
    ],
});
router.beforeEach((to, from, next) => {
    if (localStorage.getItem('jwt') === null || localStorage.getItem('jwt') === "") {
        if (to.name !== "login") {
            next('/login');
        }
    }
    next();
});
const app = new Vue({
    el: '#app',
    components: { App },
    router
});

