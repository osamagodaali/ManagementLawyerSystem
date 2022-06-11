require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// import ActivityNotifications from './Components/ActivityNotifications.vue'
// Vue.component('activity-notifications', ActivityNotifications);  

// Vue.component('example-component', require('./components/ActivityNotifications.vue').default);


// // Require Vue
// window.Vue = require('vue').default;

// // Register Vue Components
// Vue.component('activity-notifications', require('./components/ActivityNotifications.vue').default);

// // Initialize Vue
// const app = new Vue({
//     el: '#app',
// });




// import { createApp } from 'vue';
// import router from './router'

// import ActivityNotifications from './components/ActivityNotifications.vue';

// createApp({
//     components: {
//         ActivityNotifications
//     }
// }).mount('#app')


import { createApp } from 'vue'
import HelloWorld from './components/Welcome'

const app = createApp({})

app.component('hello-world', HelloWorld)

app.mount('#app')