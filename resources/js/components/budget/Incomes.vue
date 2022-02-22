<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">

                    <div class="card" v-if="$gate.isAdmin()">
                        <div class="card-header">
                            <h3 class="card-title">Incomes List</h3>

                            <div class="card-tools">

                                <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                                    <i class="fa fa-plus-square"></i> Add New
                                </button>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Source</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Updated</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="Object.keys(incomes).length">
                                        <tr v-for="(income, index) in incomes.incomes.data" :key="income.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{income.source}}</td>
                                            <td>{{income.amount}}</td>
                                            <td>{{income.date}}</td>
                                            <td>{{income.category.title}}</td>
                                            <td>{{income.description}}</td>
                                            <td>{{income.updated_at}}</td>
                                            <td>
                                                <a href="#" @click="editModal(income)">
                                                    <i class="fa fa-edit blue"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <pagination :data="incomes" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                </div>
            </div>


            <div v-if="!$gate.isAdmin()">
                <not-found></not-found>
            </div>

            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" v-show="!editmode">Create New Category</h5>
                            <h5 class="modal-title" v-show="editmode">Update Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form @submit.prevent="editmode ? updateCategory() : createIncome()">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Source</label>
                                    <input v-model="form.source" type="text" name="source"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('source') }">
                                    <has-error :form="form" field="source"></has-error>
                                </div>
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input v-model="form.amount" type="text" name="amount"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('amount') }">
                                    <has-error :form="form" field="amount"></has-error>
                                </div>
                                <div class="form-group">
                                    <label>Date</label>
                                    <input v-model="form.date" type="date" name="date"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('date') }">
                                    <has-error :form="form" field="date"></has-error>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select v-model="form.category_id" type="text" name="category_id"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('category_id') }">
                                        <option value="" selected disabled>Select Category</option>
                                        <option v-for="category in incomes.categories" :value="category.id" :key="category.id">{{ category.title }}</option>
                                    </select>
                                    <has-error :form="form" field="category_id"></has-error>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea v-model="form.description" type="text" name="description" rows="5"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                    <has-error :form="form" field="description"></has-error>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                                <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
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
                editmode: false,
                incomes : {},
                form: new Form({
                    source: '',
                    amount: '',
                    date: '',
                    category_id : '',
                    description: '',
                })
            }
        },
        methods: {

            getResults(page = 1) {

                this.$Progress.start();

                axios.get('/api/income?page=' + page).then(({ data }) => (this.incomes = data.data));

                this.$Progress.finish();
            },
            updateCategory(){
                this.$Progress.start();
                this.form.put('/api/income/'+this.form.id)
                .then((response) => {
                    $('#addNew').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    });
                    this.$Progress.finish();

                    this.loadincomes();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(income){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(income);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            loadincomes(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/income").then(({ data }) => (this.incomes = data.data));
                }
            },
            createIncome(){
                this.form.post('/api/income')
                .then((response)=>{
                    $('#addNew').modal('hide');

                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    });

                    this.$Progress.finish();
                    this.loadincomes();
                })
                .catch(()=>{
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    });
                })
            }
        },
        mounted() {
            // console.log('Component mounted.')
        },
        created() {

            this.$Progress.start();
            this.loadincomes();
            this.$Progress.finish();
        }
    }
</script>
