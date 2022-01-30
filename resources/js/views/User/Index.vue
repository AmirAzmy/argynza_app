<template>
  <div class="container">
    <div class="manager-header">
      <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
          <router-link to="/admin/user/create" class="btn btn-primary">اضف موظف جديد</router-link>
        </ol>
        <h6 class="slim-pagetitle">الموظفين
          <!--          <i class="fas fa-sync-alt"></i>-->
        </h6>
      </div><!-- slim-pageheader -->
    </div><!-- manager-header -->

    <div class="manager-wrapper">
      <div class="manager-right">
        <div class="section-wrapper" style="padding:20px 20px 5px 20px !important;">
          <div class="form-layout text-right">
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">اسم الموظف او تليفونه</label>
                  <input class="form-control" style="height: calc(2.5005rem + 2px);"
                         v-model="keyword"
                         type="text" placeholder="البحث بأسم الموظف او تليفونه">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">نوع الموظف</label>
                  <select class="form-control" v-model="userType">
                    <option value="0">الكل</option>
                    <option value="5">موظف</option>
                    <option value="4">مدير مباشر</option>
                    <option value="3">مدير مشروع</option>
                  </select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label"
                         style="display: block; margin-bottom:20px ">التفعيل </label>
                  <label class="rdiobox ml-5" style="display:  inline">
                    <input v-model="userActive" type="radio" value="2">
                    <span>الكل</span>
                  </label>
                  <label class="rdiobox ml-5" style="display:  inline">
                    <input v-model="userActive" type="radio" value="1">
                    <span>فعال</span>
                  </label>
                  <label class="rdiobox" style="display: inline">
                    <input v-model="userActive" type="radio" value="0">
                    <span>غير فعال</span>
                  </label>
                </div>
              </div><!-- col-4 -->
              <div class="col-9"></div>
              <div class="col-lg-3 text-left">
                <div class="form-group">
                  <label class="form-control-label"></label>
                  <button type="button" @click="getAll()" class="btn btn-oblong btn-primary btn-sm"
                          style="width: 75px;">
                    <i class="fas fa-search"></i>
                  </button>
                  <button type="button" @click="reload()" class="btn btn-oblong btn-secondary btn-sm"
                          style="width: 75px;">
                    <i class="fas fa-sync-alt"></i>
                  </button>
                </div>
              </div><!-- col-4 -->
            </div><!-- row -->

            <div class="form-layout-footer text-left">
            </div><!-- form-layout-footer -->
          </div><!-- form-layout -->
        </div><!-- section-wrapper -->
        <div class="row row-sm">
          <div v-if="!items.length"
               class="col-sm-12 alert alert-warning text-center" role="alert">
            لا يوجد نتيجه
          </div>
          <div class="col-sm-6 col-lg-3 mt-3" v-for="(item,index) in items">
            <div class="card-contact">
              <div class="tx-center">
                <router-link :to="{path:'/admin/user/edit/' +item.id}">
                  <img :src="item.image" width="110" height="110" class="card-img">
                </router-link>
                <h5 class="mg-t-10 mg-b-5">
                  <router-link :to="{path:'/admin/user/edit/' +item.id}" class="contact-name">
                    {{ item.name.substring(0, 16) }}
                  </router-link>
                </h5>
                <p>{{ item.created_at }}</p>
                <div class="custom-control custom-switch mb-3">
                  <input @change="changeStatus(item.id,item.active,index)" type="checkbox"
                         v-model="item.active"
                         class="custom-control-input" :id="'customSwitches'+item.id">
                  <label class="custom-control-label" :for="'customSwitches'+item.id">التفعيل</label>
                </div>
              </div><!-- tx-center -->
              <p class="contact-item">
                <span>الهاتف:</span>
                <span>{{ item.phone }}</span>
              </p><!-- contact-item -->
              <p class="contact-item">
                <span>المسمى الوظيفي: </span>
                <span>{{ item.job_title.substr(0, 16) }}</span>
              </p><!-- contact-item -->
              <p class="contact-item">
                <span>نوع الموظف: </span>
                <span>{{ item.user_type }}</span>
              </p><!-- contact-item -->
              <p class="contact-item">
                <span>تم التحقق من الهاتف:</span>
                <span>
                    <i v-if="item.phone_verified_at" class="fas fa-check-double text-success"></i>
                    <i v-else @click="showCode(item.verification_code)" class="fas fa-key ml-2"></i>
                  <i v-if="!item.phone_verified_at" class="fas fa-times text-danger"></i>
                </span>
              </p>
              <!--                            <p class="contact-item">-->
              <div class="mt-3 btn-demo" style="display: flex;justify-content: center;">

                <button class="btn btn-oblong btn-outline-danger btn-block mg-b-10 col-6 mt-2 ml-2"
                        type="button"
                        @click="deleteItem(item.id, index)"
                >
                  <i class="fa fa-trash"></i> حذف
                </button>
