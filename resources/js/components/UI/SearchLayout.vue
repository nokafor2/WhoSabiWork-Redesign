<template>
    <search-entrepreneur></search-entrepreneur>
    <select-entrepreneur ></select-entrepreneur>
</template>

<script>
    // import { inject } from 'vue';
    import SearchEntrepreneur from './SearchEntrepreneur.vue';
    import SelectEntrepreneur from './SelectEntrepreneur.vue';


    export default {
        components: { SearchEntrepreneur, SelectEntrepreneur },
        // setup() {
        //     const products = inject('products');

        //     console.log(products);
        //     return { products: products };
        // },
        methods: {
            editProducts() {
                // this.$store.commit('updateProducts', { value: this.products });
                // console.log(this.products);
                this.$store.state.products = this.products;
            }
        }
    }
</script>