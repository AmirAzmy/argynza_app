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
        <li class="breadcrumb-item active" aria-current="page">تعديل موقع</li>
      </ol>
      <h6 class="slim-pagetitle">الموقع</h6>
    </div><!-- slim-pageheader -->

    <div class="section-wrapper tx-right">
      <label class="section-title">تعديل موقع جديد</label>
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
              <label class="form-control-label">مساحه المسموح بالتحرك فيها (كم): <span class="tx-danger">*</span></label>
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
                <input class="form-control" type="text" v-model="item.lat">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group mg-b-10-force">
                <label class="form-control-label">خط الطول</label>
                <input class="form-control" type="text" v-model="item.lng">
              </div>
            </div>
          </div>
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
        _method: "PUT"
      }
    }
  },
  created() {
    this.item.id = this.$route.params.id;
    this.getItem();
  },
  methods: {
    getItem() {
      axios.get('/sites/' + this.item.id)
          .then(response => {
            this.item = response.data.data;
            this.zoom = 10;
            this.markers.push({position: {lat: this.item.lat, lng: this.item.lng}});
            this.center = {lat: this.item.lat, lng: this.item.lng};
          })
          .catch(err => {
            this.errorMessages(err.response.data);
          });
    },
    updateItem() {
      this.item._method= "PUT"
      axios.post("/sites/" + this.item.id, this.item).then(rsp => {
        swal("تم بنجاح👏", "تم التعديل جديد", "success");
      }).catch(err => {
        this.errorMessages(err.response.data);
        console.log(err.response)
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
  height: 290px;
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
