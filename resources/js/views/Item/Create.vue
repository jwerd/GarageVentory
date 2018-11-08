
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Add a new Product</div>

                    <div class="card-body">
                        <form method="POST" action="/login">
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Product Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="name" required autofocus>
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
                                <label for="size" class="col-sm-4 col-form-label text-md-right">Product Is Available</label>

                                <div class="col-md-6">
                                    <select class="form-control" v-model="available">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="qty" class="col-sm-4 col-form-label text-md-right">Quantity</label>

                                <div class="col-md-6">
                                    <input id="qty" type="text" class="form-control" v-model="qty" required>
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
                name : "",
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
            save(e){
                e.preventDefault()
                if (this.name.length > 0) {
                    this.$api.post('/api/item', {
                        name: this.name,
                        qty: this.qty,
                        price: this.price,
                        list_price: this.list_price,
                        dimension: {
                            height: this.dimension_h,
                            width: this.dimension_d,
                            length: this.dimension_l,
                        },
                        available: this.available,
                    })
                        .then(response => {
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