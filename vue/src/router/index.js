import {createRouter, createWebHistory} from "vue-router"
import Home from "../views/HomeView.vue"
import Login from "../views/Auth/Login.vue"
import SendResetLink from "../views/Auth/SendResetLink.vue"
import ResetPassword from "../views/Auth/ResetPassword.vue"
import Register from "../views/Auth/Register.vue"
import UserProfile from "../views/UserProfile.vue"
import BarberProfile from "../components/Profiles/BarberProfile.vue"
import AdminProfile from "../components/Profiles/AdminProfile.vue"
import Record from "../views/Record.vue"
import AdminPanel from "../views/AdminPanelView.vue"

const routes = [
    {path:'/',name:'Home',component:Home},
    {path:'/login',name:'Login',component:Login},
    {path:'/register',name:'Register',component:Register},
    {path:'/user_profile',name:'UserProfile',component:UserProfile},
    {path:'/barber_profile',name:'BarberProfile',component:BarberProfile},
    {path:'/admin_profile',name:'AdminProfile',component:AdminProfile},
    {path:'/record',name:'Record',component:Record},
    {path:'/admin',name:'AdminPanel',component:AdminPanel},
    {
        path: '/auth/restore',
        name: 'SendResetLink',
        component: SendResetLink        
    },
    {
        path: '/auth/restore/confirm',
        name: 'ResetPassword',
        component: ResetPassword        
    },
];

const router = createRouter({
    history:createWebHistory(),
    routes
}    
)

export default router