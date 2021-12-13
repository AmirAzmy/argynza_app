<template>
    <div class="container">
        <div class="slim-pageheader">
            <ol class="breadcrumb slim-breadcrumb">
                <li class="breadcrumb-item">
                    <router-link to="/admin/video">الفيديوهات</router-link>
                </li>
                <li class="breadcrumb-item">
                    <router-link to="/admin">الرئيسية</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">اضافة فيديو</li>
            </ol>
            <h6 class="slim-pagetitle">الفيديوهات</h6>
        </div><!-- slim-pageheader -->

        <div class="section-wrapper tx-right">
            <label class="section-title">اضافة فيديو جديد</label>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="form-layout">
                <div class="row mg-b-25">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label">العنوان: <span class="tx-danger">*</span></label>
                            <input class="form-control" v-model="item.title" type="text"
                                   placeholder="اضف عنوان للفيديو">
                        </div>
                    </div><!-- col-4 -->

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">المشاهدات </label>
                            <input class="form-control" v-model="item.views" type="number"
                                   placeholder="عدد  المشاهدات للفيديو">
                        </div>
                    </div><!-- col-4 -->

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label"
                                   style="display: block; margin-bottom:20px ">التفعيل </label>
                            <label class="rdiobox ml-5" style="display:  inline">
                                <input v-model="item.active" type="radio" value="1">
                                <span>فعال</span>
                            </label>
                            <label class="rdiobox" style="display: inline">
                                <input v-model="item.active" type="radio" value="0">
                                <span>غير فعال</span>
                            </label>
                        </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-12">
                        <div class="form-group mg-b-10-force">
                            <label class="form-control-label">التاج: <span class="tx-danger">*</span></label>
                            <multiselect v-model="selectedTags" label="name" :options="tags"
                                         track-by="id"
                                         :close-on-select="false"
                                         placeholder="اختر تاج"
                                         :multiple="true"></multiselect>
                        </div>
                    </div><!-- col-8 -->
                    <div class="col-lg-12">
                        <div class="form-group mg-b-10-force">
                            <label class="form-control-label">الوصف: <span class="tx-danger">*</span></label>
                            <textarea rows="5" v-model="item.description" class="form-control"
                                      placeholder="اضف وصف للفيديو">
                            </textarea>
                        </div>
                    </div><!-- col-8 -->
                    <div class="col-lg-6">
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
                                        قم بتحميل صورة الفيديو هنا
                                    </div>
                                </div>
                            </div>
                            <input class="form-control" ref="image" @change="uploadFile('image')" type="file"
                                   style="display: none;">
                        </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label"> الفيديو: <span class="tx-danger">*</span></label>
                            <div>
                                <div class="container">
                                    <div @click="$refs.video.click()" v-if="!video"
                                         class="upload-cta text-center text-muted">
                                        <i class="fas fa-video" style="font-size: 50px"></i>
                                        <br><br>
                                        قم بتحميل الفيديو هنا
                                    </div>
                                </div>
                            </div>
                            <video @click="$refs.video.click()" v-if="video" class="uploaded-image" :src="video"
                                   controls>
                            </video>
                            <input class="form-control" @change="uploadFile('video')" ref="video" type="file"
                                   accept="video/*" style="display: none;">
                        </div>
                    </div><!-- col-4 -->
                </div><!-- row -->

                <div class="form-layout-footer">
                    <button class="btn btn-dark bd-0" type="button" @click="$router.go(-1)">رجوع</button>
                    <button class="btn btn-primary bd-0" type="button" @click="createItem()">حفظ</button>
                </div><!-- form-layout-footer -->
            </div><!-- form-layout -->
        </div><!-- section-wrapper -->

    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    export default {
        name: "Create",
        components: {
            Multiselect
        },
        data() {
            return {
                image: null,
                video: null,
                tags: [],
                selectedTags: [],
                item: {
                    title: null,
                    tags: [],
                    description: null,
                    views: 0,
                    downloads: 0,
                    active: 1,
                    video: null,
                    image: null,
                }
            }
        },
        created() {
            this.getTags();
        },
        methods: {
            createItem() {
                let tags = [];
                this.selectedTags.forEach(tag => {
                    tags.push(tag.id);
                });
                this.item.tags = JSON.stringify(tags);
                let formData = new FormData();
                for (const property in this.item) {
                    if (this.item[property] != null) {
                        formData.append(property, this.item[property])
                    }
                }
                axios.post("/video", formData).then(rsp => {
                    this.$router.push('/admin/video');
                    swal("تم بنجاح👏", "تم اضافة  جديد", "success");
                    console.log(rsp.data);
                }).catch(err => {
                    this.errorMessages(err.response.data);
                    console.log(err.data)
                })
            },
            getTags() {
                axios.get("/tag", {
                    params: {
                        return_all: 1
                    }
                }).then(rsp => {
                    this.tags = rsp.data.data;
                }).catch(err => {
                    this.errorMessages(err.response.data);
                    console.log(err.data)
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

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
