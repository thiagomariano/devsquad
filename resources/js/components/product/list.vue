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
                    <a href="#" @click.prevent="createProduct()" v-if="activedView == 0">
                        Cadastrar novo produto
                    </a>

                    <a href="#" @click.prevent="importProduct()" v-if="activedView == 0">
                        Importar produto
                    </a>
                </h5>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col-4">Nome</th>
                            <th scope="col-4">Categoria</th>
                            <th scope="col-3">Preço</th>
                            <th scope="col-1">Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in products">
                            <td class="col-4"> {{product.name}}</td>
                            <td class="col-4"> {{product.category.name}}</td>
                            <td class="col-3"> {{product.price}}</td>
                            <td class="col-1">
                                <button class="btn btn-success" @click.prevent="loadProduct(product)"> Editar</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="card" v-if="activedView == 1">
                <h5 class="card-header">
                    <button class="btn btn-success" @click.prevent="resetIndex()" v-if="activedView == 1">
                        Voltar
                    </button>
                </h5>
                <div class="card-body">
                    <h5 class="card-title">Produtos</h5>

                    <form v-on:submit.prevent="checkForm">

                        <div class="form-group col-12">
                            <label>Nome</label>
                            <input type="text" class="form-control" v-model="product.name" placeholder="Digite o nome">
                        </div>

                        <div class="form-group col-12">
                            <label>Categoria</label>
                            <select class="form-control" v-model="product.category_id">
                                <option v-for="o in categories" v-bind:value="o.id">
                                    {{ o.name }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-12">
                            <label>Preço</label>
                            <input type="text" class="form-control" v-model="product.price" placeholder="Digite o nome">
                        </div>

                        <div class="form-group col-12">
                            <label>Imagem
                                <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
                            </label>
                        </div>

                        <div class="form-group col-12" v-if="product.image">
                            <img :src="imgSrc = 'images/product/' + product.image" height="100px"/>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Descrição</label>
                            <input type="text" class="form-control" v-model="product.description"
                                   placeholder="Digite o nome">
                        </div>

                        <div class="form-group col-md-12">
                            <button class="btn btn-success" type="submit">enviar</button>
                            <button class="btn btn-danger" type="button" @click.prevent="deleteProduct(product.id)">
                                deletar
                            </button>
                        </div>

                        <input type="hidden" v-model="product.id">

                    </form>

                </div>
            </div>

            <div class="card" v-if="activedView == 2">
                <h5 class="card-header">
                    <button class="btn btn-success" @click.prevent="showView(0)" v-if="activedView == 1">
                        Voltar
                    </button>
                </h5>
                <div class="card-body">
                    <h5 class="card-title">Produtos</h5>

                    <form v-on:submit.prevent="checkImport">

                        <div class="form-group col-md-6">
                            <label>Arquivo
                                <input type="file" id="file" ref="file" v-on:change="handleFileImport()"/>
                            </label>
                        </div>

                        <div class="form-group col-md-12">
                            <button class="btn btn-success" type="submit">enviar</button>
                        </div>

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
            axios.get(`${API_URL}/products`).then((res) => {
                this.$set(this.$data, 'products', res.data.data)
            });

            axios.get(`${API_URL}/categories`).then((res) => {
                this.$set(this.$data, 'categories', res.data.data)
            });
        },
        data() {
            return {
                errors: [],
                messages: '',
                msg: '',
                selectedFile: null,
                product: {
                    name: '',
                    category_id: '',
                    price: '',
                    image: '',
                    file: '',
                    description: '',
                    id: ''
                },
                import: {
                    file: ''
                },
                output: '',
                products: [],
                categories: [],
                title: "Lista de produtos",
                menus: [
                    {id: 0, name: 'Lista de produtos'},
                    {id: 1, name: 'Criar produtos'}
                ],
                activedView: 0,
                formType: 'insert',
                file: ''
            }
        },
        methods: {

            handleFileUpload() {
                this.product.file = this.$refs.file.files[0];
            },
            handleFileImport() {
                this.import.file = this.$refs.file.files[0];
            },
            showView: function (id) {
                this.activedView = id;
            },
            onFileChanged(event) {
                this.selectedFile = event.target.files[0];
                console.log(this.selectedFile);
            },
            checkForm: function (e) {
                if (this.product.name && this.product.category_id && this.product.price) {
                    this.errors = [];
                    this.submit();
                }

                this.errors = [];

                if (!this.product.name) {
                    this.errors.push('O campo nome é obrigatório.');
                }

                if (!this.product.category_id) {
                    this.errors.push('O campo categoria é obrigatório.');
                }

                if (!this.product.price) {
                    this.errors.push('O campo preço é obrigatório.');
                }
                e.preventDefault();
            },

            checkImport: function () {
                if (this.import.file) {
                    this.errors = [];
                    this.submit();
                }

                if (!this.import.file) {
                    this.errors.push('O arquivo é obrigatório.');
                }
            },
            submit: function () {

                if (this.formType == 'import') {
                    let formData = new FormData();
                    formData.append('image', this.import.file);

                    axios.post(`${API_URL}/products-import`,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(function () {
                        //não esta aceitando this.messages = ''
                        console.log('Lista de produto importado com sucesso.');
                    }).catch(function () {
                        //não esta aceitando this.erros.push()
                        console.log('Erro ao importado com sucesso.');
                    });

                    this.resetIndex();
                }

                if (this.formType == 'insert') {

                    //verifica se tem o nome do produto para edição
                    axios.post(`${API_URL}/products/verify-create`, {
                        name: this.product.name
                    }).then((res) => {
                        if (res.data == '') {

                            let formData = new FormData();
                            formData.append('id', this.product.id);
                            formData.append('file', this.product.file);
                            formData.append('name', this.product.name);
                            formData.append('category_id', this.product.category_id);
                            formData.append('price', this.product.price);
                            formData.append('description', this.product.description);

                            axios.post(`${API_URL}/products`,
                                formData,
                                {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                }
                            ).then(function (res) {
                                this.messages  = 'Produto inserido com sucesso.';
                            }).catch(function (res) {
                                console.log('FAILURE!!');
                            });

                            this.resetIndex();

                        } else {
                            this.errors.push('Nome do produto já existe, registre outro.')
                        }
                    });
                }

                if (this.formType == 'update') {
                    //verifica se tem o nome do produto para edição
                    axios.post(`${API_URL}/products/verify-edit`, {
                        name: this.product.name,
                        id: this.product.id
                    }).then((res) => {
                        if (res.data == '') {

                            let formData = new FormData();
                            formData.append('id', this.product.id);
                            formData.append('file', this.product.file);
                            formData.append('name', this.product.name);
                            formData.append('category_id', this.product.category_id);
                            formData.append('price', this.product.price);
                            formData.append('description', this.product.description);

                            axios.post(`${API_URL}/products-up`,
                                formData,
                                {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                }
                            ).then(function () {
                                console.log('Produto editado com sucesso sucesso.');
                            }).catch(function () {
                                console.log('Erro ao editar produto.');
                            });
                        } else {
                            this.errors.push('Nome do produto já existe, registre outro.')
                        }
                        this.resetIndex();
                    });
                }
            },
            loadProduct: function (product) {
                this.product = product;
                this.activedView = 1;
                this.formType = 'update'
            }
            ,

            createProduct: function () {
                this.activedView = 1;
                this.formType = 'insert';

                this.product = {
                    name: '',
                    category_id: '',
                    price: '',
                    image: '',
                    description: '',
                    id: ''
                };

            },
            importProduct: function () {
                this.activedView = 2;
                this.formType = 'import';

                this.import = {
                    file: ''
                };
            },
            deleteProduct: function (id) {
                if (confirm("Realmente deseja excluir esse item?")) {

                    axios.delete(`${API_URL}/products/${id}`).then((res) => {
                        let index = this.products.findIndex(product => product.id === id);

                        if (res.data.success) {
                            this.resetIndex();
                        } else {
                            alert(res.data.error);
                        }
                    })
                }
            }
            ,
            resetIndex: function () {

                this.product = {
                    name: '',
                    category_id: '',
                    price: '',
                    image: '',
                    description: '',
                    id: ''
                };

                //recarregar a imagem do produto
                axios.get(`${API_URL}/products`).then((res) => {
                    this.$set(this.$data, 'products', res.data.data)
                });

                this.activedView = 0;
                this.errors = [];
            }
        }
        ,
    }
</script>
