<template>
    <div class="container">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item">
                    <router-link to="/admin/tag">التاج</router-link>
                </li>
                <li class="breadcrumb-item">
                    <router-link to="/admin">الرئيسية</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">اضافة تاج</li>
            </ol>
            <h6 class="slim-pagetitle">التاج</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper tx-right">
            <label class="section-title">اضافة تاج جديد</label>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="form-layout">
                <div class="row mg-b-25">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label">الاسم: <span class="tx-danger">*</span></label>
                            <input class="form-control" v-model="item.name" type="text"
                                   placeholder="اضف اسم للتاج">
                        </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label">الصوره: <span class="tx-danger">*</span></label>
                            <div>
                                <div class="container">
                                    <img @click="$refs.image.click()" :src="image" v-if="image" class="uploaded-image">
                                    <div @click="$refs.image.click()" v-if="!image"
                                         class="upload-cta text-center text-muted">
                                        <i class="far fa-file-image" style="font-size: 50px"></i>
                                        <br>
                                        <br>
                                        قم بتحميل صورة للتاج هنا
                                    </div>
                                </div>
                            </div>
                            <input class="form-control" ref="image" @change="uploadFile('image')" type="file"
                                   style="display: none;">
                        </div>
                    </div><!-- col-4 -->
                </div><!-- row -->

                <div class="form-layout-footer">
                    <button class="btn btn-dark bd-0" type="button" @click="$router.go(-1)">رجوع</button>
                    <button class="btn btn-primary bd-0" type="button" @click="updateItem()">حفظ</button>
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
                image: null,
                item: {
                    name: null,
                    image: null,
                }
            }
        },
        created() {
            this.item.id = this.$route.params.id;
            this.getItem();
        },
        methods: {
            getItem() {
                axios.get('/tag/' + this.item.id)
                    .then(response => {
                        this.item = response.data.data;
                        this.image = this.item.image;
                    })
                    .catch(err => {
                        this.errorMessages(err.response.data);
                    });
            },
            updateItem() {
                let item = this.item;

                if (!(item.image instanceof File)) {
                    delete item.image;
                }
                let formData = new FormData();
                for (const property in item) {
                    if (item[property] != null) {
                        formData.append(property, item[property]);
                    }
                }
                formData.append('_method', "PUT");
                axios.post("/tag/" + this.item.id, formData).then(rsp => {
                    swal("تم بنجاح👏", "تم التعديل جديد", "success");
                }).catch(err => {
                    this.errorMessages(err.response.data);
                    console.log(err.response)
                })
            },
            uploadFile(ref) {
                this.item[ref] = this.$refs[ref].files[0];
                // const file = e.target.files[0];
                // this.image_is_changed = true;
                this[ref] = URL.createObjectURL(this.item[ref]);
            }
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
