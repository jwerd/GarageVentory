<template>
    <b-table striped hover :items="items"></b-table>
</template>

<script>
    const items = [
        {
            name: 'This is a random product',
            qty: 5,
            price: 10.00,
            list_price: 20.00,
            dimension: {
                'height': 10,
                'width': 10,
                'length': 10,
            },
            available: true,
        },
    ];

    export default {
        data () {
            return {
                items: items
            }
        }
    }
</script>
