<template>
    <div class="card">
        <div class="alert alert-primary" role="alert">
            Cree un categoria y en la lista por favor dar click para crear una sub categoria.
        </div>
        <div class="row">
            <div class="col-md-4" v-for="index in (this.level + 1)" :key="index">
                <table id="myTable" class="table-categoria">
                    <thead>
                        <tr>
                            <th v-if="index == 1">Nivel {{ index }}</th>
                            <th v-if="index > 1">Nivel {{ index + ' - ' + getName(index) }}</th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input  v-if="!edit[index]" type="text" v-model="category[index - 1].name"  v-on:keydown.enter.prevent="saveCategories(index - 1)" class="form-control" id="categoriy_name"  placeholder="Nombre Categoria">
                                <input  v-if="edit[index]" type="text" v-model="category[index - 1].name"  v-on:keydown.enter.prevent="updateCategories(index - 1)" class="form-control" id="categoriy_name"  placeholder="Nombre Categoria">
                            </td>
                            <td>
                                <input type="file" id="fileInput" style="display: none" v-on:change="onFileChange">
                                <button type="button" class="btn btn-ligth" onclick="document.getElementById('fileInput').click()">
                                    <i class="fa fa-picture-o"></i>
                                </button>
                            </td>
                            <td>
                               
                                <button v-if="!edit[index]" type="button" class="btn btn-primary" @click="saveCategories(index - 1)">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button v-if="edit[index]" type="button" class="btn btn-primary" @click="updateCategories(index - 1)">
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-for="item in categories[index - 1]" :key="item.id" v-bind:class="classObject(item, index)">
                            <td @click="subcategory(item.id, index)">
                                {{ item.name }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-default" @click="editCategories(item, index-1)">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default" @click="deleteCategoria(item.id, index-1)">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>	
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';
    export default {
        data(){
			return {
                category: [{name: '', parent: null}],
                categories: [],
                level: 0,
                edit: [false],
                image: ''
			}
        },
        mounted(){
			this.getCategories(this.level);
		},
        methods:{
            // FILE CHANGE
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            // CREATE IMAGE
            createImage(file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            //ACTIVE CLICK CATEGORY
            classObject: function (item, index) {
                let self = this;

                if(self.category[index]){
                    if(self.category[index].parent == item.id){
                        return 'active';
                    }else{
                        return '';
                    }
                }
                
            },
            // GET NAME CATEGORY
            getName: function(index){
                for (var i = 0; i < this.categories[index-2].length; i++) { 
                    if(this.categories[index-2][i].id == this.category[index-1].parent) 
                        return this.categories[index-2][i].name;
                }

                return '';
            },
            // GET CATEGORIES
			getCategories: function(index, id){
                this.$delete(this.categories, index);
                if(id){
                    axios.get('../api/category/' + id).then((response)=>{
                        this.categories.splice(index, 0, response.data);
                    })
                }else{
                    axios.get('../api/category/').then((response)=>{
                        this.categories.splice(index, 0, response.data);
                    })
                }
            },
            //SAVE CATEGORIES
            saveCategories: function(index){
                if(this.image != '' && this.category[index].name != ''){
                    this.category[index]['image'] = this.image;
                    axios.post('../api/category',  this.category[index]).then((response)=>{
                        this.category[index].name = '';
                        this.getCategories(index, this.category[index].parent);
                    })
                }else{
                    this.$swal('Error', 'Por favor ingrese una Imagen y Nombre de Categoria.', 'error');
                }

               
            },
            // EDIT CATEGORY
            editCategories: function(item, index){
                this.edit.splice(index + 1, 0, true);
                this.category[index] = item;
            },
            // UPDATE CATEGORY
            updateCategories: function(index){
                if(this.image != '')
                    this.category[index]['image'] = this.image;

                axios.put('../api/category/' + this.category[index].id,  this.category[index]).then((response)=>{
                    this.edit.splice(index + 1, 0, false);  
                    this.category[index].name = ''; 
                    this.getCategories(index, this.category[index].parent);
				})
            },
            // SELECT SUB CATEGORY
            subcategory: function(id, index){
                this.level = index;
                this.$delete(this.category, index);
                this.category.splice(index, 0, {name: '', parent: id});
                this.edit.splice(index, 0, false);
                this.getCategories(index, id);
            },
            // DELETE CATEGORY
            deleteCategoria: function(id, index){
                axios.delete('../api/category/' + id).then((response)=>{
                    for (var i = 0; i < this.categories[index].length; i++) { 
                        if(this.categories[index][i].id == id) 
                            this.$delete(this.categories[index], i);
                    }
                })
                .catch((error) => {
                    this.$swal('Error', error.response.data.message, 'error');
                })
            }
		}
    }
</script>
