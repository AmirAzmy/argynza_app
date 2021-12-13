import store from "../../store";

export default (to, from, next) => {
    if (!store.getters['auth/authenticated']) {
        return next('/admin/login')
    }
    next();
}
