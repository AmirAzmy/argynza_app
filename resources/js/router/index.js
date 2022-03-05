import VueRouter from 'vue-router';
import Vue from "vue";

Vue.use(VueRouter);

//pages
import Home from '../views/Home'
import Content from "../components/Layout/Content";

import UserIndex from "../views/User/Index"
import UserCreate from "../views/User/Create"
import UserEdit from "../views/User/Edit"
import UserAttendances from "../views/User/Attendances"
import UserComment from "../views/User/Comments"

import ProjectIndex from "../views/Project/Index"
import ProjectCreate from "../views/Project/Create"
import ProjectEdit from "../views/Project/Edit"

import SiteIndex from "../views/Site/Index"
import SiteCreate from "../views/Site/Create"
import SiteEdit from "../views/Site/Edit"
import SiteAttendance from "../views/Site/Attendances"

import RequestIndex from "../views/Request/Index"
import RequestDetails from "../views/Request/Details"

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
                    path: 'attendances/:id',
                    component: UserAttendances,
                },
                {
                    path: ':id/comments',
                    component: UserComment,
                },
            ]
        },
        {
            path: "/admin/project",
            component: Content,
            meta: {requiresAuth: true},
            children: [
                {
                    path: '/',
                    component: ProjectIndex,
                },
                {
                    path: 'create',
                    component: ProjectCreate,
                },
                {
                    path: 'edit/:id',
                    component: ProjectEdit,
                },
            ]
        },
        {
            path: "/admin/request",
            component: Content,
            meta: {requiresAuth: true},
            children: [
                {
                    path: '/',
                    component: RequestIndex,
                },
                {
                    path: 'details/:id',
                    component: RequestDetails,
                }
            ]
        },
        {
            path: "/admin/project/:project_id/site",
            component: Content,
            meta: {requiresAuth: true},
            children: [
                {
                    path: '/',
                    component: SiteIndex,
                },
                {
                    path: 'create',
                    component: SiteCreate,
                },
                {
                    path: 'edit/:id',
                    component: SiteEdit,
                },
                {
                    path: ':siteId/attendances',
                    component: SiteAttendance,
                },
            ]
        },
        {
            path: "/admin/profile",
            component: Content,
            meta: {requiresAuth: true},
            children: [
                {
                    path: '/',
                    component: Profile,
                }
            ]
        },
        {
            path: "/*",
            component: NotFoundPage,
        }
    ];
}

export default router;
