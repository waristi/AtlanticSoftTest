<template>
    <div>
         <!-- Modal -->
        <div class="modal fade" id="ProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="myForm" v-on:submit.prevent="saveProduct()" class="needs-validation" novalidate>
                        <div class="modal-body">

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="category">Categoria: </label>
                                    <!--<input type="text" v-model="product.category_id" class="form-control" id="category"  placeholder="Nombre del Producto"  required>-->
                                    <select class="form-control" id="categories_id" v-model="product.categories_id" required>
                                        <option v-for="item in categories" :key="item.id"  v-bind:value="item.id">{{ item.name }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor ingrese una Categoria.
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="name">Nombre</label>
                                    <input type="text" v-model="product.name" class="form-control" id="name"  placeholder="Nombre del Producto"  required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese un Nombre.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Descripción: </label>
                                <input type="text" v-model="product.description" class="form-control" id="name"  placeholder="Descripción del Producto"  required>
                                <div class="invalid-feedback">
                                    Por favor ingrese una Descripcion.
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="price">Precio USD:</label>
                                    <input type="number" v-model="product.price" class="form-control" id="name"  placeholder="Precio del Producto"  required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese una Precio.
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="weight">Peso:</label>
                                    <input type="number" v-model="product.weight" class="form-control" id="weight"  placeholder="Peso del Producto"  required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese un Peso.
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Galeria de Imagen</label>
                                <div class="alert alert-info" role="alert">
                                    Para selecionar varia imagenes presione la tecla <b>ctrl</b> y click en las imagenes (Solo puede Agregar <b>3</b> Por producto).<br>
                                </div>
                                <input type="file" @change="previewFiles" class="form-control-file" id="gallery_producto" name="input_img" accept="image/*" ref="myFileInput" multiple>
                                <div class="content-image">
                                    <p v-if="imagenes.length == 0"><b>No tiene Imagenes Agregadas</b></p>
                                    <div class="panel">
                                        <div class="image" v-for="(item, index) in imagenes" :key="index">
                                            <div class="eliminar" @click="eliminarImagen(index)"><i class="fa fa-times" aria-hidden="true"></i></div>
                                            <img :src="item" width="100" height="80">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div>
                <button type="button" class="btn btn-primary pull-right button-icon" @click="showModal(null)">
                    Nuevo <i class="fa fa-plus icon-right"></i>  
                </button>
            </div>
            <br>
            <table id="myTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Peso</th>
                        <th>Categoria</th>
                        <th scope="col"  width="10%">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for= "item in products" :key="item.id">
                        <td>{{item.id}}</td>
                        <td>{{item.name}}</td>
                        <td>USD {{item.price | toCurrency }}</td>
                        <td>{{item.weight }}</td>
                        <td>{{item.category.name }}</td>
                        <td class="btn-accion">
                            <a @click="showModal(item)"  role="button" aria-pressed="true" class="btn btn-default editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a @click="deleteProduct(item.id)" role="button" aria-pressed="true"  class="btn btn-default eliminar">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import datatables from 'datatables';
    import axios from 'axios';

    function toDataUrl(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.onload = function() {
            var reader = new FileReader();
            reader.onloadend = function() {
                callback(reader.result);
            }
            reader.readAsDataURL(xhr.response);
        };
        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
    }

    export default {
        data(){
			return {
                formEdit: false,
                product: {},
                categories: [],
                products: [],
                imagenes: []
			}
        },
        mounted(){
            this.getProducts();
            this.getCategories();
        },
        methods:{
            tabla(){
                $('#myTable').DataTable().destroy();
                $(document).ready( function () {
                    $('#myTable').DataTable({
                        "bLengthChange" : true,
                        "order": [],
                        language: {
                            "decimal": "",
                            "emptyTable": "No hay información",
                            "info": "Mostrando _START_ de _TOTAL_",
                            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                            "infoPostFix": "",
                            "thousands": ",",
                            "lengthMenu": "Filas _MENU_",
                            "loadingRecords": "Cargando...",
                            "processing": "Procesando...",
                            "search": "Buscar:",
                            "zeroRecords": "Sin resultados encontrados",
                            "paginate": {
                                "first": "<<",
                                "last": ">>",
                                "next": ">",
                                "previous": "<"
                            }
                        }
                    }); 
                });          
            },
            getCategories: function(){
                axios.get('../api/category/select').then((response)=>{
                    this.categories = response.data;
                })
            },
            getProducts: function(){
                axios.get('../api/product').then((response)=>{
                    this.products = response.data;
                    this.tabla();
                })
            },
            showModal(product) {
                $('#myForm').removeClass('was-validated');
                $('#ProductModal').modal('show');

                this.imagenes = [];

                if(product){
                    this.formEdit = true;
                    this.product = product;
                    let self = this;
                    for(var i=0; i < this.product.galeria.length; i++){
                        toDataUrl('/storage/product/' + this.product.galeria[i].name, function(myBase64) {
                            self.imagenes.push(myBase64); 
                        });
                    }
                }else{ 
                    this.product = {};
                    this.formEdit = false;
                }
            },
            previewFiles: function(event){
                let self = this;
                if((this.imagenes.length + event.target.files.length) > 3){
                    this.$swal('Información', 'Solo puede agregar 3 Imagenes por Producto.' , 'success');
                }else{
                    $(event.target.files).each(function () {
                        var reader = new FileReader();
                        reader.readAsDataURL(this);
                        reader.onload = function (e) {
                            self.imagenes.push(e.target.result);
                        }
                    });
                    this.$refs.myFileInput.value = '';
                }
            },
            saveProduct(){
                this.product.images = this.imagenes;
                if(this.formEdit == true){
                    axios.put('../api/product/' + this.product.id, this.product)
                    .then((response) => {
                        $('#ProductModal').modal('hide');
                        this.product = {};
                        this.getProducts();
                    })
                }else{
                    axios.post('../api/product', this.product)
                    .then((response) => {
                        $('#ProductModal').modal('hide');
                        this.product = {};
                        this.getProducts();
                    })
                }
            },
            deleteProduct: function(id){
                this.$swal({
                    title: "Eliminar Producto",
                    text: "¿Esta seguro de Eliminar este Producto?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "SI",
                    cancelButtonText: "NO",
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if(willDelete.dismiss != 'cancel'){
                        axios.delete('../api/product/' + id).then((response)=>{
                            this.getProducts();
                            this.$swal('Información', 'Se elimino producto correctamente.' , 'success');
                        })
                        .catch((error) => {
                            this.$swal('Error', error.response.data.message, 'error');
                        })
                    }   
                });
            },
            eliminarImagen: function(index){
                let self = this;
                this.$swal({
                    title: "Eliminar Imagen",
                    text: "¿Esta seguro de Eliminar esta Imagen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "SI",
                    cancelButtonText: "NO",
                    dangerMode: true,
                })
                .then((willDelete)=>{
                    if(willDelete.dismiss != 'cancel'){
                        self.$delete(self.imagenes, index);
                        self.$delete(self.url, index);
                    }
                });
            },
        }
    }
</script>
