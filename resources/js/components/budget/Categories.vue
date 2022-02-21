<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Categories</h3>

                            <div class="card-tools">

                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addCategory">
                                    <i class="fa fa-plus-square"></i>
                                    Add New
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <!-- <th>Photo</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="category in categories.data" :key="category.id">

                                        <td>{{category.id}}</td>
                                        <td>{{category.title}}</td>
                                        <td>{{category.type}}</td>
                                        <td>{{category.description | truncate(100, '...')}}</td>
                                        <td>
                                            <a href="#" @click="editModal(category)" class="mr-2 py-2 px-2">
                                                <i class="fa fa-edit blue"></i> Edit
                                            </a>

                                            <a href="#" @click="deleteProduct(category.id)" class="mr-2 py-2 px-2">
                                                <i class="fa fa-trash red"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Create New Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form @submit.prevent="createCategory">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input v-model="form.title" type="text" name="title"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                                    <has-error :form="form" field="title"></has-error>
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    <select v-model="form.type" name="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                        <option disabled selected>Select Type</option>
                                        <option value="income">Income</option>
                                        <option value="expense">Expense</option>
                                    </select>
                                    <has-error :form="form" field="type"></has-error>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea v-model="form.description" type="text" name="description" rows="5"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                                    </textarea>
                                    <has-error :form="form" field="description"></has-error>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data () {
            return {
                categories : {},
                form: new Form({
                    id : '',
                    title: '',
                    type: '',
                    description: '',
                })
            }
        },
        methods: {
            loadCategories(){
                axios.get("api/category").then(({ data }) => (this.categories = data.data));
            },
            createCategory(){
                this.$Progress.start();

                this.form.post('api/category')
                .then((data)=>{
                    $('#addNew').modal('hide');

                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                    this.$Progress.finish();
                    this.loadCategories();

                })
                .catch(()=>{

                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    });
                })
            },
            editModal(category){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(category);
            },
            deleteProduct(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    if (result.value) {
                        this.form.delete('api/category/'+id).then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                                );
                            this.loadCategories();
                        }).catch((data)=> {
                            Swal.fire("Failed!", data.message, "warning");
                        });
                    }
                })
            },
        },
        filters: {
            truncate: function (text, length, suffix) {
                return text.substring(0, length) + suffix;
            },
        },
        mounted() {

        },
        created() {
            this.$Progress.start();

            this.loadCategories();

            this.$Progress.finish();
        }
    }
</script>
