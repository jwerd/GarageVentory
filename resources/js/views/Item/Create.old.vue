
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Add a new Product</div>

                    <div class="card-body">
                        <form method="POST" action="/login">
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Product Name *</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="name" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Product Photo</label>
                                <div class="col-md-6">
                                    <div v-if="!image">
                                        <h2>Select an image</h2>
                                        <input type="file" @change="onFileChange">
                                    </div>
                                    <div v-else>
                                        <img width="300" fluid :src="image" /><br />
                                        <button @click="removeImage">Remove image</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="cost" class="col-sm-4 col-form-label text-md-right">Product Cost</label>

                                <div class="col-md-3">
                                    <input id="price" placeholder="Purchase Price" type="text" class="form-control" v-model="price" required>
                                </div>

                                <div class="col-md-3">
                                    <input id="list_price" placeholder="List Price" type="text" class="form-control" v-model="list_price" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="size" class="col-sm-4 col-form-label text-md-right">Size (Dimensions)</label>

                                <div class="col-md-2">
                                    <input id="dimension_h" placeholder="Height" type="text" class="form-control" v-model="dimension_h" required>
                                </div>
                                <div class="col-md-2">
                                    <input id="dimension_d" placeholder="Depth" type="text" class="form-control" v-model="dimension_d" required>
                                </div>
                                <div class="col-md-2">
                                    <input id="dimension_l" placeholder="Length" type="text" class="form-control" v-model="dimension_l" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cost" class="col-sm-4 col-form-label text-md-right">Extra Description</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="description" placeholder="Example: This was purchased at Habitat for Humanity" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click="save">
                                        Save
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                image: "",
                selectedImage: {},
                name : "",
                description: "",
                qty : 1,
                price : "",
                list_price : "",
                dimension_h : "",
                dimension_d : "",
                dimension_l : "",
                available : 1,
                user_id: 1,
                btnDisabled : true
            }
        },
        methods : {
            onFileChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;

                this.selectedImage = files[0];
                this.createImage(files[0]);
            },
            createImage(file) {
                var image = new Image();
                var reader = new FileReader();
                var vm = this;

                console.log(image);

                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            removeImage: function (e) {
                e.preventDefault()
                this.image = '';
            },
            save(e){
                e.preventDefault()
                if (this.name.length > 0) {
                    const fd = new FormData()
                    
                    fd.append('image', this.selectedImage, this.selectedImage.name)
                    fd.set('name', this.name)
                    fd.set('description', this.description)
                    fd.set('qty', this.qty)
                    fd.set('price', this.price)
                    fd.set('list_price', this.list_price)
                    fd.set('dimension[height]', this.dimension_h)
                    fd.set('dimension[depth]', this.dimension_d)
                    fd.set('dimension[length]', this.dimension_l)
                    fd.set('available', this.available)

                    this.$api.post('/api/item', fd,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                            this.$noty.success("Item created successfully")
                            this.$router.push('/');
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                }
            },
            btnStatus() {
                if(this.name !== "" && this.qty !== "" && this.price !== "" && this.list_price !== "") {
                    this.btnDisabled = false;
                }
            }
        }
    }
</script>