import VueRouter from 'vue-router';
import Vue from "vue";

Vue.use(VueRouter);

//pages
import Home from '../views/Home'
import Content from "../components/Layout/Content";

import UserIndex from "../views/User/Index"
import UserCreate from "../views/User/Create"
import UserEdit from "../views/User/Edit"
import UserComment from "../views/User/Comments"

import TagIndex from "../views/Tag/Index"
import TagCreate from "../views/Tag/Create"
import TagEdit from "../views/Tag/Edit"

import Login from "../views/Login";

import Profile from "../views/Admin/Profile"
import NotFoundPage from "../views/404"

//config
const router = new VueRouter({
    mode: "history",
    routes: configRoutes()// short for `routes: routes`
});

//routes
function configRoutes() {
    return [
        {
            path: "/admin/login",
            component: Login,
        },
        {
            path: "/admin/",
            meta: {requiresAuth: true},
            component: Content,
            children: [
                {
                    path: '/',
                    component: Home,
                }
            ]
        },
        {
            path: "/admin/user",
            component: Content,
            meta: {requiresAuth: true},
            children: [
                {
                    path: '/',
                    component: UserIndex,
                },
                {
                    path: 'create',
                    component: UserCreate,
                },
                {
                    path: 'edit/:id',
                    component: UserEdit,
                },
                {
                    path: ':id/comments',
                    component: UserComment,
                },
            ]
        },
        {
            path: "/admin/tag",
            component: Content,
            meta: {requiresAuth: true},
            children: [
                {
                    path: '/',
                    component: TagIndex,
                },
                {
                    path: 'create',
                    component: TagCreate,
                },
                {
                    path: 'edit/:id',
                    component: TagEdit,
                },
            ]
        },
        {
            path:"/admin/profile",
            component: Content,
            meta:{requiresAuth: true},
            children: [
                {
                    path: '/',
                    component: Profile,
                }
            ]
        },
        {
            path:"/*",
            component: NotFoundPage,
        }
    ];
}

export default router;
