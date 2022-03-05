<template>
  <div class="container">
    <div class="manager-header">
      <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
        </ol>
        <h6 class="slim-pagetitle">المواقع</h6>
      </div><!-- slim-pageheader -->
    </div><!-- manager-header -->

    <div class="manager-wrapper">
      <div class="manager-right">
        <div class="section-wrapper">
          <div class="row mb-1">
            <div class="col-md-3">
              <input type="search" class="form-control"
                     style="border-radius: 50px;padding:2px;height: 41.9px; "
                     @input="getAll()"
                     v-model="keyword"
                     placeholder="ابحث عن المواقع..." aria-controls="datatable1">
            </div>
            <div class="col-6 valign-middle text-center" style="font-size: 21px;">
              <label> الموقع: </label>
              <router-link :to="'/admin/project/edit/'+project_id" class="font-weight-bold">
                {{ project.name_ar.substr(0, 20) }}/{{ project.name_en.substr(0, 20) }}
              </router-link>
            </div>
            <div class="col-md-3">
              <router-link :to="'/admin/project/'+project_id+'/site/create'" class="btn btn-outline-primary">
                اضف جديد
              </router-link>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table mg-b-0">
              <thead>
              <tr class="text-center">
                <th class="text-center">#</th>
                <th class="text-center" style="width: 30%">الاسم باللغه العربيه</th>
                <th class="text-center" style="width: 30%">الاسم باللغه الانجليزيه</th>
                <th class="text-center" style="width: 10%">المسافه</th>
                <th class="text-center" style="width: 35%">الأجراءات</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(item,index) in items">
                <th class="valign-middle text-center p-0" scope="row">{{ item.id }}</th>
                <td class="valign-middle text-center p-0">
                  {{ item.name_ar.substr(0, 30) }}
                </td>
                <td class="valign-middle text-center p-0">
                  {{ item.name_en.substr(0, 30) }}
                </td>
                <td class="valign-middle text-center p-0">
                  {{ item.redius }}
                </td>
                <td class="valign-middle text-center p-0">
                  <div class="mt-3 col-12 btn-demo" style="display: inline-flex">
                    <router-link :to="'/admin/project/'+project_id+'/site/edit/'+item.id"
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
                    <router-link
                        :to="'/admin/project/'+project_id+'/site/'+item.id+'/attendances'"
                        class="btn btn-oblong btn-outline-dark btn-block mg-b-10 mt-2 ml-2"
                        type="button"
                    >
                      <i class="icon fas fa-copy"></i> الحضور
                    </router-link>
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
      project_id: 0,
      project: null,
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
    this.project_id = this.$route.params.project_id
    this.show = true;
    this.getAll();
    this.getProject();
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
    getAll() {
      axios.get('/sites', {
        params: {
          project_id: this.project_id,
          active: 2,
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
    getProject() {
      axios.get('/project/' + this.project_id)
          .then(response => {
            this.project = response.data.data;

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
              .delete("sites/" + id)
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