<!--                <router-link :to="'/admin/user/'+item.id+'/comments'"-->
<!--                             class="btn btn-oblong btn-outline-dark btn-block mg-b-10 col-6">-->
<!--                  <i class="fas fa-comment-alt"></i> كومنتات-->
<!--                </router-link>-->
              </div>
              <!--                            </p>&lt;!&ndash; contact-item &ndash;&gt;-->
            </div><!-- card -->
          </div><!-- col -->
        </div><!-- row -->

        <div class="d-flex justify-content-center mt-3">
          <b-pagination
              v-if="show"
              v-model="currentPage"
              @input="getAll"
              :total-rows="rows"
              :per-page="perPage"
              first-text="<<"
              prev-text="<"
              next-text=">"
              last-text=">>"
          ></b-pagination>
        </div>

      </div><!-- manager-right -->
    </div><!-- manager-wrapper -->

  </div><!-- container -->

</template>

<script>
export default {
  name: "Index",
  data() {
    return {
      keyword: null,
      userType: 0,
      userActive: 2,
      perPage: 3,
      currentPage: 1,
      rows: 15,
      items: [],
      show: false,
    }
  },
  created() {
    if (this.$route.query.page) {
      this.currentPage = this.$route.query.page;
    }
    if (this.$route.query.keyword) {
      this.keyword = this.$route.query.keyword
    }
    this.show = true;
    this.getAll();
  },
  watch: {
    currentPage: {
      handler: function (value) {
        if (this.$route.query.page !== value) {
          this.$router.push({
            path: "",
            query: {page: this.currentPage}
          });
          this.getAll();
        }
      }
    },
    $route(to) {
      this.keyword = (to.query.keyword) ? to.query.keyword : this.keyword = null;
      this.getAll()
    }
  },
  methods: {
    reload() {
      this.userActive = 2;
      this.keyword = null;
      this.userType = 0;
      this.getAll();
    },
    getAll() {
      axios.get('/users', {
        params: {
          active: this.userActive,
          keyword: this.keyword,
          type: this.userType,
          page: this.currentPage, return_all: 0
        }
      }).then(response => {
        this.items = response.data.data.data;
        this.currentPage = response.data.data.current_page;
        this.perPage = response.data.data.per_page;
        this.rows = response.data.data.total
      })
          .catch(err => console.log(err));
    },
    changeStatus(id, value, index) {
      swal({
        title: "هل انت متاكد ؟",
        text: "سيتم تغير  حاله التفعيل للمستخدم , تاكيد؟",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          axios
              .post("users/" + id, {
                '_method': 'PUT',
                'active': value ? 1 : 0,
              })
              .then(res => {
                this.items[index].active = (value) ? 1 : 0;
                swal("تم التعديل بنجاح", {
                  icon: "success"
                });
              })
              .catch(err => {
                this.items[index].active = !(value)
                console.log(err.response.data);
                this.errorMessages(err.response.data);
              });
        } else {
          this.items[index].active = !(value)
          swal("لم يتم التغيير يرجى التاكد من البيانات");
        }
      });
    },
    deleteItem(id, index) {
      swal({
        title: "هل انت متاكد ؟",
        text: "بمجرد الحذف لا يمكنك استرجاعه مره اخره, تاكيد؟",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          axios
              .delete("users/" + id)
              .then(res => {
                this.items.splice(index, 1);
                swal("تم الحذف بنجاح", {
                  icon: "success"
                });
              })
              .catch(error => console.log(error));
        } else {
          swal("لم يتم الحذف يرجى التاكد من البيانات");
        }
      });
    },
    showCode(code) {
      swal('رمز التحقق', code);
    }

  }
}
</script>

<style scoped>

</style>
