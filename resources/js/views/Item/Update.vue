
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <router-link :to="{ name: 'dashboard'}">< Back to Items</router-link>
                <div class="card card-default">
                    <div class="card-header">Update Product</div>

                    <div class="card-body">
                        <form method="POST" action="/login">
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Product Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="item.name" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Product Photo</label>
                                <div class="col-md-6">
                                    <div v-if="!this.image">
                                        <h2>Select an image</h2>
                                        <input type="file" @change="onFileChange">
                                    </div>
                                    <div v-else>
                                        <img width="300" fluid :src="this.image" /><br />
                                        <button @click="removeImage">Remove image</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cost" class="col-sm-4 col-form-label text-md-right">Product Cost ($)</label>

                                <div class="col-md-6">
                                    <div class="form-label-group">
                                        <input ref="price" type="number" id="price" class="form-control" placeholder="Purchase Price" v-model="item.price">
                                        <label for="price">Purchase Price</label>
                                    </div>
                                    <div v-if="listPriceNotSet">
                                        <div class="form-label-group">
                                            <input ref="com" id="com" placeholder="Cost of Materials" type="number" class="form-control" @change="setPrice()" v-model="com">
                                            <label for="com">Cost of Materials</label>
                                        </div>
                                        <div class="form-label-group">
                                            <input  id="profit" placeholder="Desired Profit" type="number" class="form-control"  v-on:keyup="setPrice()" v-model="profit">
                                            <label for="profit">Desired Profit</label>
                                        </div>
                                        <div class="form-label-group">
                                            <input disabled id="tax" placeholder="Tax Rate: 8.1%" type="number" class="form-control"  v-model="tax">
                                            <label for="tax">Tax Rate: 8.1%</label>
                                        </div>
                                    </div>
                                    <div class="form-label-group" v-else>
                                        <button type="submit" class="btn btn-secondary" @click="recalc">
                                        Re-Calculate
                                        </button>
                                    </div>
                                    <div v-show="item.list_price" class="form-label-group">
                                        <input ref="list_price" id="list_price" placeholder="List Price" type="number" class="form-control" v-model="item.list_price" required>
                                        <label for="tax">List Price</label>
                                    </div>
                                    <div v-show="previousListPrice" class="form-label-group">
                                        <input disabled ref="previous_list_price" id="previous_list_price" placeholder="Previous List Price" type="number" class="form-control" v-model="previousListPrice">
                                        <label for="tax">Previous List Price</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="size" class="col-sm-4 col-form-label text-md-right">Size (Dimensions)</label>

                                <div class="col-md-2">
                                    <div class="form-label-group">
                                        <input id="dimension_h" placeholder="Height" type="number" class="form-control" v-model="dimension.height" required>
                                        <label for="dimension_h">Height</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-label-group">
                                        <input id="dimension_d" placeholder="Depth" type="number" class="form-control" v-model="dimension.depth" required>
                                        <label for="dimension_d">Width</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-label-group">
                                        <input id="dimension_l" placeholder="Length" type="number" class="form-control" v-model="dimension.length" required>
                                        <label for="dimension_l">Length</label>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="cost" class="col-sm-4 col-form-label text-md-right">Extra Description</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="item.description" placeholder="Example: This was purchased at Habitat for Humanity" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cost" class="col-sm-4 col-form-label text-md-right"></label>
                                <div class="col-md-6">
                                    <label>
                                        <small>
                                        Product Added On: {{item.added}}
                                        </small>
                                    </label>
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" :disabled="submitting === true" @click="save">
                                        Save
                                    </button>
                                    <MarkItemSold v-show="!this.item.price_sold" :item="this.item"></MarkItemSold>
                                </div>
                            </div>

                                <br />

                                <b-card bg-variant="light">
                                    <b-button class="btn-danger" size="sm" @click.stop="removeItem(item.id)">
                                    Remove This Item
                                    </b-button>
                                    <span>* This is irreversible</span>
                                </b-card>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import MarkItemSold from "../../components/MarkItemSold"
    export default {
        components: {MarkItemSold},
        data(){
            return {
                id: this.$route.params.id,
                item: [],
                image: '',
                selectedImage: '',
                com : '',
                tax : '',
                profit : '',
                previousListPrice : '',
                listPriceNotSet: false,
                priceSet: false,
                priceSetFinal: false,
                dimension: {
                    'height': '',
                    'width': '',
                    'length': '',
                },
                submitting: false,
                btnDisabled : true
            }
        },
        mounted() {
            this.$api.get('/api/item/'+this.id)
                .then(response => {
                    this.item        = response.data.data
                    if(!this.item.list_price) {
                        this.listPriceNotSet = true;
                    }
                    this.dimension   = this.item.dimension
                    this.image       = this.item.photo
                })
                .catch(function (error) {
                    console.error(error)
                });
        },
        methods : {
            save(e){
                e.preventDefault()
                if (this.item.name.length > 0) {
                    this.item.dimension = this.dimension
                    this.submitting = true;
                    this.$api.put('/api/item/'+this.id, this.item)
                        .then(response => {
                            if(this.selectedImage !== "") {
                                const fd = new FormData()
                                fd.append('image', this.selectedImage, this.selectedImage.name)
                                this.$api.post('/api/ItemMedia/'+this.id, fd,
                                {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                }).then(response => {
                                        this.$noty.success("Media uploaded successfully")
                                    })
                            }
                            this.$noty.success("Item updated successfully")
                            this.$router.push('/');
                        })
                        .catch(function (error, submitting) {
                            submitting = false
                            console.error(error)
                        });
                }
            },
            recalc(e) {
                e.preventDefault()
                this.listPriceNotSet = true
                this.previousListPrice = this.item.list_price;
                this.item.list_price = null
                this.$nextTick(function(){
                    this.$refs.com.focus()
                });
            },
            removeItem(id) {
                if(confirm('Are you sure you want to remove this item?')) {
                    this.$api.delete('/api/item/'+id, {'available': false}).then(response => {
                        this.$noty.success("Item deleted successfully...")
                        this.$router.push('/')
                    })
                }
            },
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
                if(this.item.price !== "") {
                    this.priceSet = true;
                    //this.$refs.com.focus();
                    sum = sum+parseFloat(this.item.price);
                }

                if(this.com !== "") {
                    sum = sum+parseFloat(this.com);
                }

                if(this.profit !== "") {
                    sum = sum+parseFloat(this.profit);
                }

                if(this.item.price !== "" && this.com !== "" && this.profit !== "") {
                    this.priceSetFinal = true;
                    //this.$refs.list_price.focus();
                    let taxRate = .0810;
                    let taxTotal = 0;
                    taxTotal = parseFloat(sum * taxRate).toFixed(2);
                    this.tax = taxTotal;
                    sum = parseFloat(sum)+parseFloat(taxTotal);
                    this.item.list_price = this.roundBy5(parseFloat(sum).toFixed(2));
                }

            }
        }
    }
</script>
