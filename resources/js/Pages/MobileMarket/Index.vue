<template>
    <h1 class="display-6 text-center">Welcome! Select the seller category you need</h1>
    <!-- <SearchLayout></SearchLayout> -->
    <search-entrepreneur :pageName="pageName" @send-search-result="updateMobileMarketers" @search-started="startSearch" @update-search-params="storeSearchParams"></search-entrepreneur>
    <select-entrepreneur :pageName="pageName" :selectArray="products2" @send-category-type="updateMobileMarketers" @search-started="startSearch" @update-search-params="storeSearchParams"></select-entrepreneur>
    <!-- Results section -->
    <div v-if="mobileMarketers2Len" class="row justify-content-evenly">
        <BusinessCard v-for="marketer in mobileMarketers2" :key="marketer.id"
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
    
    <!-- Loading more indicator -->
    <div v-if="isLoadingMore" class="row justify-content-center mt-4 mb-4">
        <div class="col-md-6 text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading more...</span>
            </div>
            <p class="mt-2 text-muted small">Loading more mobile marketers...</p>
        </div>
    </div>
    
    <!-- Empty state message - Only show when not searching AND no results -->
    <div v-else-if="!isSearching && mobileMarketers2Len === 0" class="row justify-content-center mt-5">
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
    <div v-else-if="isSearching" class="row justify-content-center mt-5">
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
    import axios from 'axios';

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
                isLoadingMore: false, // Track if loading more data
                page: usePage(),
                currentPage: 1,
                lastPage: 1,
                nextPageUrl: null,
                lastSearchParams: null, // Store last search parameters for pagination
                isSearchMode: false, // Track if results are from search or filter
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
                this.isLoadingMore = false;
                
                // Check if this is search result (has data.data structure) or filter result (has mobileMarketers structure)
                if (pageProps && pageProps.data && pageProps.data.data) {
                    // This is a search result
                    this.isSearchMode = true;
                    console.log('✅ Setting mobileMarketers2 from search paginated response');
                    this.mobileMarketers2 = pageProps.data.data;
                    this.currentPage = pageProps.data.current_page || 1;
                    this.lastPage = pageProps.data.last_page || 1;
                    this.nextPageUrl = pageProps.data.next_page_url;
                    return;
                }
                
                // Mark as filter mode (not search)
                this.isSearchMode = false;
                
                // Handle paginated data structure
                if (pageProps && pageProps.mobileMarketers && pageProps.mobileMarketers.data) {
                    // Paginated response from backend
                    console.log('✅ Setting mobileMarketers2 from paginated response');
                    this.mobileMarketers2 = pageProps.mobileMarketers.data;
                    this.currentPage = pageProps.mobileMarketers.current_page || 1;
                    this.lastPage = pageProps.mobileMarketers.last_page || 1;
                    this.nextPageUrl = pageProps.mobileMarketers.next_page_url;
                    
                    // Update products if provided
                    if (pageProps.products) {
                        this.products2 = pageProps.products;
                    }
                } else if (pageProps && pageProps.flash && pageProps.flash.success) {
                    // From search results (flash session) - non-paginated
                    console.log('✅ Setting mobileMarketers2 from flash.success');
                    this.mobileMarketers2 = pageProps.flash.success;
                    this.resetPagination();
                } else if (pageProps && pageProps.data) {
                    // Check if paginated
                    if (pageProps.data.data) {
                        console.log('✅ Setting mobileMarketers2 from Axios paginated response');
                        this.mobileMarketers2 = pageProps.data.data;
                        this.currentPage = pageProps.data.current_page || 1;
                        this.lastPage = pageProps.data.last_page || 1;
                        this.nextPageUrl = pageProps.data.next_page_url;
                    } else {
                        // Direct data from Axios response
                        console.log('✅ Setting mobileMarketers2 from Axios response data');
                        this.mobileMarketers2 = pageProps.data;
                        this.resetPagination();
                    }
                } else if (pageProps && pageProps.mobileMarketers) {
                    // From direct Inertia response - check if paginated
                    if (pageProps.mobileMarketers.data) {
                        console.log('✅ Setting mobileMarketers2 from direct paginated mobileMarketers');
                        this.mobileMarketers2 = pageProps.mobileMarketers.data;
                        this.currentPage = pageProps.mobileMarketers.current_page || 1;
                        this.lastPage = pageProps.mobileMarketers.last_page || 1;
                        this.nextPageUrl = pageProps.mobileMarketers.next_page_url;
                    } else {
                        console.log('✅ Setting mobileMarketers2 from direct mobileMarketers');
                        this.mobileMarketers2 = pageProps.mobileMarketers;
                        this.resetPagination();
                    }
                    
                    // Update products if provided
                    if (pageProps.products) {
                        this.products2 = pageProps.products;
                    }
                } else if (Array.isArray(pageProps)) {
                    // From dropdown selection (direct array) - non-paginated
                    console.log('✅ Setting mobileMarketers2 from array');
                    this.mobileMarketers2 = pageProps;
                    this.resetPagination();
                } else {
                    console.warn('❌ No valid data found, clearing mobileMarketers2');
                    this.mobileMarketers2 = [];
                    this.resetPagination();
                }
                
                console.log('✅ mobileMarketers2 updated:', this.mobileMarketers2);
                console.log('✅ Results length:', this.mobileMarketers2.length);
            },
            
            startSearch() {
                // Set loading state when search/filter starts
                this.isSearching = true;
                // Reset pagination when starting new search
                this.resetPagination();
            },
            
            storeSearchParams(params) {
                // Store search params for pagination
                this.lastSearchParams = params;
                console.log('📦 Search params stored for pagination:', params);
            },
            
            resetPagination() {
                this.currentPage = 1;
                this.lastPage = 1;
                this.nextPageUrl = null;
            },
            
            handleScroll() {
                // Check if user has scrolled to bottom
                const bottomOfWindow = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100;
                
                if (bottomOfWindow && this.hasMorePages() && !this.isLoadingMore && !this.isSearching) {
                    // Load more based on whether we're in search mode or filter mode
                    if (this.isSearchMode) {
                        this.loadMoreSearchResults();
                    } else {
                        this.loadMoreMarketers();
                    }
                }
            },
            
            hasMorePages() {
                return this.currentPage < this.lastPage && this.nextPageUrl !== null;
            },
            
            loadMoreMarketers() {
                if (!this.hasMorePages() || this.isLoadingMore) {
                    return;
                }
                
                this.isLoadingMore = true;
                const nextPage = this.currentPage + 1;
                
                // Store current search params from last search (set by SelectEntrepreneur)
                if (!this.lastSearchParams) {
                    console.log('⚠️ No search params available for pagination');
                    this.isLoadingMore = false;
                    return;
                }
                
                // Add page parameter to search params
                const params = { ...this.lastSearchParams, page: nextPage };
                
                router.post('/mobile-marketers', params, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.mobileMarketers && page.props.mobileMarketers.data) {
                            // Append new mobile marketers to existing list
                            this.mobileMarketers2 = [...this.mobileMarketers2, ...page.props.mobileMarketers.data];
                            this.currentPage = page.props.mobileMarketers.current_page;
                            this.lastPage = page.props.mobileMarketers.last_page;
                            this.nextPageUrl = page.props.mobileMarketers.next_page_url;
                            
                            // Update Vuex store with appended data
                            this.$store.dispatch('appendMobileMarketers', { value: page.props.mobileMarketers.data });
                        }
                        this.isLoadingMore = false;
                    },
                    onError: (errors) => {
                        console.log('Pagination error:', errors);
                        this.isLoadingMore = false;
                    }
                });
            },
            
            async loadMoreSearchResults() {
                if (!this.hasMorePages() || this.isLoadingMore) {
                    return;
                }
                
                this.isLoadingMore = true;
                const nextPage = this.currentPage + 1;
                
                // Store current search params from last search (set by SearchEntrepreneur)
                if (!this.lastSearchParams) {
                    console.log('⚠️ No search params available for pagination');
                    this.isLoadingMore = false;
                    return;
                }
                
                // Add page parameter to search params
                const params = { ...this.lastSearchParams, page: nextPage };
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                try {
                    const response = await axios.post(route('search.store'), params, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    if (response.data && response.data.data && response.data.data.data) {
                        // Append new mobile marketers to existing list
                        this.mobileMarketers2 = [...this.mobileMarketers2, ...response.data.data.data];
                        this.currentPage = response.data.data.current_page;
                        this.lastPage = response.data.data.last_page;
                        this.nextPageUrl = response.data.data.next_page_url;
                        
                        // Update Vuex store with appended search results
                        this.$store.dispatch('appendMobileMarketers', { value: response.data.data.data });
                    }
                    this.isLoadingMore = false;
                } catch (error) {
                    console.log('Search pagination error:', error);
                    this.isLoadingMore = false;
                }
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
            if (this.mobileMarketers) {
                // Check if paginated data
                if (this.mobileMarketers.data && Array.isArray(this.mobileMarketers.data)) {
                    console.log('✅ Initializing mobileMarketers2 from paginated props');
                    this.mobileMarketers2 = this.mobileMarketers.data;
                    this.currentPage = this.mobileMarketers.current_page || 1;
                    this.lastPage = this.mobileMarketers.last_page || 1;
                    this.nextPageUrl = this.mobileMarketers.next_page_url;
                } else if (Array.isArray(this.mobileMarketers) && this.mobileMarketers.length > 0) {
                    console.log('✅ Initializing mobileMarketers2 from props');
                    this.mobileMarketers2 = this.mobileMarketers;
                } else {
                    console.log('ℹ️ No initial mobileMarketers data, starting with empty array');
                    this.mobileMarketers2 = [];
                }
            } else {
                console.log('ℹ️ No initial mobileMarketers data, starting with empty array');
                this.mobileMarketers2 = [];
            }
            
            // Update store with products
            if (this.$store && this.$store.commit) {
                this.$store.commit('updateProducts', { value: this.products });
            }
            
            // Add scroll event listener for infinite scrolling
            window.addEventListener('scroll', this.handleScroll);
            
            console.log('🏁 Final mobileMarketers2 after mount:', this.mobileMarketers2);
            console.log('🏁 mobileMarketers2 length:', this.mobileMarketers2.length);
            console.log('🏁 Pagination info - Current:', this.currentPage, 'Last:', this.lastPage);
        },
        
        beforeUnmount() {
            // Remove scroll event listener
            window.removeEventListener('scroll', this.handleScroll);
        }
    }
</script>