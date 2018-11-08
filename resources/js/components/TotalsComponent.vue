<template>
    <b-card :title="'$'+totalInvestment" bg-variant="light">
        <p class="card-text">{{title}}</p>
    </b-card>
</template>

<script>
    const items = []
    export default {
        data () {
            items: items,
            title: null
        },
        computed: {
            totalProjectedRevenue() {
              return this.items.reduce(function(total, item){
                return total + item.list_price; 
              },0);
            },
            totalInvestment() {
              return this.items.reduce(function(total, item){
                return total + item.price; 
              },0);
            },
            totalProfit() {
              let total = this.items.reduce(function(total, item){
                return total + item.price_sold; 
              },0);
              if(total > this.totalInvestment) {
                  return total-this.totalInvestment;
              }
              return total;
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
