<template>
    <div class="slim-header">
        <div class="container">
            <div class="slim-header-left">
                <h2 class="slim-logo">
                    <router-link to="/admin"><span>لوحه تحكم </span></router-link>
                </h2>

                <div class="search-box">
                    <input type="text" @keyup.enter="search()" v-model="search_key" class="form-control" placeholder="ابحث عن فيديوهات">
                    <button @click="search()" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div><!-- search-box -->
            </div><!-- slim-header-left -->
            <div class="slim-header-right">
                <!--                <div class="dropdown dropdown-b">-->
                <!--                    <a href="" class="header-notification" data-toggle="dropdown">-->
                <!--                        <i class="icon ion-ios-bell-outline"></i>-->
                <!--                        <span class="indicator"></span>-->
                <!--                    </a>-->
                <!--                    <div class="dropdown-menu">-->
                <!--                        <div class="dropdown-menu-header">-->
                <!--                            <h6 class="dropdown-menu-title">الاشعارات</h6>-->
                <!--                            <div>-->
                <!--                                <a href="">اشر عليها بانها قرات</a>-->
                <!--&lt;!&ndash;                                <a href="">Settings</a>&ndash;&gt;-->
                <!--                            </div>-->
                <!--                        </div>&lt;!&ndash; dropdown-menu-header &ndash;&gt;-->
                <!--                        <div class="dropdown-list">-->
                <!--                            &lt;!&ndash; loop starts here &ndash;&gt;-->
                <!--                            <a href="" class="dropdown-link">-->
                <!--                                <div class="media">-->
                <!--                                    <img src="http://via.placeholder.com/500x500" alt="">-->
                <!--                                    <div class="media-body">-->
                <!--                                        <p><strong>Suzzeth Bungaos</strong> tagged you and 18 others in a post.</p>-->
                <!--                                        <span>October 03, 2017 8:45am</span>-->
                <!--                                    </div>-->
                <!--                                </div>&lt;!&ndash; media &ndash;&gt;-->
                <!--                            </a>-->
                <!--                            &lt;!&ndash; loop ends here &ndash;&gt;-->
                <!--                            <a href="" class="dropdown-link">-->
                <!--                                <div class="media">-->
                <!--                                    <img src="http://via.placeholder.com/500x500" alt="">-->
                <!--                                    <div class="media-body">-->
                <!--                                        <p><strong>Mellisa Brown</strong> appreciated your work <strong>The Social Network</strong></p>-->
                <!--                                        <span>October 02, 2017 12:44am</span>-->
                <!--                                    </div>-->
                <!--                                </div>&lt;!&ndash; media &ndash;&gt;-->
                <!--                            </a>-->
                <!--                            <a href="" class="dropdown-link read">-->
                <!--                                <div class="media">-->
                <!--                                    <img src="http://via.placeholder.com/500x500" alt="">-->
                <!--                                    <div class="media-body">-->
                <!--                                        <p>20+ new items added are for sale in your <strong>Sale Group</strong></p>-->
                <!--                                        <span>October 01, 2017 10:20pm</span>-->
                <!--                                    </div>-->
                <!--                                </div>&lt;!&ndash; media &ndash;&gt;-->
                <!--                            </a>-->
                <!--                            <a href="" class="dropdown-link read">-->
                <!--                                <div class="media">-->
                <!--                                    <img src="http://via.placeholder.com/500x500" alt="">-->
                <!--                                    <div class="media-body">-->
                <!--                                        <p><strong>Julius Erving</strong> wants to connect with you on your conversation with <strong>Ronnie Mara</strong></p>-->
                <!--                                        <span>October 01, 2017 6:08pm</span>-->
                <!--                                    </div>-->
                <!--                                </div>&lt;!&ndash; media &ndash;&gt;-->
                <!--                            </a>-->
                <!--                            <div class="dropdown-list-footer">-->
                <!--                                <a href="page-notifications.html"><i class="fa fa-angle-down"></i> اظهار الكل</a>-->
                <!--                            </div>-->
                <!--                        </div>&lt;!&ndash; dropdown-list &ndash;&gt;-->
                <!--                    </div>&lt;!&ndash; dropdown-menu-right &ndash;&gt;-->
                <!--                </div>&lt;!&ndash; dropdown &ndash;&gt;-->
                <div class="dropdown dropdown-c">
                    <a href="#" class="logged-user" data-toggle="dropdown">
                        <img src="/assets/avat.webp" alt="">
                        <span>{{adminName}}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <nav class="nav">
                            <router-link :to="'/admin/profile'" class="nav-link"><i class="icon ion-person"></i> الملف الشخصى</router-link>
                            <a @click="submit()" href="#" class="nav-link"><i class="icon ion-forward"></i> تسجيل الخروج</a>
                        </nav>
                    </div><!-- dropdown-menu -->
                </div><!-- dropdown -->

            </div><!-- header-right -->
        </div><!-- container -->
    </div>
</template>

<script>
    import {mapActions} from 'vuex';
    export default {
        name: "Header",
        data() {
            return {
                search_key: ''
            }
        },
        computed: {
            adminName() {
                if (this.$store.state.auth.user.name) {
                    return this.$store.state.auth.user.name;
                }
                return localStorage.getItem('name');
            }
        },
        methods: {
            ...mapActions({
                logoutAction: 'auth/logout'
            }),
            submit() {
                this.logoutAction()
                    .then(() => {
                        this.$router.push('/admin/login')
                    });
            },
            search() {
                this.$router.push('/admin/video?keyword=' + this.search_key);
            },
        }
    }
</script>

<style scoped>

</style>
