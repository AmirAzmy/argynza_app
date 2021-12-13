<template>
    <div class="slim-mainpanel">
        <div class="container">
            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/admin">الرئيسية</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link to="/admin/video">فيديوهات</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">الكومنتات</li>
                </ol>
                <h6 class="slim-pagetitle">الكومنتات</h6>
            </div><!-- slim-pageheader -->

            <div class="section-wrapper">

                <div class="card-contact col-12" style="display: inline-flex">
                    <label class="section-title col-6">
                        <img :src="item.image" style="width:100%; height:195px">

                    </label>
                    <label class="section-title col-6">
                        <p>
                            <router-link :to="'/admin/video/edit/'+item.id">
                                {{item.title}}
                            </router-link>
                        </p>
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
                    </label>
                </div>
                <div class="table-responsive">
                    <table class="table mg-b-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center" style="width: 15%">الاسم</th>
                            <th class="text-center" style="width: 15%">رقم التليفون</th>
                            <th class="text-center" style="width: 40%">الكومنت</th>
                            <th class="text-center" style="width: 15%">الوقت</th>
                            <th class="text-center">حذف</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="item.comments.length==0">
                            <td colspan="6" class="text-center" style="background: #f6993f" >لا يوجد كومنتات</td>
                        </tr>
                        <tr v-for="(comment,index) in  item.comments">
                            <th>{{comment.id}}</th>
                            <td class="text-center">{{comment.name}}</td>
                            <td class="text-center">{{comment.phone}}</td>
                            <td class="text-center">{{comment.comment}}</td>
                            <td class="text-center">{{comment.created_at}}</td>
                            <td class="text-center">
                                <button type="button" @click="deleteItem(comment.id, index)"
                                        class="btn btn-outline-danger btn-icon rounded-circle">
                                    <div><i class="fa fa-trash"></i></div>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div><!-- table-responsive -->
            </div><!-- section-wrapper -->


        </div><!-- container -->
    </div><!-- slim-mainpanel -->

</template>

<script>
    export default {
        name: "Comments",
        data() {
            return {
                item: {
                    title: null,
                    comments: [],
                    description: null,
                    views: 0,
                    downloads: 0,
                    active: 1,
                    tags: [],
                    video: null,
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
                axios.get('/video/' + this.item.id)
                    .then(response => {
                        this.item = response.data.data;
                        this.image = this.item.image;
                        this.video = this.item.video;
                    })
                    .catch(err => {
                        this.errorMessages(err.response.data);
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
                            .delete("video/" + this.item.id + '/comment/' + id)
                            .then(res => {
                                this.item.comments.splice(index, 1);
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
