require('bootstrap');

window.Vue = require('vue');
window.$ = require('jquery')
// Lo declaramos globalmente

//import Vuex from 'vuex'
import VueRouter from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';

import Login from './components/admin/Login.vue';
import Category from './components/admin/Category.vue';
import Product from './components/admin/Product.vue';
import Detail from './components/admin/Detail.vue';

import Shop from './components/client/Shop.vue';


//Vue.use(Vuex);
Vue.use(VueSweetalert2);
Vue.use(VueRouter);
//Vue.use(axios);

Vue.component('pagination', require('./components/util/Pagination.vue').default);

//const Login = () => import('components/admin/Login.vue')

function guard(to, from, next){
    let userCurrent = localStorage.getItem("currentUser");
    if(userCurrent) {
        next();
    } else{
        next('/admin');
    }
}

const router = new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        {
            path: '/admin', 
            component: Login,
            meta: {
                auth: false
            }
        },
        {
            path: '/shop', 
            component: Shop,
            meta: {
                auth: false
            }
        },
        {
            path: '/category', 
            beforeEnter: guard, 
            component: Category,
            meta: {
                auth: true
            }
        },
        {
            path: '/product', 
            beforeEnter: guard, 
            component: Product,
            meta: {
                auth: true
            }
        },
        {
            path: '/detalle', 
            beforeEnter: guard, 
            component: Detail,
            meta: {
                auth: true
            }
        }
       
    ]
});


Vue.filter('toCurrency', function(value) {
    let val = (value / 1).toFixed(2).replace('.', ',');
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
});

const app = new Vue({
    el: '#app',
    router
});