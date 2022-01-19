<template>
  <div class="container">
    <div class="manager-header">
      <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
        </ol>
        <h6 class="slim-pagetitle">المشاريع</h6>
      </div><!-- slim-pageheader -->
    </div><!-- manager-header -->

    <div class="manager-wrapper">
      <div class="manager-right">
        <div class="section-wrapper">
          <div class="row mb-1">
            <div class="col-md-6">
              <input type="search" class="form-control"
                     style="border-radius: 50px;padding:2px;height: 41.9px; "
                     @input="getAll()"
                     v-model="keyword"
                     placeholder="ابحث عن المشاريع..." aria-controls="datatable1">
            </div>
            <div class="col-md-6">
              <router-link to="/admin/project/create" class="btn btn-outline-primary">
                اضف جديد
              </router-link>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table mg-b-0">
              <thead>
              <tr class="text-center">
                <th class="text-center">#</th>
                <th class="text-center" style="width: 10%">الصوره</th>
                <th class="text-center" style="width: 20%">الاسم باللغه العربيه</th>
                <th class="text-center" style="width: 20%">الاسم باللغه الانجليزيه</th>
                <th class="text-center" style="width: 1%">المواقع</th>
                <th class="text-center" style="width: 35%">الأجراءات</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(item,index) in items">
                <th class="valign-middle text-center p-0" scope="row">{{ item.id }}</th>
                <td class="valign-middle text-center p-0">
                  <img :src="item.image" class="pt-2" height="50" width="50">
                </td>
                <td class="valign-middle text-center p-0">
                  {{ item.name_ar.substr(0,30) }}
                </td>
                <td class="valign-middle text-center p-0">
                  {{ item.name_en.substr(0,30) }}
                </td>
                <td class="valign-middle text-center p-0">
                  {{ item.sites_count }}
                </td>
                <td class="valign-middle text-center p-0">
                  <div class="mt-3 col-12 btn-demo" style="display: inline-flex">
                    <router-link :to="'/admin/project/edit/'+item.id"
                                 class="btn btn-oblong btn-outline-primary btn-block mg-b-10 mt-2 ml-2">
                      <i class="fas fa-comment-alt"></i> عرض
                    </router-link>
                    <router-link :to="'/admin/project/'+item.id+'/site'"
                                 class="btn btn-oblong btn-outline-dark btn-block mg-b-10 mt-2 ml-2">
                      <i class="fas fa-map-marker-alt"></i> المواقع
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
    getAll() {
      axios.get('/project', {
        params: {
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
              .delete("project/" + id)
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
