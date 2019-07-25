
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
//Begin Add Element-Ui
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);
//End Add Element-Ui
//Begin  VueRouter config
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import router from './router';
//End  VueRouter config

//Begin Vue Translate
Vue.prototype.trans = string => _.get(window.i18n, string);
window.trans = (string) => _.get(window.i18n, string);
import VueI18n from 'vue-i18n'
Vue.use(VueI18n)
import locale from 'element-ui/lib/locale'
import faLocale from 'element-ui/lib/locale/lang/fa'
locale.use(faLocale)
//End Vue Translate

//begin config event listener
window.Fire = new Vue();
//end config event listener

//begin  moment & moment jallali config
//import moment from 'moment';
import VuePersianDatetimePicker from 'vue-persian-datetime-picker';
Vue.component('date-picker', VuePersianDatetimePicker);
Vue.use(require('vue-moment-jalaali'));
//end  moment & moment jallali config
import InfiniteLoading from 'vue-infinite-loading';

Vue.use(InfiniteLoading, { /* options */ });
Vue.component('user-box',require('./components/user_profile/UserBox.vue').default);
Vue.component('notification-box',require('./components/notification/NotificationBar.vue').default);
const app = new Vue({
    el: '#app',
    router
});
