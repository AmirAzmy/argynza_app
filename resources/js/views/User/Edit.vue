<template>
  <div class="container">
    <div class="slim-pageheader">
      <ol class="breadcrumb slim-breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="/admin/user">موظف</router-link>
        </li>
        <li class="breadcrumb-item">
          <router-link to="/admin">الرئيسية</router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">تعديل بيانات موظف</li>
      </ol>
      <h6 class="slim-pagetitle">الموظفيين</h6>
    </div><!-- slim-pageheader -->

    <div class="section-wrapper tx-right">
      <div class="float-left">
        <button class="btn btn-oblong btn-sm btn-outline-primary ml-2"
                @click="showChangePassword=!showChangePassword">
          <div class="tx-15" v-if="!showChangePassword"><i class="fas fa-cogs"></i> تغير كلمه المرور</div>
          <div class="tx-15" v-if="showChangePassword"><i class="fas fa-cogs"></i> تعديل بيانات المستخدم</div>
        </button>
      </div>
      <label class="section-title mt-3" style="display: inline">تعديل بيانات موظف </label>


      <p class="mg-b-20 mg-sm-b-40"></p>
      <div class="form-layout" v-if="showChangePassword">
        <div class="row mg-b-25">

          <div class="col-lg-6 mt-4">
            <div class="form-group mg-b-10-force">
              <label class="form-control-label">الكلمه المرور: <span
                  class="tx-danger">*</span></label>
              <input class="form-control" type="password" v-model="password"
                     placeholder="الكلمه المرور:test@123123">
            </div>
          </div><!-- col-4 -->
          <div class="col-lg-6 mt-4">
            <div class="form-group mg-b-10-force">
              <label class="form-control-label">ناكيد الكلمه المرور: <span class="tx-danger">*</span></label>
              <input class="form-control" type="password" v-model="password_confirmation"
                     placeholder="ناكيد الكلمه المرور:test@123123">
            </div>
          </div><!-- col-4 -->
        </div>
        <div class="form-layout-footer text-left">
          <button class="btn btn-primary bd-0" :disabled="disabled" type="button"
                  @click="changePassword()">
            حفظ
          </button>
          <button class="btn btn-secondary bd-0" type="button" @click="$router.go(-1)">رجوع</button>
        </div><!-- form-layout-footer -->
      </div>
      <div class="form-layout" v-if="!showChangePassword">
        <div class="row mg-b-25">
          <div class="col-lg-5">
            <div class="form-group">
              <label class="form-control-label">الاسم: <span class="tx-danger">*</span></label>
              <input class="form-control" v-model="item.name" type="text"
                     placeholder="اضف الاسم للموظف">
            </div>
          </div><!-- col-4 -->

          <div class="col-lg-4">
            <div class="form-group">
              <label class="form-control-label">رقم الهاتف </label>
              <input class="form-control" v-model="item.phone" type="tel"
                     placeholder="رقم الهاتف للموظف">
            </div>
          </div><!-- col-4 -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="form-control-label">المسمى الوظيفى </label>
              <input class="form-control" v-model="item.job_title" type="text"
                     placeholder="رقم المسمى الوظيفى(مهندس معمارى)">
            </div>
          </div><!-- col-4 -->
          <div class="col-lg-5">
            <div class="form-group mg-b-10-force">
              <label class="form-control-label">المشروع: <span class="tx-danger">*</span></label>
              <select v-model="item.project_id"
                      class="custom-select">
                <option value="0" disabled> اختر مشروع</option>
                <option v-for="project in projects" :value="project.id">
                  {{ project.name_ar }}/ {{ project.name_en }}
                </option>
              </select>
            </div>
          </div><!-- col-8 -->

          <div class="col-lg-4">
            <div class="form-group">
              <label class="form-control-label">الترتيب الوظيفى : <span class="tx-danger">*</span></label>
              <select class="form-control" v-model="item.type">
                <option value="5">موظف</option>
                <option value="4">مدير مباشر</option>
                <option value="3">مدير مشروع</option>
