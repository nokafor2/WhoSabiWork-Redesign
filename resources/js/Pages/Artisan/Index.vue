<template>
    <h1 class="display-6 text-center">Welcome! Select the artisan category you need</h1>
    <!-- <SearchLayout></SearchLayout> -->
    <search-entrepreneur :pageName="pageName" @send-search-result="updateArtisans"></search-entrepreneur>
    <select-entrepreneur :pageName="pageName" :selectArray="artisanTypes2" @send-category-type="updateArtisans"></select-entrepreneur>
    <div class="row justify-content-evenly">
        <BusinessCard v-for="(artisan, index) in artisans2" :key="index"
            :userId=artisan.id
            :firstName=artisan.first_name
            :lastName=artisan.last_name
            :phoneNumber=artisan.phone_number
            :addressLine1=artisan.address.address_line_1
            :addressLine2=artisan.address.address_line_2
            :addressLine3=artisan.address.address_line_3
            :businessName=artisan.business_category.business_name
        ></BusinessCard>
    </div>
</template>

<script setup>
    // import SearchLayout from '../../components/UI/SearchLayout.vue';
    // import BusinessCard from '../../components/UI/BusinessCard.vue';
    // import {ref} from 'vue';
    // defineProps(['artisans']);
    
    // mounted: {
    //     this.$store.commit('updateArtisans', { value: this.artisans });
    // }
</script>

<script>
    import SearchLayout from '../../components/UI/SearchLayout.vue';
    import SearchEntrepreneur from '../../components/UI/SearchEntrepreneur.vue';
    import SelectEntrepreneur from '../../components/UI/SelectEntrepreneur.vue';
    import BusinessCard from '../../components/UI/BusinessCard.vue';

    export default {
        components: { SearchLayout, SearchEntrepreneur, SelectEntrepreneur, BusinessCard},
        props: ['artisans', 'artisanTypes', 'states'],
        data() {
            return {
                pageName: 'artisan',
                artisans2: [],
                artisanTypes2: this.artisanTypes,
            }
        },
        methods: {
            updateArtisans(artisans) {
                this.artisans2 = artisans;
                this.artisanTypes2 = this.$store.getters.getArtisans;
            }
        },
        provide() {
            return {
                // products: this.artisanTypes
            };
        },
        mounted() {
            this.$store.commit('updateArtisans', { value: this.artisanTypes });
        }
    }
</script>