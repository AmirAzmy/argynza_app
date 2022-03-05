<template>
  <div class="container">
    <div class="manager-header">
      <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
        </ol>
        <h6 class="slim-pagetitle">الحضور والانصراف</h6>
      </div><!-- slim-pageheader -->
    </div><!-- manager-header -->

    <div class="manager-wrapper">
      <div class="manager-right">
        <div class="section-wrapper">
          <div class="row mb-1">
            <div class="col-4 text-right" style="font-size: 21px;">
              <label> الموظف: </label>
              <router-link :to="'/admin/user/edit/'+user_id" class="font-weight-bold">
                {{ user.name.substr(0, 20) }}
              </router-link>
            </div>
            <div class="col-md-4">
              <input type="month" class="form-control"
                     style="border-radius: 50px;padding:2px;height: 41.9px;"
                     v-model="date"
                     placeholder="ابحث عن المواقع..." aria-controls="datatable1">
            </div>
            <div class="col-md-4">
              <button
                  @click="getAll"
                  class="btn btn-outline-primary">
                بحث
              </button>
              <button
                  @click="exportAttendances"
                  class="btn btn-outline-dark">
                استخراج تقرير
              </button>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table mg-b-0">
              <thead>
              <tr class="text-center">
                <th class="text-center">#</th>
                <th class="text-center" style="width: 30%">الحضور</th>
                <th class="text-center" style="width: 30%">الانصراف</th>
                <th class="text-center" style="width: 30%">اليوم</th>
                <!--                <th class="text-center" style="width: 35%">الأجراءات</th>-->
              </tr>
              </thead>
              <tbody>
              <tr v-for="(item,index) in items">
                <th class="valign-middle text-center p-0" scope="row">{{ item.id }}</th>
                <td class="valign-middle text-center p-0">
                  {{ item.checkin }}
                </td>
                <td class="valign-middle text-center p-0">
                  {{ item.checkout }}
                </td>
                <td class="valign-middle text-center p-0">
                  {{ item.day }}
                </td>
              </tr>
              </tbody>
            </table>
          </div><!-- table-responsive -->
          <div v-if="!items.length"
               class="alert alert-warning text-center" role="alert">
            لا يوجد حضور لهذا الشهر
          </div>
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
      date: new Date().toISOString().slice(0, 7),
      user_id: 0,
      user: {id: null, name: 'null'},
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
    this.user_id = this.$route.params.id
    this.show = true;
    this.getAll();
    this.getUser();
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
      axios.get('/attendance/user-attendances', {
        params: {
          user_id: this.user_id,
          date: this.date,
          page: this.currentPage
        }
      }).then(response => {
        this.items = response.data.data.data;
        this.currentPage = response.data.data.current_page;
        this.perPage = response.data.data.per_page;
        this.rows = response.data.data.total
      })
          .catch(err => console.log(err));
    },
    getUser() {
      axios.get('/users/profile?user_id' + this.user_id)
          .then(response => {
            this.user = response.data.data;

          })
          .catch(err => console.log(err));
    },
    exportAttendances() {
      if (!this.items.length) {
        swal('خطأ',"لم يتم الاستخراج لعدم وجود بيانات");
        return;
      }
      swal({
        title: "هل انت متاكد ؟",
        text: "من استخرج تقرير الحضور والانصراف, تاكيد؟",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          axios
              .post("attendance/export", {
                user_id: this.user_id,
                date: this.date
              })
              .then(res => {
                window.open(res.data.data.file, '_blank');
              })
              .catch(error => console.log(error));
        } else {
          swal("لم يتم الاستخراج يرجى التاكد من البيانات");
        }
      });
    }
  }
}
</script>

<style scoped>

</style>