<!--                <option value="2">اتش ار</option>-->
<!--                <option value="1">مدير عام</option>-->
              </select>
            </div>
          </div><!-- col-4 -->
          <div class="col-lg-3">
            <div class="form-group">
              <label class="form-control-label"
                     style="display: block; margin-bottom:20px ">التفعيل </label>
              <label class="rdiobox ml-5" style="display:  inline">
                <input v-model="item.active" type="radio" value="1">
                <span>فعال</span>
              </label>
              <label class="rdiobox" style="display: inline">
                <input v-model="item.active" type="radio" value="0">
                <span>غير فعال</span>
              </label>
            </div>
          </div><!-- col-4 -->
          <div class="col-3"></div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="form-control-label">الصوره: <span class="tx-danger">*</span></label>
              <div>
                <div class="container">
                  <img @click="$refs.image.click()" :src="image" v-if="image" class="uploaded-image">
                  <div @click="$refs.image.click()" v-if="!image"
                       class="upload-cta text-center text-muted">
                    <i class="far fa-file-image" style="font-size: 50px"></i>
                    <br>
                    <br>
                    قم بتحميل صورة الفيديو هنا
                  </div>
                </div>
              </div>
              <input class="form-control" ref="image" @change="uploadFile('image')" type="file"
                     style="display: none;">
            </div>
          </div><!-- col-4 -->


        </div><!-- row -->

        <div class="form-layout-footer text-left">
          <button class="btn btn-primary bd-0" type="button" @click="updateItem()">حفظ</button>
          <button class="btn btn-dark bd-0" type="button" @click="$router.go(-1)">رجوع</button>
        </div><!-- form-layout-footer -->
      </div><!-- form-layout -->
    </div><!-- section-wrapper -->

  </div>
</template>

<script>

export default {
  name: "Edit",
  data() {
    return {
      showChangePassword: false,
      disabled: false,
      password: null,
      password_confirmation: null,
      image: null,
      projects: [],
      item: {
        name: null,
        phone: null,
        job_title: null,
        password: null,
        type: 5,
        project_id: null,
        active: 1,
        image: null,
      }
    }
  },
  created() {
    this.item.id = this.$route.params.id;
    this.getItem();
    this.getProjects();
  },
  methods: {
    getItem() {
      axios.get('/users/profile?user_id=' + this.item.id)
          .then(response => {
            this.item = response.data.data;
            this.image = this.item.image;
          })
          .catch(err => {
            this.errorMessages(err.response.data);
          });
    },
    updateItem() {
      let item = this.item;
      if (!(item.image instanceof File)) {
        delete item.image;
      }
      let formData = new FormData();
      for (const property in item) {
        if (item[property] != null) {
          formData.append(property, item[property]);
        }
      }
      formData.append('_method', "PUT");
      axios.post("/users/" + this.item.id, formData).then(rsp => {
        // this.$router.push('/admin/user');
        this.getItem();
        swal("تم بنجاح👏", "تم التعديل جديد", "success");
      }).catch(err => {
        this.errorMessages(err.response.data);
        console.log(err.response)
      })
    },
    getProjects() {
      axios.get("/project?return_all=1").then(rsp => {
        this.projects = rsp.data.data;
      }).catch(err => {
        this.errorMessages(err.response.data);
        console.log(err.data)
      })
    },
    uploadFile(ref) {
      this.item[ref] = this.$refs[ref].files[0];
      // const file = e.target.files[0];
      // this.image_is_changed = true;
      this[ref] = URL.createObjectURL(this.item[ref]);
    },
    changePassword() {
      axios.post("/users/change-password", {
        user_id: this.item.id,
        password: this.password,
        password_confirmation: this.password_confirmation,
      }).then(rsp => {
        // this.$router.push('/admin/user');
        swal("تم بنجاح👏", "تم اضافة  جديد", "success");
        console.log(rsp.data);
      }).catch(err => {
        this.errorMessages(err.response.data);
        console.log(err.data)
      })
    },
  }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
.upload-cta {
  display: inline-table;
  height: 190px;
  width: 100%;
  cursor: pointer;
  padding-top: 36px;
  line-height: 17px;
  border: 2px dashed #a3a3a3;
  background: #eaeaea;
  font-size: .9em;
}

.uploaded-image {
  display: inline-table;
  height: 200px;
  width: 100%;
  background-size: cover !important;
  text-indent: -9000px;
  border-style: solid;
}


input[type=file] {
  opacity: .5;
  margin-bottom: 20px;
}
</style>
