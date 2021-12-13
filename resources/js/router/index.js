import VueRouter from 'vue-router';
import Vue from "vue";

Vue.use(VueRouter);

//pages
import Home from '../views/Home'
import Content from "../components/Layout/Content";

import VideoIndex from "../views/Video/Index"
import VideoCreate from "../views/Video/Create"
import VideoEdit from "../views/Video/Edit"
import VideoComment from "../views/Video/Comments"

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
            path: "/admin/video",
            component: Content,
            meta: {requiresAuth: true},
            children: [
                {
                    path: '/',
                    component: VideoIndex,
                },
                {
                    path: 'create',
                    component: VideoCreate,
                },
                {
                    path: 'edit/:id',
                    component: VideoEdit,
                },
                {
                    path: ':id/comments',
                    component: VideoComment,
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
