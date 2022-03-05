<template>
  <div class="container">
    <div class="slim-pageheader">
      <ol class="breadcrumb slim-breadcrumb">
        <li class="breadcrumb-item">
          <!--                    <router-link to="/">الرئيسية</router-link>-->
        </li>
      </ol>
      <h6 class="slim-pagetitle">الرئيسية</h6>
    </div><!-- slim-pageheader -->

    <div class="row row-xs">
      <div class="col-sm-6 col-lg-6">
        <div class="card card-status">
          <div class="media">
            <i class="icon ion-ios-videocam-outline tx-purple"></i>
            <div class="media-body">
              <h1>{{ items.employees_count }}</h1>
              <p>اجمالى عدد الموظفين</p>
            </div><!-- media-body -->
          </div><!-- media -->
        </div><!-- card -->
      </div><!-- col-3 -->
      <div class="col-sm-6 col-lg-6 mg-t-10 mg-sm-t-0">
        <div class="card card-status">
          <div class="media">
            <i class="icon ion-ios-pricetags-outline tx-teal"></i>
            <div class="media-body">
              <h1>{{ items.projects_count }}</h1>
              <p>اجمالى عدد المشاريع</p>
            </div><!-- media-body -->
          </div><!-- media -->
        </div><!-- card -->
      </div><!-- col-3 -->
      <div class="col-sm-6 col-lg-6 mg-t-10 mg-lg-t-0">
        <div class="card card-status">
          <div class="media">
            <i class="icon ion-ios-eye-outline tx-primary"></i>
            <div class="media-body">
              <h1>{{ items.requests_count }}</h1>
              <p>اجمالى الطلبات</p>
            </div><!-- media-body -->
          </div><!-- media -->
        </div><!-- card -->
      </div><!-- col-3 -->
      <div class="col-sm-6 col-lg-6 mg-t-10 mg-lg-t-0">
        <div class="card card-status">
          <div class="media">
            <i class="icon ion-ios-cloud-download-outline tx-pink"></i>
            <div class="media-body">
              <h1>{{ items.sites_count }}</h1>
              <p>اجمالى المواقع</p>
            </div><!-- media-body -->
          </div><!-- media -->
        </div><!-- card -->
      </div><!-- col-3 -->
    </div><!-- row -->

  </div><!-- container -->
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      items: {
        employees_count: 0,
        projects_count: 0,
        requests_count: 0,
        sites_count: 0,
        most_watched: [],
        most_downloaded: []
      }
    }
  },
  created() {
    this.dashboardHome()
  },
  methods: {
    dashboardHome() {
      axios.get('/home/dashboard')
          .then(response => {
            this.items = response.data.data;
          })
          .catch(err => {
            this.errorMessages(err.response.data);
          });
    }
  }
}
</script>

<style scoped>

</style>
