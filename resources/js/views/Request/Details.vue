<template>

  <div class="container">
    <div class="manager-header">
      <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
        </ol>
        <h6 class="slim-pagetitle">تفاصيل الطلب</h6>
      </div><!-- slim-pageheader -->
    </div><!-- manager-header -->

    <div class="manager-wrapper">
      <div class="manager-right">
        <div class="card card-invoice">
          <div class="card-body">
            <div class="invoice-header">
              <h1 class="invoice-title">تفاصيل الطلب</h1>
              <div class="billed-from">
                <h6>{{ item.employee.name }}</h6>
                <p>رقم الهاتف : {{ item.employee.phone }}<br>
                  نوع الموظف: {{ item.employee.user_type }}<br>
                  المسمى الوظيفى : {{ item.employee.job_title }}
                </p>
              </div><!-- billed-from -->
            </div><!-- invoice-header -->

            <div class="row mg-t-20">
              <div class="col-md">
                <label class="section-label-sm tx-gray-500">بيانات الطلب</label>
                <div class="billed-to">
                  <h6 class="tx-gray-800">{{ item.created_at }}</h6>
                  <p> حاله الطلب :
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
                    </i> <br>
                    <i class="font-weight-bold">
                      نوع الطلب :{{ item.type_name }}<br>
                    </i>
                    الاسباب :{{ item.notes }}</p>
                </div>
              </div><!-- col -->
              <div class="col-md">
                <label class="section-label-sm tx-gray-500">بيانات الطلب </label>
                <p class="invoice-info-row"
                   v-if="item.type=='late_and_leave'||item.type=='late_and_leave'||item.type=='reduction'">
                  <span>التاريخ</span>
                  <span>{{ details.date }}</span>
                </p>
                <p class="invoice-info-row"
                   v-if="item.type=='late_and_leave'||item.type=='late_and_leave'||item.type=='reduction'||item.type=='loan'"
                >
                  <span>المبلغ</span>
                  <span>{{ details.amount }}</span>
                </p>
                <p class="invoice-info-row"
                   v-if="item.type=='errand'||item.type=='late_and_leave'"
                >
                  <span>من </span>
                  <span>{{ details.from }}</span>
                </p>
                <p class="invoice-info-row"
                   v-if="item.type=='errand'||item.type=='late_and_leave'"
                >
                  <span>الى </span>
                  <span>{{ details.to }}</span>
                </p>
                <p class="invoice-info-row" v-if="item.type=='reduction'">
                  <span>الموظف المخصوم منه:</span>
                  <span>{{ item.employee.name }}</span>
                </p>
                <p class="invoice-info-row" v-if="item.type=='vacation'">
                  <span>تاريخ البدء:</span>
                  <span>{{ details.start }}</span>
                </p>
                <p class="invoice-info-row" v-if="item.type=='vacation'">
                  <span>تاريخ النهايه:</span>
                  <span>{{ details.end }}</span>
                </p>
                <div class="invoice-info-row" v-if="item.status=='rejected'">
                  <p class="font-weight-bold" style="width: 40%;">سبب الرفض :</p>
                  <p>{{ item.rejection_reason }}</p>
                </div>
              </div><!-- col -->
            </div><!-- row -->
            <hr class="mg-b-30">
            <div class="row" v-if="item.status!='rejected'">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">حاله الطلب : <span class="tx-danger">*</span></label>
                  <select class="form-control" v-model="status">
                    <option value="pending">قيد الانتظار</option>
                    <option value="approved">موافق</option>
                    <option value="rejected">مرفوض</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6" v-if="status=='rejected'">
                <div class="form-group">
                  <label class="form-control-label">سبب الرفض: <span class="tx-danger">*</span></label>
                  <textarea class="form-control" v-model="rejection_reason" rows="5"
                            placeholder="اكتب سبب الرفض">
                  </textarea>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-10"></div>
              <div class="col-lg-2 ">
                <label class="form-control-label"></label>
                <button type="button"
                        @click="changeStatus"
                        class="btn btn-primary float-left">حفظ
                </button>
              </div>
            </div>

          </div><!-- card-body -->
        </div><!-- card -->


      </div><!-- manager-right -->
    </div><!-- manager-wrapper -->

  </div><!-- container -->

</template>

<script>
export default {
  name: "Details",
  data() {
    return {
      rejection_reason: null,
      status: null,
      details: {
        date: null,
        start: null,
        end: null,
        from: null,
        to: null,
        employee: {
          name: null
        },
      },
      item: {
        id: null,
        notes: null,
        type: null,
        status: null,
        status_name: null,
        employee_id: null,
        employee: {
          name: null
        },
        lateAndLeave: null,
        loan: null,
        errand: null,
        vacation: null,
        reduction: null
      }
    }
  },
  created() {
    this.item.id = this.$route.params.id;
    this.getItem();
  },
  methods: {
    getItem() {
      axios.get('/request/' + this.item.id)
          .then(response => {
            this.item = response.data.data;
            this.details = this.item[this.item.type]
            this.status = this.item.status
          })
          .catch(err => {
            this.errorMessages(err.response.data);
          });
    },
    changeStatus() {
      swal({
        title: "هل انت متاكد ؟",
        text: "من تغيير حاله الطلب, تاكيد؟",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          axios.post("request/" + this.item.id, {
            _method: "PUT",
            status: this.status,
            rejection_reason: this.rejection_reason,
          })
              .then(res => {
                this.getItem();
                swal("تم التغيير بنجاح", {icon: "success"});
              })
              .catch(error => {
                this.errorMessages(error.response.data);
                console.log(error)
              });
        } else {
          swal("لم يتم التتغير يرجى التاكد من البيانات");
        }
      });
    }
  }
}
</script>

<style scoped>

</style>
