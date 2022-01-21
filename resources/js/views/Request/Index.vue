<template>
  <div class="container">
    <div class="manager-header">
      <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
        </ol>
        <h6 class="slim-pagetitle">الطلبات</h6>
      </div><!-- slim-pageheader -->
    </div><!-- manager-header -->

    <div class="manager-wrapper">
      <div class="manager-right">
        <div class="section-wrapper mb-3" style="padding:20px 20px 5px 20px !important;">
          <div class="form-layout text-right">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">اسم الموظف او تليفونه</label>
                  <input class="form-control" style="height: calc(2.5005rem + 2px);"
                         v-model="keyword"
                         type="text" placeholder="البحث بأسم الموظف او تليفونه">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">التاريخ</label>
                  <input class="form-control" style="height: calc(2.5005rem + 2px);"
                         v-model="requestDate"
                         type="date">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">حاله الطلب</label>
                  <select class="form-control" v-model="requestStatus">
                    <option value="0">الكل</option>
                    <option value="pending">في الإنتظار</option>
                    <option value="approved">موافق</option>
                    <option value="rejected">مرفوض</option>
                  </select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">نوع الطلب</label>
                  <select class="form-control" v-model="requestType">
                    <option value="0">الكل</option>
                    <option value="late_and_leave">تأخير او مغادرة</option>
                    <option value="vacation">اجازة</option>
                    <option value="errand">مأمورية</option>
                    <option value="loan">سلفه</option>
                  </select>
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

        <div class="section-wrapper">
          <div class="table-responsive">
            <table class="table mg-b-0">
              <thead>
              <tr class="text-center">
                <th class="text-center">#</th>
                <th class="text-center" style="width: 10%">نوع الطلب</th>
                <th class="text-center" style="width: 20%">اسم الموظف</th>
                <th class="text-center" style="width: 20%">حالته</th>
                <th class="text-center" style="width: 20%">وقت الطلب</th>
                <th class="text-center" style="width: 25%">الأجراءات</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(item,index) in items">
                <th class="valign-middle text-center p-0" scope="row">{{ item.id }}</th>
                <td class="valign-middle text-center p-0">
                  {{ item.employee.name.substr(0, 30) }}
                </td>
                <td class="valign-middle text-center p-0">
                  {{ item.type_name }}
                </td>
                <td class="valign-middle text-center p-0">
                  <i class="text-success"
                     v-if="item.status=='approved'">
                    {{ item.status_name }}
                  </i>
                  <i class="text-warning"
                     v-if="item.status=='pending'">
                    {{ item.status_name }}
                  </i>
                  <i class="text-danger"
                     v-if="item.status=='rejected'">
                    {{ item.status_name }}
                  </i>

                </td>
                <td class="valign-middle text-center p-0">
                  {{ item.created_at }}
                </td>
                <td class="valign-middle text-center p-0">
                  <div class="mt-3 col-12 btn-demo" style="display: inline-flex">
                    <router-link :to="'/admin/request/edit/'+item.id"
                                 class="btn btn-oblong btn-outline-primary btn-block mg-b-10 mt-2 ml-2">
                      <i class="fas fa-comment-alt"></i> عرض
                    </router-link>
                    <button
                        class="btn btn-oblong btn-outline-danger btn-block mg-b-10 mt-2 ml-2"
                        type="button"
                        @click="deleteItem(item.id,index)"
                    >
                      <i class="fa fa-trash"></i> حذف
                    </button>
                  </div>
                </td>
              </tr>
              </tbody>
            </table>
          </div><!-- table-responsive -->
        </div><!-- section-wrapper --><!-- row -->
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
      requestStatus: 0,
      requestDate: null,
      requestType: 0,
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
    }
  },
  methods: {
    reload() {
      this.keyword = null;
      this.requestStatus = 0;
      this.requestDate = null;
      this.requestType = 0;
      this.getAll();
    },
    getAll() {
      axios.get('/request', {
        params: {
          status: this.requestStatus,
          date: this.requestDate,
          type: this.requestType,
          keyword: this.keyword,
          page: this.currentPage, is_paginate: 1
        }
      }).then(response => {
        this.items = response.data.data.data;
        this.currentPage = response.data.data.current_page;
        this.perPage = response.data.data.per_page;
        this.rows = response.data.data.total
      })
          .catch(err => console.log(err));
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
              .delete("request/" + id)
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
    }
  }
}
</script>

<style scoped>

</style>
