<template>
    <b-container fluid>
        <!-- User Interface controls -->
        <b-row>
            <b-col md="6" class="my-1">
                <b-input-group>
                    <b-form-input v-model="filter" placeholder="Type to Search" autocomplete="off" />
                    <b-input-group-append>
                        <b-btn :disabled="!filter" @click="filter = ''">Clear</b-btn>
                    </b-input-group-append>
                </b-input-group>
            </b-col>
            <b-col md="6" class="my-1">
                <b-input-group-append>
                    <b-btn v-model="showSoldItems" @click="soldItemsToggle">{{showSoldItemsLabel}}</b-btn>
                </b-input-group-append>
            </b-col>
        </b-row>

        <weekly-goal :update.sync="updateWeeklyGoal"></weekly-goal>

        <!-- Main table element -->
        <b-table show-empty
                 :striped=false
                 :hover=true
                 :items="items"
                 :fields="fields"
                 :current-page="currentPage"
                 :per-page="perPage"
                 :filter="filter"
                 :sort-by.sync="sortBy"
                 :sort-desc.sync="sortDesc"
                 :sort-direction="sortDirection"
                 @filtered="onFiltered"
        >
            <template slot="name" slot-scope="row"><router-link :to="{ name: 'itemUpdate', params: { id: row.item.id }}">{{row.value | truncate(50)}}</router-link></template>
            <template slot="price" slot-scope="row">${{row.value}}</template>
            <template slot="list_price" slot-scope="row">${{row.value}}</template>
            <template slot="price_sold" slot-scope="row">{{row.value?'$'+row.value:'N/A'}}</template>
            <template slot="dimension" slot-scope="row">
                <span v-show="row.value.height">H{{row.value.height}}"<br /></span>
                <span v-show="row.value.depth">D{{row.value.depth}}"<br /></span>
                <span v-show="row.value.length">L{{row.value.length}}"</span>
            </template>
            <template slot="available" slot-scope="row">{{row.value?'Available':'Not Available'}}
            </template>
            <template slot="soldaction" slot-scope="row">
                <MarkItemSold :item="row.item" @refreshItems="getItems"></MarkItemSold>
            </template>
            <template slot="removeaction" slot-scope="row">
                <b-button class="btn-danger" size="sm" @click.stop="removeItem(row.item.id)">
                    Remove
                </b-button>
            </template>
            <template slot="row-details" slot-scope="row">
                <b-card>
                    <ul>
                        <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value}}</li>
                    </ul>
                </b-card>
            </template>
        </b-table>

        <b-row>
            <b-col md="8" class="my-1">
                <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
            </b-col>
            <b-col md="4" class="my-1">
                <div v-if="!showSoldItems">
                    <b-card-group class="mb-2">
                        <StatsCard :items="items" title="Current Investment" type="totalInvestment"></StatsCard>
                        <StatsCard :items="items" title="Projected Revenue" type="totalProjectedRevenue"></StatsCard>
                    </b-card-group>
                </div>
                <div v-else>
                    <b-card-group class="mb-2">
                        <StatsCard :items="items" title="Current Investment" type="totalInvestment"></StatsCard>
                        <StatsCard :items="items" title="Profit" type="totalProfit"></StatsCard>
                    </b-card-group>
                </div>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    import WeeklyGoal from "../components/WeeklyGoal"
    import StatsCard from "../components/StatsCard"
    import MarkItemSold from "../components/MarkItemSold"

    const items = []

    export default {
        isBusy: false,
        components: {WeeklyGoal, StatsCard, MarkItemSold},
        data () {
            return {
                updateWeeklyGoal: false,
                items: items,
                fields:[],
                currentPage: 1,
                perPage: 15,
                totalRows: items.length,
                sortBy: null,
                sortDesc: false,
                sortDirection: 'asc',
                showSoldItems: false,
                showSoldItemsLabel: 'Switch to Sold Items',
                filter: null,
                modalInfo: { title: '', content: '' }
            }
        },
        computed: {
            sortOptions () {
                // Create an options list from our fields
                return this.fields
                    .filter(f => f.sortable)
                    .map(f => { return { text: f.label, value: f.key } })
            }
        },
        methods: {
            resetModal () {
                this.modalInfo.title = ''
                this.modalInfo.content = ''
            },
            onFiltered (filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            soldItemsToggle () {
                this.showSoldItems = !this.showSoldItems;
                if(this.showSoldItems) {
                    this.fields = [
                        { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
                        { key: 'dimension', label: 'Dimensions (h/d/l)',  sortable: false,  'class': 'text-center' },
                        //{ key: 'price', label: 'Purchase Price', sortable: true, 'class': 'text-center' },
                        { key: 'price_sold', label: 'Sold Price', sortable: true, 'class': 'text-center' },
                        //{ key: 'available', label: 'In Stock',  sortable: true },
                        { key: 'removeaction', label: 'Actions' }
                    ];
                    this.showSoldItemsLabel = 'Switch to Available Items';
                } else {
                    this.fields = this.defaultFields;
                    this.showSoldItemsLabel = 'Switch to Sold Items';
                }
                this.getItems()
            },
            getItems () {
                let endpoint = 'api/item';
                if(this.showSoldItems) {
                    endpoint += '?showSoldItems=1';
                }
                this.$api.get(endpoint).then(response => {
                    this.items = response.data;
                    console.log('getting all the items');
                    this.updateWeeklyGoal = !this.updateWeeklyGoal
                }).catch(err => {
                    if(err) {
                        console.log(err);
                    }
                });
            },
            removeItem(id) {
                if(confirm('Are you sure you want to remove this item?')) {
                    this.$api.delete('/api/item/'+id, {'available': false}).then(response => {
                        this.$noty.success("Item deleted successfully...")
                        this.getItems();
                    })
                }
            },
        },
        mounted() {
            /*
            this.app.$on('refreshItems', () => {
                console.log('refreshing items');
                this.getItems();
            });*/
            this.defaultFields = this.fields = [
                { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
                { key: 'dimension', label: 'Dimensions (h/d/l)',  sortable: false,  'class': 'text-center' },
                //{ key: 'price', label: 'Purchase Price', sortable: true, 'class': 'text-center' },
                //{ key: 'list_price', label: 'List Price', sortable: true, 'class': 'text-center' },
                //{ key: 'available', label: 'In Stock',  sortable: true },
                { key: 'soldaction', label: 'Actions' }
            ]
            this.getItems()
        }
    }
</script>
