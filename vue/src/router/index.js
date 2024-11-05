import {createRouter, createWebHistory} from "vue-router"
import Home from "../views/HomeView.vue"
import Login from "../views/Login.vue"
import Register from "../views/Register.vue"
import UserProfile from "../views/UserProfile.vue"
import BarberProfile from "../components/BarberProfile.vue"
import AdminProfile from "../components/AdminProfile.vue"
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
];

const router = createRouter({
    history:createWebHistory(),
    routes
}    
)

export default router