
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
                                <label for="cost" class="col-sm-4 col-form-label text-md-right">Product Cost ($)</label>

                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <input ref="price" type="number" id="price" class="form-control" placeholder="Purchase Price" v-model="price" v-on:keyup="setPrice()">
                                        <label for="price">Purchase Price</label>
                                    </div>
                                    <div class="form-label-group">
                                        <input ref="com" id="com" placeholder="Cost of Materials" type="number" class="form-control" @change="setPrice()" v-model="com">
                                        <label for="com">Cost of Materials</label>
                                    </div>
                                    <div v-if="priceSet" class="form-label-group">
                                        <input  id="profit" placeholder="Desired Profit" type="number" class="form-control"  v-on:keyup="setPrice()" v-model="profit">
                                        <label for="profit">Desired Profit</label>
                                    </div>
                                    
                                    <div v-show="priceSetFinal">
                                        <div class="form-label-group">
                                            <input disabled id="tax" placeholder="Tax Rate: 8.1%" type="number" class="form-control"  v-model="tax">
                                            <label for="tax">Tax Rate: 8.1%</label>
                                        </div>
                                        <div class="form-label-group">
                                            <input ref="list_price" id="list_price" placeholder="List Price" type="number" class="form-control" v-model="list_price" required>
                                            <label for="tax">Suggested List Price</label>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="size" class="col-sm-4 col-form-label text-md-right">Size (Dimensions)</label>

                                <div class="col-md-2">
                                    <div class="form-label-group">
                                        <input id="dimension_h" placeholder="Height" type="number" class="form-control" v-model="dimension_h" required>
                                        <label for="dimension_h">Height</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-label-group">
                                        <input id="dimension_d" placeholder="Depth" type="number" class="form-control" v-model="dimension_d" required>
                                        <label for="dimension_d">Depth</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-label-group">
                                        <input id="dimension_l" placeholder="Length" type="number" class="form-control" v-model="dimension_l" required>
                                        <label for="dimension_l">Length</label>
                                    </div>
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
                                    <button type="submit" class="btn btn-primary" :disabled="submitting === true" @click="save">
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
                selectedImage: '',
                name : "",
                description: "",
                qty : 1,
                price : "",
                com : "",
                tax : "",
                profit : "",
                priceSet: false,
                priceSetFinal: false,
                list_price : "",
                dimension_h : "",
                dimension_d : "",
                dimension_l : "",
                available : 1,
                user_id: 1,
                submitting: false,
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
                    this.submitting = true
                    this.$api.post('/api/item', {
                        name: this.name,
                        description: this.description,
                        qty: this.qty,
                        price: this.price,
                        list_price: this.list_price,
                        dimension: {
                            height: this.dimension_h,
                            depth: this.dimension_d,
                            length: this.dimension_l,
                        },
                        available: this.available,
                    })
                    .then(response => {
                            let id = response.data.data.id;
                            if(this.selectedImage !== "") {
                                const fd = new FormData()
                                fd.append('image', this.selectedImage, this.selectedImage.name)
                                this.$api.post('/api/ItemMedia/'+id, fd,
                                {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                }).then(response => {
                                        this.$noty.success("Media uploaded successfully")
                                    })
                            }
                            this.$noty.success("Item created successfully")
                            this.$router.push('/');
                        })
                        .catch(function (error, submitting) {
                            submitting = false
                            console.error(error);
                        });
                }
            },
            btnStatus() {
                if(this.name !== "" && this.qty !== "" && this.price !== "" && this.list_price !== "") {
                    this.btnDisabled = false;
                }
            },
            roundBy5(x) { //@todo: move this to utils
                return Math.ceil(x/5)*5;
            },
            setPrice() {
                let sum = 0;
                if(this.price !== "") {
                    this.priceSet = true;
                    //this.$refs.com.focus();
                    sum = sum+parseFloat(this.price);
                    console.log('setting price')
                }

                if(this.com !== "") {
                    sum = sum+parseFloat(this.com);
                    console.log('setting com')
                }

                if(this.profit !== "") {
                    sum = sum+parseFloat(this.profit);
                    console.log('setting profit')
                }

                if(this.price !== "" && this.com !== "" && this.profit !== "") {
                    this.priceSetFinal = true;
                    //this.$refs.list_price.focus();
                    let taxRate = .0810;
                    let taxTotal = 0;
                    taxTotal = parseFloat(sum * taxRate).toFixed(2);
                    this.tax = taxTotal;
                    console.log(taxTotal);
                    sum = parseFloat(sum)+parseFloat(taxTotal);
                    this.list_price = this.roundBy5(parseFloat(sum).toFixed(2));
                }

            }
        }
    }
</script>