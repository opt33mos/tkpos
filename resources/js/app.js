require('./bootstrap');
import VueRouter from "vue-router";
window.Vue = require('vue').default;

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

const router = new VueRouter({
    mode: 'history',
    routes, // short for `routes: routes`
});

const app = new Vue({
    el: '#app',
    router
});
