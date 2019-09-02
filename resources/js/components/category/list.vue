<template>
    <div class="container">
        <div class="box">

            <div class="alert alert-danger" role="alert" v-if="errors.length">
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>

            <div class="alert alert-success" role="alert" v-if="messages.length">
                <ul>
                    <li>{{ messages }}</li>
                </ul>
                </p>
            </div>

            <div class="card" v-if="activedView == 0">
                <h5 class="card-header">
                    <a href="#" @click.prevent="createCategory()" v-if="activedView == 0">
                        Cadastrar nova categoria
                    </a>
                </h5>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col-6">Nome</th>
                            <th scope="col-5">Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="category in categories">
                            <td class="col-6"> {{category.name}}</td>
                            <td class="col-5">
                                <button class="btn btn-success" @click.prevent="loadCategory(category)"> Editar</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card" v-if="activedView == 1">
                <h5 class="card-header">
                    <button class="btn btn-primary" @click.prevent="showView(0)" v-if="activedView == 1">
                        Voltar
                    </button>
                </h5>
                <div class="card-body">
                    <h5 class="card-title">Categoria</h5>

                    <form v-on:submit.prevent="submit">

                        <div class="form-group col-md-6">
                            <label>Nome</label>
                            <input type="text" class="form-control" v-model="category.name" placeholder="Digite o nome">
                        </div>

                        <div class="form-group col-md-12">
                            <button class="btn btn-success" type="submit">enviar</button>
                            <button class="btn btn-danger" type="button" @click.prevent="deleteCategory(category.id)">
                                deletar
                            </button>
                        </div>

                        <input type="hidden" v-model="category.id">

                    </form>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    const API_URL = 'http://localhost:8000/api';

    export default {
        components: {},
        mounted() {
            axios.get(`${API_URL}/categories`).then((res) => {
                this.$set(this.$data, 'categories', res.data.data)
            });

            axios.get(`${API_URL}/categories`).then((res) => {
                this.$set(this.$data, 'categories', res.data.data)
            });
        },
        data() {
            return {
                errors: [],
                messages: '',
                categories: {},
                category: {
                    name: '',
                    id: ''
                },
                output: '',
                activedView: 0,
                formType: 'insert',
            }
        },
        methods: {
            showView: function (id) {
                this.activedView = id;
            },
            checkForm: function (e) {
                this.errors = [];

                if (this.category.name === '') {
                    this.errors.push('O nome não pode ser vazio');
                }

                if (this.category.name.length > 64) {
                    this.errors.push('O nome não pode conter mais que 64 caracteres');
                }
                e.preventDefault();
            },
            submit: function () {

                if (this.formType == 'insert') {

                    axios.post(`${API_URL}/categories`, this.category).then((res) => {
                        this.categories.push(res.data);
                        this.activedView = 0;
                    });
                }

                if (this.formType == 'update') {
                    axios.put(`${API_URL}/categories`, this.category).then((res) => {

                        if (res.data.error) {
                            console.log(res.data.error);
                            this.errors.push(res.data.error);
                        } else {
                            this.messages = res.data.message;
                            this.category = {
                                name: '',
                                id: ''
                            };
                            this.errors = [];
                            this.activedView = 0;
                        }
                    });
                }
            },
            loadCategory: function (category) {
                this.category = category;
                this.activedView = 1;
                this.formType = 'update'
            },

            createCategory: function () {
                this.activedView = 1;
                this.formType = 'insert';

                this.category = {
                    name: '',
                    id: ''
                };
            },

            deleteCategory: function (id) {
                if (confirm("Realmente deseja excluir esse item?")) {
                    axios.delete(`${API_URL}/categories/${id}`).then((res) => {
                        let index = this.categories.findIndex(category => category.id === id);

                        if (res.data.success) {
                            this.categories.splice(index, 1);
                            this.messages = res.data.success;
                            this.activedView = 0;
                        } else {
                            this.errors.push(res.data.error);
                            this.activedView = 0;
                        }
                    })
                }
            },
        },
    }
</script>
