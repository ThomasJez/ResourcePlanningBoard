/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);

Vue.component('resourceComponent', require('./components/ResourceComponent').default);
Vue.component('rule-component', require('./components/RuleComponent').default);
Vue.component('respos-component', require('./components/ResPosComponent').default);
Vue.component('rulepos-component', require('./components/RulePosComponent').default);
Vue.component('startedit-component', require('./components/StartEditComponent').default);
Vue.component('ganttchart-component', require('./components/GanttchartComponent').default);
Vue.component('ganttmenu-component', require('./components/GanttmenuComponent').default);
Vue.component('planningboard-component', require('./components/PlanningBoardComponent').default);
Vue.component('servererror-component', require('./components/ServerErrorComponent').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
