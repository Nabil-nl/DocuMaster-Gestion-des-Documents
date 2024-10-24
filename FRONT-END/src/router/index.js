import { createRouter, createWebHistory } from "vue-router";
import Main from "../components/Main.vue";
import Login from '../views/auth/Login.vue';
import Logout from '../views/auth/Logout.vue';
import Register from '../views/auth/Register.vue';
import PageAdmin from '../views/dashbord/admin/PageAdmin.vue'; 
import Categories from '../views/dashbord/admin/categories/Categories.vue'; 
import Documents from '../views/dashbord/admin/documents/Documents.vue'; 
import Users from '../views/dashbord/admin/users/Users.vue'; 
import Members from '../views/dashbord/member/Members.vue'; 
import categoriesMember from '../views/dashbord/member/CategoriesMember.vue'; 
import documentsMember from '../views/dashbord/member/DocumentsMember.vue'; 
import settingsMember from '../views/dashbord/member/SettingsMember.vue'; 

const routes = [
    { path: "/", name: "Main", component: Main },
    { path: '/login', name: 'Login', component: Login },
    { path: '/register', name: 'Register', component: Register },
    { path: '/logout', name: 'Logout', component: Logout },
        // Dashboard route
        {
            path: '/dashboard',
            name: 'Dashboard',
            
            children: [
              { path: 'pageadmin', component: PageAdmin, name: 'PageAdmin' },
              { path: 'categories', component: Categories, name: 'Categories' },
              { path: 'documents', component: Documents, name: 'Documents' },
              { path: 'users', component: Users, name: 'Users' },
             
            ],
        },
        {
          path: '/Members',
          name: 'Members',
          children: [
            { path: 'member', component: Members, name: 'Members' },
            { path: 'categoriesMember', component: categoriesMember, name: 'categoriesMember' },
            { path: 'documentsMember', component: documentsMember, name: 'documentsMember' },
            { path: 'settingsMember', component: settingsMember, name: 'settingsMember' },
           
            
           
          ],
        },
          

    
]
const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
  });
  
  export default router;