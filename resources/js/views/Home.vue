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
            <div class="col-sm-6 col-lg-3">
                <div class="card card-status">
                    <div class="media">
                        <i class="icon ion-ios-videocam-outline tx-purple"></i>
                        <div class="media-body">
                            <h1>{{items.videos_count}}</h1>
                            <p>اجمالى عدد الفيديوهات</p>
                        </div><!-- media-body -->
                    </div><!-- media -->
                </div><!-- card -->
            </div><!-- col-3 -->
            <div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0">
                <div class="card card-status">
                    <div class="media">
                        <i class="icon ion-ios-pricetags-outline tx-teal"></i>
                        <div class="media-body">
                            <h1>{{items.tags_count}}</h1>
                            <p>اجمالى عدد التاجات</p>
                        </div><!-- media-body -->
                    </div><!-- media -->
                </div><!-- card -->
            </div><!-- col-3 -->
            <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
                <div class="card card-status">
                    <div class="media">
                        <i class="icon ion-ios-eye-outline tx-primary"></i>
                        <div class="media-body">
                            <h1>{{items.views_sum}}</h1>
                            <p>اجمالى المشاهدات</p>
                        </div><!-- media-body -->
                    </div><!-- media -->
                </div><!-- card -->
            </div><!-- col-3 -->
            <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
                <div class="card card-status">
                    <div class="media">
                        <i class="icon ion-ios-cloud-download-outline tx-pink"></i>
                        <div class="media-body">
                            <h1>{{items.downloads_sum}}</h1>
                            <p>اجمالى التحميلات</p>
                        </div><!-- media-body -->
                    </div><!-- media -->
                </div><!-- card -->
            </div><!-- col-3 -->
        </div><!-- row -->
        <div class="row row-xs mg-t-10">
            <div class="col-lg-6">
                <div class="card card-table">
                    <div class="card-header tx-right">
                        <h6 class="slim-card-title">الفيديوهات الاكثر مشاهده</h6>
                    </div><!-- card-header -->
                    <div class="table-responsive">
                        <table class="table mg-b-0 tx-13">
                            <thead>
                            <tr class="tx-10">
                                <th class="wd-10p pd-y-5">&nbsp;</th>
                                <th class="pd-y-5">العنوان</th>
                                <th class="pd-y-5">المشاهدات</th>
                                <th class="pd-y-5">التحميلات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="mVideo in items.most_watched">
                                <td class="pd-l-20">
                                    <img :src="mVideo.image"  style="height: 35px" class="wd-36 rounded-circle"
                                         alt="Image">
                                </td>
                                <td style="width: 40%">
                                    <router-link :to="'/admin/video/edit/'+mVideo.id"
                                                 class="tx-inverse tx-14 tx-medium d-block">{{mVideo.title}}</router-link>
                                    <span v-if="mVideo.active==1" class="tx-11 d-block">
                                        <span class="square-8 bg-success mg-l-5 rounded-circle"></span>{{mVideo.created_at}}
                                    </span>
                                    <span v-if="mVideo.active==0" class="tx-11 d-block">
                                        <span class="square-8 bg-danger mg-l-5 rounded-circle"></span>{{mVideo.created_at}}
                                    </span>
                                </td>
                                <td class="tx-12">
                                    <span class="text-primary">{{mVideo.views}}</span>
                                </td>
                                <td>
                                    <span class="text-danger">
                                    {{mVideo.downloads}}
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!-- table-responsive -->
                </div><!-- card -->
            </div><!-- col-6 -->
            <div class="col-lg-6 mg-t-10 mg-lg-t-0">
                <div class="card card-table tx-right">
                    <div class="card-header">
                        <h6 class="slim-card-title">الفيديوهات الاكثر تحميلا</h6>
                    </div><!-- card-header -->
                    <div class="table-responsive">
                        <table class="table mg-b-0 tx-13">
                            <thead>
                            <tr class="tx-10">
                                <th class="wd-10p pd-y-5">&nbsp;</th>
                                <th class="pd-y-5">العنوان</th>
                                <th class="pd-y-5 tx-right">المشاهدات</th>
                                <th class="pd-y-5">التحميلات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="dVideo in items.most_downloaded">
                                <td class="pd-l-20">
                                    <img :src="dVideo.image" class="wd-55" style="height: 35px" alt="Image">
                                </td>
                                <td style="width: 40%">
                                    <router-link :to="'/admin/video/edit/'+dVideo.id" class="tx-inverse tx-14 tx-medium d-block">
                                        {{dVideo.title}}
                                    </router-link>
                                    <span v-if="dVideo.active==1" class="tx-11 d-block">
                                        <span class="square-8 bg-success mg-l-5 rounded-circle"></span>{{dVideo.created_at}}
                                    </span>
                                    <span v-if="dVideo.active==0" class="tx-11 d-block">
                                        <span class="square-8 bg-danger mg-l-5 rounded-circle"></span>{{dVideo.created_at}}
                                    </span>
                                </td>
                                <td class="valign-middle tx-14 tx-right">
                                    <span class="text-primary">{{dVideo.views}}</span>
                                </td>
                                <td class="valign-middle"><span class="tx-danger">{{dVideo.downloads}}</span>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div><!-- table-responsive -->
                </div><!-- card -->
            </div><!-- col-6 -->
        </div><!-- row -->

    </div><!-- container -->
</template>

<script>
    export default {
        name: "Home",
        data() {
            return {
                items: {
                    tags_count: 0,
                    videos_count: 0,
                    views_sum: 0,
                    downloads_sum: 0,
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
