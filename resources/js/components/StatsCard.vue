<template>
    <b-card :title="'$'+this.switcher(type)" bg-variant="light">
        <p class="card-text">{{title}}</p>
    </b-card>
</template>

<script>
    export default {
        isBusy: false,
        name: "StatsCard",
        props: ['type', 'title', 'items'],
        methods: {
            switcher(type) {
                switch(type)
                {
                    case "totalProjectedRevenue":
                        return this.totalProjectedRevenue;
                        break;
                    case "totalInvestment":
                        return this.totalInvestment;
                        break;
                    case "totalProfit":
                        return this.totalProfit;
                        break;
                }
            }
        },
        computed: {
            totalProjectedRevenue() {
                let total = this.items.reduce(function(total, item){
                    return total + item.list_price; 
                },0);
                if(total > this.totalInvestment) {
                    return total-this.totalInvestment;
                }
                return total;
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
            },
        }
    }
</script>
