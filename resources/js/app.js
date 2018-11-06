import Vue          from 'vue'
import VueRouter    from 'vue-router'
import axios        from 'axios'

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use({
    install(Vue) {
        Vue.prototype.$api = axios.create({
        })
        let token = localStorage.getItem('jwt')
        Vue.prototype.$api.defaults.headers.common['Content-Type'] = 'application/json'
        Vue.prototype.$api.defaults.headers.common['Authorization'] = 'Bearer ' + token

    }
});

import App          from './views/App'
import Login        from './views/Login'
import Register     from './views/Register'
import Dashboard    from './views/Dashboard'
import ItemCreate      from './views/Item/Create'

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
            path: '/register',
            name: 'register',
            component: Register,
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router
});