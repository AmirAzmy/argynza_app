<template>
    <div class="slim-navbar">
        <div class="container">
            <ul class="nav">
                <li :class="{'nav-item':true,active:item.active}" v-for="item in navItems">
                    <router-link class="nav-link" :to="item.route">
                        <i :class="item.icon"></i>
                        <span> {{item.name}} </span>
                    </router-link>
                </li>
            </ul>
        </div><!-- container -->
    </div>
</template>

<script>
    export default {
        name: "NavBar",
        data() {
            return {
                navItems: [
                    {name: 'الرئيسية', icon: 'icon ion-ios-home-outline', route: '/admin', active: 1},
                    {name: ' الموظفيين ', icon: 'icon fas fa-user-tie', route: '/admin/user', active: 0},
                    // {name: ' التاج ', icon: 'icon ion-ios-pricetags', route: '/admin/tag', active: 0},
                ]
            }
        },
        watch: {
            $route(to, from) {
                this.setActiveNavItem(to.path);
            }
        },
        created() {
            this.setActiveNavItem(this.$route.path)
        },
        methods: {
            setActiveNavItem(path) {
                this.navItems.forEach(item => {
                    item.active = 0;
                    if (item.route == path) {
                        item.active = 1;
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
