/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from "vue-router";
import Swal from 'sweetalert2';

Vue.use(VueRouter);
import routes from './routes';

const router = new VueRouter({
    mode: 'history',
    routes
})

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 10000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
window.Swal = Swal;
window.Toast = Toast;

const app = new Vue({
    el: '#app',
    router
});
