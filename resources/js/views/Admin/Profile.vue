<template>
    <div class="container">

        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item">
                    <router-link to="/admin">الرئيسية</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">البيانات الشخصية</li>
            </ol>
            <h6 class="slim-pagetitle">البيانات الشخصية</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper">
            <label class="section-title">البيانات الاساسية</label>

            <div class="row">
                <div class="col-lg-6">
                    <label class="float-right">*الاسم</label>
                    <input v-model="$v.name.$model" :class="{'is-invalid': validationStatus($v.name)}" class="form-control" placeholder="الاسم" type="text">
                    <div v-if="!$v.name.required" class="invalid-feedback">* مطلوب ادخال حقل البريد الاسم</div>
                    <div v-if="!$v.name.minLength" class="invalid-feedback">* الاسم يجب ان لا يقل عن 3 احرف</div>
                    <div v-if="!$v.name.maxLength" class="invalid-feedback">* الاسم يجب ان لا يزيد عن 20 حرف</div>
                </div><!-- col -->
                <div class="col-lg-6">
                    <label class="float-right">*البريد الالكتروني</label>
                    <input v-model="$v.email.$model" :class="{'is-invalid': validationStatus($v.email)}" class="form-control" placeholder="البريد الالكتروني" type="email">
                    <div v-if="!$v.email.required" class="invalid-feedback">* مطلوب ادخال حقل البريد الالكتروني</div>
                </div><!-- col -->
            </div><!-- row -->

            <div class="row mg-t-20">
                <div class="col-lg-6">
                    <label class="float-right">*كلمة السر</label>
                    <input v-model="$v.password.$model" :class="{'is-invalid': validationStatus($v.password)}" class="form-control" placeholder="كلمة السر" type="password">
                    <div v-if="!$v.password.minLength" class="invalid-feedback">* كلمة السر يجب ان لا تقل عن 6 احرف</div>
                </div><!-- col -->

                <div class="col-lg-6">
                    <label class="float-right">*تأكيد كلمة السر</label>
                    <input v-model="$v.password_confirmation.$model" :class="{'is-invalid': validationStatus($v.password_confirmation)}" class="form-control" placeholder="اعد ادخال كلمة السر" type="password">
                    <div v-if="!$v.password_confirmation.sameAsPassword" class="invalid-feedback">* هذا الحقل يجب ان يكون مطابق لحقل كلمة السر</div>
                </div><!-- col -->
            </div><!-- row -->
            <div class="mg-t-20">
                <button @click="store" :disabled="is_loading" class="btn btn-primary">حفظ</button>
            </div>
        </div><!-- section-wrapper -->
    </div>
</template>

<script>
import {required, sameAs, minLength, maxLength} from 'vuelidate/lib/validators';
export default {
    name: "Profile.vue",
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            is_loading:false
        }
    },
    validations: {
        name: {required, minLength : minLength(3), maxLength : maxLength(20)},
        email: {required},
        password: {minLength : minLength(6)},
        password_confirmation: {sameAsPassword:sameAs('password')},
    },
    created() {
        this.user();
    },
    methods: {
        user() {
          axios.get('admin/profile')
            .then(res => {
                console.log(res);
                this.name = res.data.data.name
                this.email = res.data.data.email
            })
            .catch(err => {
                console.log(err)
            })
        },
        store() {
            this.$v.$touch();
            if(this.$v.$pendding || this.$v.$error) return;

            this.is_loading = true;
            let data = {}
            data._method = "PUT"
            data.name = this.name
            data.phone = this.email
            data.password = this.password
            data.password_confirmation = this.password_confirmation
            axios.post('admin/edit-admin', data)
                .then(res => {
                    swal("تم بنجاح👏", "تم تعديل البيانات", "success");
                }).catch(err => {
                console.log(err)
            })

            this.is_loading = false;
        },
        validationStatus(validation) {
            return typeof validation != "undefined" ? validation.$error : false
        },
    }
}
</script>

<style scoped>

</style>
