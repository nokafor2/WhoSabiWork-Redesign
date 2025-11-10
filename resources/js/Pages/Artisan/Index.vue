<template>
    <h1 class="display-6 text-center">Welcome! Select the artisan category you need</h1>
    <!-- <SearchLayout></SearchLayout> -->
    <search-entrepreneur :pageName="pageName" @send-search-result="updateSearchedArtisans" @search-started="startSearch"></search-entrepreneur>
    <select-entrepreneur :pageName="pageName" :selectArray="artisanTypes2" @send-category-type="updateArtisans" @search-started="startSearch"></select-entrepreneur>
    
    <!-- Results section -->
    <div v-if="artisans2Len > 0" class="row justify-content-evenly">
        <BusinessCard v-for="(artisan, index) in artisans2" :key="index"
            :userId=artisan.id
            :firstName=artisan.first_name
            :lastName=artisan.last_name
            :phoneNumber=artisan.phone_number
            :addressLine1=artisan.address.address_line_1
            :addressLine2=artisan.address.address_line_2
            :addressLine3=artisan.address.address_line_3
            :businessName=artisan.business_category.business_name
            :coverPhoto=artisan.cover_photo
        ></BusinessCard>
    </div>
    
    <!-- Empty state message -->
    <div v-else-if="!isSearching" class="row justify-content-center mt-5">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">Ready to Find Skilled Artisans?</h4>
                    <p class="text-muted mb-4">
                        Use the dropdown menus above to select your preferred artisan category, services, location, and other criteria. 
                        Then click "Search" to find skilled artisans that match your needs.
                    </p>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-info-circle me-2"></i>
                            Start by selecting an artisan type from the first dropdown menu
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loading state -->
    <div v-else class="row justify-content-center mt-5">
        <div class="col-md-6 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Searching...</span>
            </div>
            <p class="mt-3 text-muted">Searching for skilled artisans...</p>
        </div>
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
    import { router, useForm, usePage } from '@inertiajs/vue3';

    export default {
        components: { SearchLayout, SearchEntrepreneur, SelectEntrepreneur, BusinessCard},
        props: ['artisans', 'artisanTypes', 'states'],
        data() {
            return {
                pageName: 'artisan',
                artisanTypes2: this.artisanTypes,
                isSearching: false, // Track if a search/filter is in progress
                artisans2: [], // Simple array for search results
                page: usePage(),
            }
        },
        methods: {
            updateArtisans(pageProps) {
                console.log('🔄 updateArtisans called with:', pageProps);
                
                // End loading state
                this.isSearching = false;
                
                // Simple direct assignment - handle different data sources
                if (pageProps && pageProps.flash && pageProps.flash.success) {
                    // From search results (flash session)
                    this.artisans2 = pageProps.flash.success;
                } else if (pageProps && pageProps.artisans) {
                    // From direct Inertia response
                    this.artisans2 = pageProps.artisans;
                    
                    // Update artisan types if provided
                    if (pageProps.artisanTypes) {
                        this.artisanTypes2 = pageProps.artisanTypes;
                    }
                } else if (Array.isArray(pageProps)) {
                    // From dropdown selection (direct array)
                    this.artisans2 = pageProps;
                } else {
                    this.artisans2 = [];
                }
            },

            updateSearchedArtisans(pageProps) {
                // End loading state
                this.isSearching = false;
                
                // Handle different data structures from Axios response
                let searchResults = null;
                
                if (pageProps && pageProps.data) {
                    // Direct data from Axios response
                    searchResults = pageProps.data;
                } else if (pageProps && pageProps.flash && pageProps.flash.success) {
                    // Fallback to flash session data (if still using redirect)
                    searchResults = pageProps.flash.success;
                } else if (Array.isArray(pageProps)) {
                    // Direct array (fallback)
                    searchResults = pageProps;
                }
                
                if (searchResults) {
                    this.artisans2 = searchResults;
                } else {
                    this.artisans2 = [];
                }
            },
            
            startSearch() {
                // Set loading state when search/filter starts
                this.isSearching = true;
            }
        },

        computed: {
            artisans2Len() {
                return Object.keys(this.artisans2).length;
            }
        },


        provide() {
            return {
                // products: this.artisanTypes
            };
        },
        mounted() {
            // Initialize artisans2 from props if available
            if (this.artisans && Array.isArray(this.artisans) && this.artisans.length > 0) {
                this.artisans2 = this.artisans;
            } else {
                this.artisans2 = [];
            }
            
            // Update store with artisan types
            if (this.$store && this.$store.commit) {
                this.$store.commit('updateArtisanTypes', { value: this.artisanTypes });
            }
            
            console.log('🏁 Final artisans2 after mount:', this.artisans2);
            console.log('🏁 artisans2 length:', this.artisans2.length);
        }
    }
</script>