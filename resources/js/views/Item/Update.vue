
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
                                    <input id="name" type="text" class="form-control" v-model="task.name" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cost" class="col-sm-4 col-form-label text-md-right">Product Cost</label>

                                <div class="col-md-3">
                                    <input id="price" placeholder="Purchase Price" type="text" class="form-control" v-model="task.price" required>
                                </div>

                                <div class="col-md-3">
                                    <input id="list_price" placeholder="List Price" type="text" class="form-control" v-model="task.list_price" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="size" class="col-sm-4 col-form-label text-md-right">Size (Dimensions)</label>

                                <div class="col-md-2">
                                    <input id="dimension_h" placeholder="Height" type="text" class="form-control" v-model="task.dimension.height" required>
                                </div>
                                <div class="col-md-2">
                                    <input id="dimension_d" placeholder="Depth" type="text" class="form-control" v-model="task.dimension.depth" required>
                                </div>
                                <div class="col-md-2">
                                    <input id="dimension_l" placeholder="Length" type="text" class="form-control" v-model="task.dimension.length" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cost" class="col-sm-4 col-form-label text-md-right">Extra Description</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="task.description" placeholder="Example: This was purchased at Habitat for Humanity" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click="save">
                                        Save
                                    </button>

                                </div>
                            </div>
                                <br />
                                    <b-card bg-variant="light">
                                        <b-button class="btn-danger" size="sm" @click.stop="removeItem(task.id)">
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
    export default {
        data(){
            return {
                id: this.$route.params.id,
                task: [],
                btnDisabled : true
            }
        },
        mounted() {
            this.$api.get('/api/item/'+this.id)
                .then(response => {
                    console.log(response);
                    this.task = response.data;
                    this.task.dimension_h = response.data.dimension.width;
                    this.task.dimension_d = response.data.dimension.depth;
                    this.task.dimension_l = response.data.dimension.length;
                })
                .catch(function (error) {
                    console.error(error);
                });
        },
        methods : {
            save(e){
                e.preventDefault()
                if (this.task.name.length > 0) {
                    
                    this.$api.put('/api/item/'+this.id, this.task)
                        .then(response => {
                            this.$noty.success("Item updated successfully")
                            this.$router.push('/');
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                }
            },
            removeItem(id) {
                if(confirm('Are you sure you want to remove this item?')) {
                    this.$api.delete('/api/item/'+id, {'available': false}).then(response => {
                        this.$noty.success("Item deleted successfully...")
                        this.$router.push('/');
                    })
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