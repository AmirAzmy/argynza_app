<template>
  <div class="container">
    <div class="slim-pageheader">
      <ol class="breadcrumb slim-breadcrumb">
        <li class="breadcrumb-item">
          <router-link :to="'/admin/project/'+item.project_id+'/site'">الموقع</router-link>
        </li>
        <li class="breadcrumb-item">
          <router-link to="/admin">الرئيسية</router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">اضافة موقع</li>
      </ol>
      <h6 class="slim-pagetitle">الموقع</h6>
    </div><!-- slim-pageheader -->

    <div class="section-wrapper tx-right">
      <label class="section-title">اضافة موقع جديد</label>
      <p class="mg-b-20 mg-sm-b-40"></p>

      <div class="form-layout">
        <div class="row mg-b-25">
          <div class="col-lg-6">
            <div class="form-group">
              <label class="form-control-label">الاسم باللغه العربيه: <span class="tx-danger">*</span></label>
              <input class="form-control" v-model="item.name_ar" type="text"
                     placeholder="اضف اسم للموقع">
            </div>
          </div><!-- col-4 -->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="form-control-label">الاسم باللغه الانجليزيه: <span class="tx-danger">*</span></label>
              <input class="form-control" v-model="item.name_en" type="text"
                     placeholder="اضف اسم للموقع">
            </div>
          </div><!-- col-4 -->
          <div class="col-lg-6">
            <div class="form-group">
              <label class="form-control-label">مساحه المسموح بالتحرك فيها (متر): <span class="tx-danger">*</span></label>
              <input class="form-control" v-model="item.redius" type="number"
                     placeholder="اضف اسم للموقع">
            </div>
          </div><!-- col-4 -->
          <div class="col-md-12 row">
            <label style="width: 100%">
              <span class="text-dark font-weight-bold"> الموقع
                <span class="text-danger">*</span>
              </span>
              <GmapAutocomplete
                  class="form-control col-6"
                  style="display: inline"
                  placeholder="ادخل الموقع"
                  @place_changed="setPlace">
              </GmapAutocomplete>
              <button class="btn btn-info btn-sm col-2" @click="usePlace">اضف العلامه</button>
            </label>
            <br/>

            <GmapMap style="width: 100%; height: 450px;" :zoom="zoom" :center="center">
              <GmapMarker v-for="(marker, index) in markers"
                          :key="index"
                          :position="marker.position"
              />
              <GmapMarker
                  v-if="this.place"
                  label="★"
                  :position="{lat: this.place.geometry.location.lat(),lng: this.place.geometry.location.lng()}"
              />
            </GmapMap>
            <div class="col-lg-6">
              <div class="form-group mg-b-10-force">
                <label class="form-control-label">خط العرض</label>
                <input disabled class="form-control" type="text" v-model="item.lat">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group mg-b-10-force">
                <label class="form-control-label">خط الطول</label>
                <input disabled class="form-control" type="text" v-model="item.lng">
              </div>
            </div>
          </div>
        </div><!-- row -->

        <div class="form-layout-footer text-left">
          <button class="btn btn-primary bd-0" type="button" @click="createItem()">حفظ</button>
          <button class="btn btn-dark bd-0" type="button" @click="$router.go(-1)">رجوع</button>
        </div><!-- form-layout-footer -->
      </div><!-- form-layout -->
    </div><!-- section-wrapper -->

  </div>

</template>

<script>
export default {
  name: "Create",
  components: {},
  data() {
    return {
      place: null,
      markers: [],
      center: {lat: 23.88, lng: 45.88},
      zoom: 5,
      item: {
        name_ar: null,
        name_en: null,
        project_id: null,
        redius: 0,
        lat: 0,
        lng: 0,
      }
    }
  },
  created() {
    this.item.project_id = this.$route.params.project_id
  },
  methods: {
    createItem() {
      axios.post("/sites", this.item).then(rsp => {
        this.$router.push('/admin/project/' + this.item.project_id + '/site');
        swal("تم بنجاح👏", "تم اضافة  جديد", "success");
      }).catch(err => {
        this.errorMessages(err.response.data);
        console.log(err.data)
      })
    },
    setPlace(place) {
      this.place = place
      this.center = {
        lat: this.place.geometry.location.lat(),
        lng: this.place.geometry.location.lng()
      }
      this.item.lat = this.place.geometry.location.lat()
      this.item.lng = this.place.geometry.location.lng()
      this.zoom = 10;
    },
    usePlace(place) {
      if (this.place) {
        this.markers.push({
          position: {
            lat: this.place.geometry.location.lat(),
            lng: this.place.geometry.location.lng(),
          }
        })
        this.place = null;
      }
    },
  }
}
</script>

<style scoped>

</style>
