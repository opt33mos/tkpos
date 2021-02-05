require('./bootstrap');

import VueRouter from "vue-router";

window.Vue = require('vue').default;

import { Form, HasError, AlertError } from "vform";
import SweetAlert2 from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/src/sweetalert2.scss';


window.Swal = SweetAlert2;

const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.Toast = toast;

window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

Vue.use(VueRouter);

let routes = [
    {
        path: "/dashboard",
        component: require("./components/Dashboard.vue").default
    },
    {
        path: "/example",
        component: require("./components/Example.vue").default
    },
    {
        path: "/users",
        component: require("./components/Users.vue").default
    }
];

Vue.filter('capitalize', function (value) {
    if (!value) return '';
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1);
})
Vue.filter('DateFormat', function (value) {
    return moment(value).format('MMMM DD, YYYY');
})
const router = new VueRouter({
    mode: 'history',
    routes, // short for `routes: routes`
});

const app = new Vue({
    el: '#app',
    router
});
