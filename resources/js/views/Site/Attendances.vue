<template>
  <div class="container">
    <div class="manager-header">
      <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
        </ol>
        <h6 class="slim-pagetitle">الحضور والانصراف للموقع</h6>
      </div><!-- slim-pageheader -->
    </div><!-- manager-header -->

    <div class="manager-wrapper">
      <div class="manager-right">
        <div class="section-wrapper">
          <div class="row mb-1">
            <div class="col-4 text-right" style="font-size: 21px;">
              <label> الموظف: </label>
              <router-link :to="'/admin/site/edit/'+site_id" class="font-weight-bold">
                {{ site.name_ar.substr(0, 20) }}
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
                <th class="text-center" style="width: 30%">الاسم</th>
                <th class="text-center" style="width: 70%">البيانات</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(item,index) in items">
                <th class="valign-middle text-center p-0" scope="row">{{ item.id }}</th>

                <td class="valign-middle text-center p-0">
                  {{ item.name }}
                </td>
                <td>

                <table>
                  <tr ></tr>
                  <tr >
                    <th  style="width: 45%">الحضور</th>
                    <th  style="width: 40%">الانصراف</th>
                    <th  style="width: 40%">اليوم</th>
                  </tr>
                  <tr v-for="attendance in item.attendances">
<!--                    <td></td>-->
<!--                    <td></td>-->
                    <td>{{ attendance.checkin }}</td>
                    <td>{{ attendance.checkout }}</td>
                    <td>{{ attendance.day }}</td>
                  </tr>
                </table>

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
      site_id: 0,
      site: {id: null, name_ar: 'null'},
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
    this.site_id = this.$route.params.siteId
    this.show = true;
    this.getAll();
    this.getSite();
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
      axios.get('/attendance/site-attendances', {
        params: {
          site_id: this.site_id,
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
    getSite() {
      axios.get('/sites/' + this.site_id)
          .then(response => {
            this.site = response.data.data;
          })
          .catch(err => console.log(err));
    },
    exportAttendances() {
      if (!this.items.length) {
        swal('خطأ', "لم يتم الاستخراج لعدم وجود بيانات");
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
              .post("attendance/export-site-attendances", {
                site_id: this.site_id,
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
