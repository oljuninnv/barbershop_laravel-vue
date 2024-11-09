import {createRouter, createWebHistory} from "vue-router"
import Home from "../views/HomeView.vue"
import Login from "../views/Auth/Login.vue"
import SendResetLink from "../views/Auth/SendResetLink.vue"
import ResetPassword from "../views/Auth/ResetPassword.vue"
import Register from "../views/Auth/Register.vue"
import UserProfile from "../views/UserProfile.vue"
import Record from "../views/Record.vue"
import AdminPanel from "../views/AdminPanelView.vue"
import NotFoundPage from "../views/NotFoundPage.vue"

const routes = [
    {path:'/',name:'Home',component:Home},
    {path:'/login',name:'Login',component:Login},
    {path:'/register',name:'Register',component:Register},
    {path:'/user_profile',name:'UserProfile',component:UserProfile},
    {path:'/record',name:'Record',component:Record},
    {path:'/admin',name:'AdminPanel',component:AdminPanel},
    {path: '/auth/restore',name: 'SendResetLink',component: SendResetLink},
    {path: '/auth/restore/confirm',name: 'ResetPassword',component: ResetPassword},
    {path: '/:catchAll(.*)',component: NotFoundPage, name: 'NotFoundPage'},
];

const router = createRouter({
    history:createWebHistory(),
    routes
}    
)

export default router