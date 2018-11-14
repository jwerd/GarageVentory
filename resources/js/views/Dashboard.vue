<template>
    <b-container fluid>
        <!-- User Interface controls -->
        <b-row>
            <b-col md="6" class="my-1">
                <b-input-group>
                    <b-form-input v-model="filter" placeholder="Type to Search" />
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

        <!-- Main table element -->
        <b-table show-empty
                 :striped=fase
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
                <b-button class="btn-success" size="sm" @click="markItemSold(row.item.id)">
                    Mark Item Sold
                </b-button>
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
                <div v-show="!showSoldItems">
                    <b-card-group class="mb-2">
                        <b-card :title="'$'+totalInvestment" bg-variant="light">
                            <p class="card-text">Total Current Investment</p>
                        </b-card>
                        <b-card :title="'$'+totalProjectedRevenue+'*'" bg-variant="light">
                            <p class="card-text">Total Projected Revenue</p>
                        </b-card>
                    </b-card-group>
                </div>
                <div v-show="showSoldItems">
                    <b-card-group class="mb-2">
                        <b-card :title="'$'+totalInvestment" bg-variant="light">
                            <p class="card-text">Total Current Investment</p>
                        </b-card>
                        <b-card :title="'$'+totalProfit" bg-variant="light">
                            <p class="card-text">Total Profit</p>
                        </b-card>
                    </b-card-group>
                </div>
            </b-col>
        </b-row>

        <!-- Info modal -->
        <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
            <pre>{{ modalInfo.content }}</pre>
        </b-modal>
    </b-container>
</template>
                Total Project Revenue: $1,500
<script>

    const items = []

    export default {
        isBusy: false,
        data () {
            return {
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
            sortOptions () {
                // Create an options list from our fields
                return this.fields
                    .filter(f => f.sortable)
                    .map(f => { return { text: f.label, value: f.key } })
            }
        },
        methods: {
            info (item, index, button) {
                this.modalInfo.title = `Row index: ${index}`
                this.modalInfo.content = JSON.stringify(item, null, 2)
                this.$root.$emit('bv::show::modal', 'modalInfo', button)
            },
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
                        { key: 'dimension', label: 'Dimension (h/d/l)',  sortable: false,  'class': 'text-center' },
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
                })
            },
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
                        this.getItems();
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
            this.defaultFields = this.fields = [
                { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
                { key: 'dimension', label: 'Dimension (h/d/l)',  sortable: false,  'class': 'text-center' },
                //{ key: 'price', label: 'Purchase Price', sortable: true, 'class': 'text-center' },
                //{ key: 'list_price', label: 'List Price', sortable: true, 'class': 'text-center' },
                //{ key: 'available', label: 'In Stock',  sortable: true },
                { key: 'soldaction', label: 'Actions' }
            ]
            this.getItems()
        }
    }
</script>
