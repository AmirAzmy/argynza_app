<template>
   <div>
    <app-header></app-header>
    <nav-bar></nav-bar>

    <div class="slim-mainpanel">
            <router-view></router-view>
    </div>

    <app-footer></app-footer>
       <div class="overlay" v-if="isLoading">
           <vue-loaders-ball-beat class="to-center" color="blue" scale="2"></vue-loaders-ball-beat>
       </div>
   </div>
</template>

<script>
    import Header from "./Header";
    import NavBar from "./NavBar";
    import Footer from "./Footer";
    import { mapState } from 'vuex';
    export default {
        name: "Content",
        components: {
            AppHeader:Header,
            AppFooter:Footer,
            NavBar,
        },
        computed: {
            // ...mapGetters(['getLoader']),
            ...mapState(['isLoading', 'refCount'])
        },
    }
</script>

<style>
    .overlay {
        position: fixed; /* Sit on top of the page content */
        width: 100%; /* Full width (cover the whole page) */
        height: 100%; /* Full height (cover the whole page) */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
        z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
        cursor: pointer; /* Add a pointer on hover */
    }
    .to-center {
        position: absolute;
        top: 50%;
        left: 50%
    }
</style>
