<template>
    <h1 class="display-6 text-center">Welcome! Select the seller category you need</h1>
    <!-- <SearchLayout></SearchLayout> -->
    <search-entrepreneur :pageName="pageName" @send-search-result="updateMobileMarketers" @search-started="startSearch"></search-entrepreneur>
    <select-entrepreneur :pageName="pageName" :selectArray="products2" @send-category-type="updateMobileMarketers" @search-started="startSearch"></select-entrepreneur>
    <!-- Results section -->
    <div v-if="mobileMarketers2Len" class="row justify-content-evenly">
        <BusinessCard v-for="marketer in mobileMarketers2" :key="marketer"
            :userId=marketer.id
            :firstName=marketer.first_name
            :lastName=marketer.last_name
            :phoneNumber=marketer.phone_number
            :addressLine1=marketer.address.address_line_1
            :addressLine2=marketer.address.address_line_2
            :addressLine3=marketer.address.address_line_3
            :businessName=marketer.business_category.business_name
            :coverPhoto=marketer.cover_photo
        ></BusinessCard>
    </div>
    
    <!-- Empty state message -->
    <div v-else-if="!isSearching" class="row justify-content-center mt-5">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">Ready to Find Mobile Marketers?</h4>
                    <p class="text-muted mb-4">
                        Use the dropdown menus above to select your preferred seller category, location, and other criteria. 
                        Then click "Search" to find mobile marketers that match your needs.
                    </p>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-info-circle me-2"></i>
                            Start by selecting a seller type from the first dropdown menu
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
            <p class="mt-3 text-muted">Searching for mobile marketers...</p>
        </div>
    </div>
</template>

<script >
    import SearchLayout from '../../components/UI/SearchLayout.vue';
    import SearchEntrepreneur from '../../components/UI/SearchEntrepreneur.vue';
    import SelectEntrepreneur from '../../components/UI/SelectEntrepreneur.vue';
    import BusinessCard from '../../components/UI/BusinessCard.vue';
    import { router, useForm, usePage } from '@inertiajs/vue3';

    // this.editProducts;
    export default {
        components: { SearchLayout, SearchEntrepreneur, SelectEntrepreneur, BusinessCard},
        props: ['mobileMarketers', 'products'],
        data() {
            return {
                pageName: 'seller',
                mobileMarketers2: [], // Simple array for search results
                products2: this.products,
                isSearching: false, // Track if a search/filter is in progress
                page: usePage(),
            }
        },
        computed: {
            mobileMarketers2Len() {
                return Object.keys(this.mobileMarketers2).length;
            }
        },
        methods: {
            updateMobileMarketers(pageProps) {
                console.log('🔄 updateMobileMarketers called with:', pageProps);
                
                // End loading state
                this.isSearching = false;
                
                // Handle different data structures from Axios response
                let searchResults = null;
                
                if (pageProps && pageProps.data) {
                    // Direct data from Axios response
                    console.log('✅ Setting mobileMarketers2 from Axios response data');
                    searchResults = pageProps.data;
                } else if (pageProps && pageProps.flash && pageProps.flash.success) {
                    // Fallback to flash session data (if still using redirect)
                    console.log('✅ Setting mobileMarketers2 from flash.success');
                    searchResults = pageProps.flash.success;
                } else if (pageProps && pageProps.mobileMarketers) {
                    // From direct Inertia response
                    console.log('✅ Setting mobileMarketers2 from direct mobileMarketers');
                    searchResults = pageProps.mobileMarketers;
                    
                    // Update products if provided
                    if (pageProps.products) {
                        this.products2 = pageProps.products;
                    }
                } else if (Array.isArray(pageProps)) {
                    // From dropdown selection (direct array)
                    console.log('✅ Setting mobileMarketers2 from array');
                    searchResults = pageProps;
                } else {
                    console.warn('❌ No valid data found, clearing mobileMarketers2');
                    searchResults = [];
                }
                
                if (searchResults) {
                    console.log('✅ Search results found:', searchResults);
                    console.log('✅ Results length:', searchResults.length);
                    this.mobileMarketers2 = searchResults;
                    console.log('✅ mobileMarketers2 updated:', this.mobileMarketers2);
                } else {
                    console.warn('❌ No search results found');
                    this.mobileMarketers2 = [];
                }
            },
            
            startSearch() {
                // Set loading state when search/filter starts
                this.isSearching = true;
            }
        },
        provide() {
            return {
                // products: this.products
            };
        },
        mounted() {
            console.log('🚀 MobileMarket component mounted');
            console.log('📋 Props received:', {
                mobileMarketers: this.mobileMarketers,
                mobileMarketers_length: this.mobileMarketers ? this.mobileMarketers.length : 0,
                products: this.products
            });
            
            // Initialize mobileMarketers2 from props if available
            if (this.mobileMarketers && Array.isArray(this.mobileMarketers) && this.mobileMarketers.length > 0) {
                console.log('✅ Initializing mobileMarketers2 from props');
                this.mobileMarketers2 = this.mobileMarketers;
            } else {
                console.log('ℹ️ No initial mobileMarketers data, starting with empty array');
                this.mobileMarketers2 = [];
            }
            
            // Update store with products
            if (this.$store && this.$store.commit) {
                this.$store.commit('updateProducts', { value: this.products });
            }
            
            console.log('🏁 Final mobileMarketers2 after mount:', this.mobileMarketers2);
            console.log('🏁 mobileMarketers2 length:', this.mobileMarketers2.length);
        }
    }
</script>