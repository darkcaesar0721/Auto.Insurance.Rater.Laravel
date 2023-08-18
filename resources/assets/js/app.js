
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('rate-calculator', require('./components/RateCalculator.vue'));
Vue.component('auto-competitors', require('./components/AutoCompetitors.vue'));
Vue.component('auto-uploader', require('./components/AutoUploader.vue'));

import swal from 'sweetalert';

const app = new Vue({
    el: '#app'
});
