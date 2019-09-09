<template>
    <div>
        <!--<h3>Categorias</h3>-->
        <!--<nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" v-for="item in breadcrumb" :key="item.level">
                    <a href="#">{{ item.name }}</a>
                </li>
            </ol>
        </nav>-->

        <h3>Tienda Virtual</h3>
        
        <br>

        <div class="row">
            <div class="col-xs-6 col-md-6 col-lg-2"  v-for="item in categories.data" :key="item.codigo">
                <div class="card card-category" @click="selCategory(item)">
                    <img :src="'../storage/category/' + item.id + '.jpg'" class="img-category" @error="imageUrlAlt">
                    <p>{{ item.name }}</p>
                </div>
            </div>
        </div>    
    
        <div class="row">
            <div class="row d-flex justify-content-center" v-if="products.data.length == 0 && empty" style="width:100%; margin-left: 0%; padding: 5% 20% 150px;">
                <div class="row d-flex justify-content-center">
                    <img src="/img/none.svg" alt="" width="100px" height="100px" style="margin: 10px;">
                    <h3 style="text-align: center;">Lo sentimos, no encontramos Productos para los filtros aplicados</h3>
                    <!--<a href="/producto"><i class="fa fa-refresh" aria-hidden="true"></i> Volver a Productos</a>-->
                </div>
            </div>
            <div class="col-xs-6 col-md-6 col-lg-3"  v-for="item in products.data" :key="item.id" @click="openDetalle(item.id)">
                <div class="card card-product">
                    <img :src="'../storage/product/' + item.id + '_0.jpg'" class="img-product" @error="imageUrlAlt">
                    <p class="name">{{ item.name }}</p>
                    <p><b>Categoria:</b> {{ item.category.name }}</p>
                    <p><b>Peso:</b> {{ item.weight }}</p>
                    <p><b>USD:</b> ${{ item.price | toCurrency }}</p>
                    <p><b>COP:</b> ${{ parseFloat(USDCOP) * parseFloat(item.price) | toCurrency  }}</p>
    
                    <button type="button" class="btn btn-primary btn-cart-producto">
                        <i class="fa fa-truck icon-right" aria-hidden="true"></i>
                        Agregar 
                    </button>
                </div>
            </div>
        </div> 
    
        <pagination v-if="categories.last_page > 1" :pagination="categories" :offset="4" @paginate="fetchPageCategory()"></pagination>
        <pagination v-if="products.last_page > 1" :pagination="products" :offset="4" @paginate="fetchPageProduct()"></pagination>
    
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data(){
			return {
                breadcrumb: [],
                categories: [],
                products: { data: [] },
                USDCOP: 0,
                level: 0,
                empty: false
            }
        },
        mounted(){
            this.breadcrumb.push({level: this.level, name:"Categorias"})
            this.getConvert();
            this.getCategories();
        },
        methods:{
            imageUrlAlt: function(){
                event.target.src = '../img/no-photo.svg'
            },
            getCategories: function(){
                this.$delete(this.categories, this.level);

                let id = 0;
                
                let url = new URL(window.location.href);

                let params = {};

                if(url.searchParams.get("page"))
                    params.page = url.searchParams.get("page");

                if(url.searchParams.get("c")){
                    id = url.searchParams.get("c");
                }
                    

                if(id != 0){
                    axios.get('../api/category/paginateshow/' + id, {params: params}).then((response)=>{
                        this.categories = response.data;
                        if(this.categories.data.length == 0){
                            this.getProducts(id);
                        }
                    })
                }else{
                    axios.get('../api/category/paginate', {params: params}).then((response)=>{
                        this.categories = response.data;
                        if(this.categories.data.length == 0){
                            this.getProducts(id);
                        }
                    })
                }
            },
            getConvert: function(){
                axios.get('http://www.apilayer.net/api/live?access_key=63f153d65e47d0715eb1712afc827b6a').then((response)=>{
                    this.USDCOP = response.data.quotes["USDCOP"];
                });
            },
            getProducts: function(id){
                
                let url = new URL(window.location.href);

                let params = {};

                if(url.searchParams.get("page"))
                    params.page = url.searchParams.get("page");

                axios.get('../api/product/paginate/' + id, {params: params}).then((response)=>{
                    this.products = response.data;
                    this.empty = true;
                });
            },
            selCategory: function(item){
                //this.breadcrumb.push({level: this.level + 1, name: item.name})
                //this.level = this.level + 1;
                //this.getCategories(item.id);
                let url = new URL(window.location.href);

                let params = {};
                if(url.searchParams.get("page"))
                    params.page = url.searchParams.get("page");

                params.c =  item.id;
                const searchParams = new URLSearchParams(params);
                window.location.href = '/shop?' + searchParams.toString();
            },
            fetchPageCategory: function(){
                let url = new URL(window.location.href);
                let params = {};
                params.page = this.categories.current_page;
                const searchParams = new URLSearchParams(params);
                window.location.href = '/shop?' + searchParams.toString();
            },
            fetchPageProduct: function(){
                let url = new URL(window.location.href);
                let params = {};
                params.page = this.products.current_page;

                if(url.searchParams.get("c"))
                    params.c = url.searchParams.get("c");

                const searchParams = new URLSearchParams(params);
                window.location.href = '/shop?' + searchParams.toString();
            },
            openDetalle: function(id){
                window.location.href = '/detalle?p=' + id;
            }
        }
    }

</script>
