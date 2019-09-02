import Vue from 'vue'

Vue.component('dashbord', require('./components/dashboard').default);

//product
Vue.component('product-list', require('./components/product/list').default);

//category
Vue.component('category-list', require('./components/category/list').default);

//list
Vue.component('list', require('./components/list/list').default);

const app = new Vue({
    el: '#app',
});
