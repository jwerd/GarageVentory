<template>
        <div>
            <h5>Weekly Goal</h5>
            <b-progress height="2em" :show-value="false" :max="weeklyStats.max_total" show-progress>
                <b-progress-bar variant="success" :show-value="false" :value="weeklyStats.total" animated>
                    <template v-if="weeklyStats.total > 0">${{weeklyStats.total}} earned</template>
                </b-progress-bar>
            </b-progress>
            <div align="right">
                <template v-if="!weeklyStats.metThreshold">Left to make for the week: ${{leftOfWeeklyGoal}}</template>
                <template v-else><strong>You've reached your goal! YAY!</strong></template>
            </div>
        </div>
</template>

<script>
    export default {
        isBusy: false,
        name: "WeeklyGoal",
        props: ['update'],
        data () {
            return {
                weeklyStats: {
                    total: 0,
                    max_total: 0,
                    metThreshold: false,
                }
            }
        },
        computed: {
            leftOfWeeklyGoal() {
                if(this.weeklyStats.max_total > this.weeklyStats.total) {
                    return this.weeklyStats.max_total-this.weeklyStats.total;
                }
                return 0;
            },
        },
        methods: {
            checkWeeklyGoal () {
                let endpoint = 'api/weeklyRevenueCheck';
                this.$api.get(endpoint).then(response => {
                    this.weeklyStats = response.data;
                }).catch(err => {
                    if(err) {
                        console.log('Unauthorized');
                    }
                });
            },
        },
        watch: {
            update() {
                this.checkWeeklyGoal()
            }
        },
        mounted() {
        }
    }
</script>
