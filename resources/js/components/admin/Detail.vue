<template>
    <div class="card detalle">
        <div class="container">
            <div class="row">
                <div class="col-md-6 img-product">
                    <div class="galeria">
                        <ul>
                            <li v-for="(item, index) in product.galeria" :key="index" @click="cambiarImage(index)" :class="index == imgActual ? 'active' : ''">
                                <div></div>
                                <img :src="'../storage/product/' + item.name" @error="imageUrlAlt" alt="">
                            </li>
                        </ul>
                    </div>
                    <img v-if="product.galeria.length > 0" :src="'../storage/product/' + product.galeria[imgActual].name" alt="" @error="imageUrlAlt">
                    <img v-if="product.galeria.length == 0" :src="'/img/no-photo.svg'">
                    
                </div>
                <div class="col-md-6 detalle-product">
                    <h1><b>{{ product.name }}</b></h1>
                    <h2 class="precio"><b>USD {{ product.price | toCurrency }} / COP {{ parseFloat(USDCOP) * parseFloat(product.price) | toCurrency }}</b></h2>
                    <table>
                        <tbody>
                            <tr>
                                <td><b>Categoria: </b></td>
                                <td>{{ product.category.name }}</td>
                            </tr>
                            <tr>
                                <td><b>Peso: </b></td>
                                <td>{{ product.weight }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div v-html="product.description"></div> 
                    
                    <form>
                        <div class="form-group row">
                            <label for="cantidad" class="col-sm-2 col-form-label">Cantidad: </label>
                            <div class="col-sm-4">
                                <input type="number" v-model="cantidad" class="form-control" id="cantidad" placeholder="0">
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-comprar">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            Comprar
                        </button>
                    </form>
                </div> 
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data(){
            return {
                product: {},
                carrito: [],
                imgActual: 0,
                cantidad: 0,
                USDCOP: 0,
                agregado: null,
                token: null,
                mensaje: null
            }
        },
        mounted() {
            this.getConvert();
            this.loadProduct();
        },
        methods: {
            imageUrlAlt: function(){
                event.target.src = "/img/no-photo.svg"
            },
            loadProduct: function(){
                let url = new URL(window.location.href);
                if(url.searchParams.get("p")){
                    let id = url.searchParams.get("p");

                    axios.get('../api/product/' + id).then((response)=>{
                        this.product = response.data;
                    });
                }
            },
            getConvert: function(){
                axios.get('http://www.apilayer.net/api/live?access_key=63f153d65e47d0715eb1712afc827b6a').then((response)=>{
                    this.USDCOP = response.data.quotes["USDCOP"];
                });
            },
            cambiarImage: function(index){
                this.imgActual = index;
            }
        }
    }
</script>
