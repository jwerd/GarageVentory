<template>
    <b-button class="btn-success" size="sm" @click="markItemSold(item.id)">
        Mark Item Sold
    </b-button>
</template>

<script>
    export default {
        isBusy: false,
        name: "MarkItemSold",
        props: ['item'],
        data () {
            return {
            }
        },
        computed: {
            
        },
        methods: {
            markItemSold(id) {
                swal({
                    text: 'What did this purchase go for?',
                    content: "input",
                    button: {
                        text: "Save",
                        closeModal: false,
                    },
                })
                .then(price_sold => {
                    if (!price_sold) throw null;
                    this.$api.patch('api/sold/'+id, {'available': false, 'price_sold': price_sold}).then(response => {
                        swal("Nice job!", "Item was marked as sold.", "success");
                        this.$emit('refreshItems', true);
                    })
                })
                .catch(err => {
                    if (err) {
                        swal("Oh noes!", "The AJAX request failed!", "error");
                    } else {
                        swal.stopLoading();
                        swal.close();
                    }
                });
            }
        },
        watch: {
            update() {
            }
        },
        mounted() {
        }
    }
</script>
