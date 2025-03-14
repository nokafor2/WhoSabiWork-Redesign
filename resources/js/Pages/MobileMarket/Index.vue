<template>
    <h1 class="display-6 text-center">Welcome! Select the seller category you need</h1>
    <!-- <SearchLayout></SearchLayout> -->
    <search-entrepreneur></search-entrepreneur>
    <select-entrepreneur :pageName="pageName" :selectArray="products2"  @send-category-type="updateMobileMarketers"></select-entrepreneur>
    <div class="row justify-content-evenly">
        <BusinessCard v-for="marketer in mobileMarketers2" :key="marketer"
            :firstName=marketer.first_name
            :lastName=marketer.last_name
            :phoneNumber=marketer.phone_number
            :addressLine1=marketer.address.address_line_1
            :addressLine2=marketer.address.address_line_2
            :addressLine3=marketer.address.address_line_3
            :businessName=marketer.business_category.business_name
        ></BusinessCard>
    </div>
</template>

<script >
    import SearchLayout from '../../components/UI/SearchLayout.vue';
    import SearchEntrepreneur from '../../components/UI/SearchEntrepreneur.vue';
    import SelectEntrepreneur from '../../components/UI/SelectEntrepreneur.vue';
    import BusinessCard from '../../components/UI/BusinessCard.vue';

    // this.editProducts;
    export default {
        components: { SearchLayout, SearchEntrepreneur, SelectEntrepreneur, BusinessCard},
        props: ['mobileMarketers', 'products'],
        data() {
            return {
                pageName: 'seller',
                mobileMarketers2: [],
                products2: this.products,
            }
        },
        methods: {
            updateMobileMarketers(mobileMarketers) {
                this.mobileMarketers2 = mobileMarketers;
                this.products2 = this.$store.getters.getProducts;
            }
        },
        provide() {
            return {
                // products: this.products
            };
        },
        mounted() {
            // this.$store.state.sellerProducts = this.products;
            this.$store.commit('updateProducts', { value: this.products });
        }
    }
</script>