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
                 stacked="md"
                 :striped=true
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
            <template slot="name" slot-scope="row">{{row.value}}</template>
            <template slot="price" slot-scope="row">${{row.value}}</template>
            <template slot="list_price" slot-scope="row">${{row.value}}</template>
            <template slot="dimension" slot-scope="row">{{row.value.height}}/{{row.value.width}}/{{row.value.depth}}</template>
            <template slot="available" slot-scope="row">{{row.value?'Available':'Not Available'}}</template>
            <template slot="actions" slot-scope="row">
                <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
                <!--<b-button size="sm" @click.stop="info(row.item, row.index, $event.target)" class="mr-1">-->
                    <!--Info modal-->
                <!--</b-button>-->
                <b-button size="sm" @click.stop="row.toggleDetails">
                    Edit
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
        </b-row>

        <!-- Info modal -->
        <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
            <pre>{{ modalInfo.content }}</pre>
        </b-modal>

    </b-container>
</template>

<script>
    const items = []
    const olditems = [
        {
            name: 'Random Product 1',
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
        {
            name: 'Random Product 2',
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
        {
            name: 'Random Product 3',
            qty: 5,
            price: 10.00,
            list_price: 20.00,
            dimension: {
                'height': 10,
                'width': 10,
                'length': 10,
            },
            available: false,
        },
        {
            name: 'Random Product 4',
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
        {
            name: 'Random Product 5,',
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
        {
            name: 'Random Product 6',
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

    ]

    export default {
        isBusy: false,
        data () {
            return {
                items: items,
                fields: [
                    { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
                    { key: 'qty', label: 'Quantity', sortable: true, 'class': 'text-center' },
                    { key: 'dimension', label: 'Dimension (h/w/l)',  sortable: true,  'class': 'text-center' },
                    { key: 'price', label: 'My Price', sortable: true, 'class': 'text-center' },
                    { key: 'list_price', label: 'List Price', sortable: true, 'class': 'text-center' },
                    { key: 'available', label: 'In Stock',  sortable: true },
                ],
                currentPage: 1,
                perPage: 15,
                filterShowing: [
                    {key: 1, value: 'Only Show Stuff I have'},
                    {key: 2, value: 'Show All'}
                ],
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
                    this.showSoldItemsLabel = 'Switch to Unsold Items';
                } else {
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
            }
        },
        mounted() {
            this.getItems()
        },
        beforeRouteEnter (to, from, next) {
            if (localStorage.getItem('jwt') === null) {
                next('/login');
            }

            next();
        }
    }
</script>
