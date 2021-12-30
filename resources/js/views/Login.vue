<template>
    <div class="signin-wrapper">

        <div class="signin-box">
            <h2 class="slim-logo"><a href="#">لوحه التحكم<span>.</span></a></h2>
            <h2 class="signin-title-primary">مرحبا بعودتك!</h2>
            <h3 class="signin-title-secondary">قم بتسجيل الدخول للمتابعة.</h3>

            <div class="form-group">
                <input type="text" v-model="form.phone" @keyup.enter="submit()" class="form-control" placeholder="أدخل بريدك الإلكتروني">
            </div><!-- form-group -->
            <div class="form-group mg-b-50">
                <input v-model="form.password" @keyup.enter="submit()" type="password" class="form-control" placeholder="ادخل كلمه المرور">
            </div><!-- form-group -->
            <p class="text-danger" v-if="showError"> البريد الإلكتروني او كلمه المرور غير صحيح</p>
            <button type="button" @click="submit()" class="btn btn-primary btn-block btn-signin">
                تسجيل الدخول
            </button>
        </div><!-- signin-box -->

    </div><!-- signin-wrapper -->

</template>

<script>
    import {mapActions} from 'vuex';
    export default {
        name: "Login",
        data() {
            return {
                form: {
                    phone: null,
                    password: null,
                },
                showError: false
            }
        },
        methods: {
            ...mapActions({
                login: 'auth/login'
            }),
            submit() {
                this.login(this.form)
                    .then(() => {
                        this.$router.push('/admin')
                    })
                    .catch(() => {
                        this.showError = true;
                    })
            }
        }
    }
</script>

<style scoped>

</style>
