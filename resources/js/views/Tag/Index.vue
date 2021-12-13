<template>
    <div class="container">
        <div class="manager-header">
            <div class="slim-pageheader">
                <ol class="breadcrumb slim-breadcrumb">
                </ol>
                <h6 class="slim-pagetitle">التاج</h6>
            </div><!-- slim-pageheader -->
        </div><!-- manager-header -->

        <div class="manager-wrapper">
            <div class="manager-right">
                <div class="section-wrapper">
                    <router-link to="/admin/tag/create" class="btn btn-primary mb-3">اضف جديد</router-link>

                    <div class="table-responsive">
                        <table class="table mg-b-0">
                            <thead>
                            <tr class="text-center">
                                <th class="text-center">#</th>
                                <th class="text-center" style="width: 30%">الصوره</th>
                                <th class="text-center" style="width: 30%">الاسم</th>
                                <th class="text-center" style="width: 35%">الأجراءات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item,index) in items">
                                <th class="text-center" scope="row">{{item.id}}</th>
                                <td class="text-center">
                                    <img :src="item.image" class="pt-2" height="50" width="50">
                                </td>
                                <td class="text-center p-5">
                                  {{item.name}}
                                </td>
                                <td class="text-center">
                                    <div class="mt-3 col-12 btn-demo" style="display: inline-flex">
                                        <router-link :to="'/admin/tag/edit/'+item.id"
                                                     class="btn btn-oblong btn-outline-primary btn-block mg-b-10 col-6 mt-2 ml-2">
                                            <i class="fas fa-comment-alt"></i> عرض
                                        </router-link>
                                        <button
                                            class="btn btn-oblong btn-outline-danger btn-block mg-b-10 col-6 mt-2 ml-2"
                                            type="button"
                                            @click="deleteItem(item.id,index)"
                                        >
                                            <i class="fa fa-trash"></i> حذف
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!-- table-responsive -->
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
            }
        },
        methods: {
            getAll() {
                axios.get('/tag', {
                    params: {
                        active: 2,
                        keyword: this.keyword,
                        page: this.currentPage, is_paginate: 1
                    }
                }).then(response => {
                    this.items = response.data.data.data;
                    this.currentPage = response.data.data.current_page;
                    this.perPage = response.data.data.per_page;
                    this.rows = response.data.data.total
                })
                    .catch(err => console.log(err));
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
                            .delete("tag/" + id)
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
