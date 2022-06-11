import { createRouter, createWebHistory } from 'vue-router'

import ActivityNotifications from '../components/ActivityNotifications.vue'

const routes = [
    {
        path: '/dashboard',
        name: 'companies.index',
        component: ActivityNotifications
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
})