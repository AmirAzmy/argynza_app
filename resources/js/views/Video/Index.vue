<template>
    <div class="container">
        <div class="manager-header">
            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                    <router-link to="/admin/video/create" class="btn btn-primary">اضف فيديو جديد</router-link>
                </ol>
                <h6 class="slim-pagetitle">الفيديوهات</h6>
            </div><!-- slim-pageheader -->
        </div><!-- manager-header -->

        <div class="manager-wrapper">
            <div class="manager-right">
                <div class="row row-sm">
                    <div class="col-sm-6 col-lg-3 mt-3" v-for="(item,index) in items">
                        <div class="card-contact">
                            <div class="tx-center">
                                <router-link :to="{path:'/admin/video/edit/' +item.id}">
                                    <img :src="item.image" width="110" height="110" class="card-img">
                                </router-link>
                                <h5 class="mg-t-10 mg-b-5">
                                    <router-link :to="{path:'/admin/video/edit/' +item.id}" class="contact-name">
                                        {{item.title.substring(0,16)}}
                                    </router-link>
                                </h5>
                                <p>{{item.created_at}}</p>
                                <div class="custom-control custom-switch mb-3">
                                    <input @change="changeStatus(item.id,item.active,index)" type="checkbox"
                                           v-model="item.active"
                                           class="custom-control-input" :id="'customSwitches'+item.id">
                                    <label class="custom-control-label" :for="'customSwitches'+item.id">التفعيل</label>
                                </div>
                            </div><!-- tx-center -->
                            <p class="contact-item">
                                <span>المشاهدات:</span>
                                <span>{{item.views}}</span>
                            </p><!-- contact-item -->
                            <p class="contact-item">
                                <span>عدد التنزيلات: </span>
                                <span>{{item.downloads}}</span>
                            </p><!-- contact-item -->
                            <p class="contact-item">
                                <span>عدد التعليقات:</span>
                                <span>{{item.comments_count}}</span>
                            </p>
                            <!--                            <p class="contact-item">-->
                            <div class="mt-3 btn-demo" style="display: flex;justify-content: center;">

                                <button class="btn btn-oblong btn-outline-danger btn-block mg-b-10 col-6 mt-2 ml-2"
                                        type="button"
                                        @click="deleteItem(item.id, index)"
                                >
                                    <i class="fa fa-trash"></i> حذف
                                </button>
                                <router-link :to="'/admin/video/'+item.id+'/comments'"
                                             class="btn btn-oblong btn-outline-dark btn-block mg-b-10 col-6">
                                    <i class="fas fa-comment-alt"></i> كومنتات
                                </router-link>
                            </div>
                            <!--                            </p>&lt;!&ndash; contact-item &ndash;&gt;-->
                        </div><!-- card -->
                    </div><!-- col -->
                </div><!-- row -->

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
            if (this.$route.query.keyword){
                this.keyword=this.$route.query.keyword
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
            },
            $route(to) {
                this.keyword = (to.query.keyword) ? to.query.keyword : this.keyword = null;
                this.getAll()
            }
        },
        methods: {
            getAll() {
                axios.get('/video', {
                    params: {
                        active: 2,
                        keyword: this.keyword,
                        page: this.currentPage, return_all: 0
                    }
                }).then(response => {
                    this.items = response.data.data.data;
                    this.currentPage = response.data.data.current_page;
                    this.perPage = response.data.data.per_page;
                    this.rows = response.data.data.total
                })
                    .catch(err => console.log(err));
            },
            changeStatus(id, value, index) {
                swal({
                    title: "هل انت متاكد ؟",
                    text: "سيتم تغير  حاله التفعيل للمستخدم , تاكيد؟",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios
                            .post("video/" + id, {
                                '_method': 'PUT',
                                'active': value,
                            })
                            .then(res => {
                                this.items[index].active = (value) ? 1 : 0;
                                swal("تم التعديل بنجاح", {
                                    icon: "success"
                                });
                            })
                            .catch(err => {
                                console.log(err.response.data);
                                this.errorMessages(err.response.data);
                            });
                    } else {
                        swal("لم يتم التغيير يرجى التاكد من البيانات");
                    }
                });
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
                            .delete("video/" + id)
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
