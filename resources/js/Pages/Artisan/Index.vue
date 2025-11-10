<template>
    <h1 class="display-6 text-center">Welcome! Select the artisan category you need</h1>
    <!-- <SearchLayout></SearchLayout> -->
    <search-entrepreneur :pageName="pageName" @send-search-result="updateSearchedArtisans" @search-started="startSearch" @update-search-params="storeSearchParams"></search-entrepreneur>
    <select-entrepreneur :pageName="pageName" :selectArray="artisanTypes2" @send-category-type="updateArtisans" @search-started="startSearch" @update-search-params="storeSearchParams"></select-entrepreneur>
    
    <!-- Results section -->
    <div v-if="artisans2Len > 0" class="row justify-content-evenly">
        <BusinessCard v-for="(artisan, index) in artisans2" :key="artisan.id || index"
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
    
    <!-- Loading more indicator -->
    <div v-if="isLoadingMore" class="row justify-content-center mt-4 mb-4">
        <div class="col-md-6 text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading more...</span>
            </div>
            <p class="mt-2 text-muted small">Loading more artisans...</p>
        </div>
    </div>
    
    <!-- Empty state message - Only show when not searching AND no results -->
    <div v-else-if="!isSearching && artisans2Len === 0" class="row justify-content-center mt-5">
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
    <div v-else-if="isSearching" class="row justify-content-center mt-5">
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
    import axios from 'axios';

    export default {
        components: { SearchLayout, SearchEntrepreneur, SelectEntrepreneur, BusinessCard},
        props: ['artisans', 'artisanTypes', 'states'],
        data() {
            return {
                pageName: 'artisan',
                artisanTypes2: this.artisanTypes,
                isSearching: false, // Track if a search/filter is in progress
                isLoadingMore: false, // Track if loading more data
                artisans2: [], // Simple array for search results
                page: usePage(),
                currentPage: 1,
                lastPage: 1,
                nextPageUrl: null,
                lastSearchParams: null, // Store last search parameters for pagination
                isSearchMode: false, // Track if results are from search or filter
            }
        },
        methods: {
            updateArtisans(pageProps) {
                console.log('🔄 updateArtisans called with:', pageProps);
                
                // End loading state
                this.isSearching = false;
                this.isLoadingMore = false;
                
                // Mark as filter mode (not search)
                this.isSearchMode = false;
                
                // Handle paginated data structure
                if (pageProps && pageProps.artisans && pageProps.artisans.data) {
                    // Paginated response from backend
                    this.artisans2 = pageProps.artisans.data;
                    this.currentPage = pageProps.artisans.current_page || 1;
                    this.lastPage = pageProps.artisans.last_page || 1;
                    this.nextPageUrl = pageProps.artisans.next_page_url;
                    
                    // Update artisan types if provided
                    if (pageProps.artisanTypes) {
                        this.artisanTypes2 = pageProps.artisanTypes;
                    }
                } else if (pageProps && pageProps.flash && pageProps.flash.success) {
                    // From search results (flash session) - non-paginated
                    this.artisans2 = pageProps.flash.success;
                    this.resetPagination();
                } else if (pageProps && pageProps.artisans) {
                    // From direct Inertia response - check if paginated
                    if (pageProps.artisans.data) {
                        this.artisans2 = pageProps.artisans.data;
                        this.currentPage = pageProps.artisans.current_page || 1;
                        this.lastPage = pageProps.artisans.last_page || 1;
                        this.nextPageUrl = pageProps.artisans.next_page_url;
                    } else {
                        this.artisans2 = pageProps.artisans;
                        this.resetPagination();
                    }
                    
                    // Update artisan types if provided
                    if (pageProps.artisanTypes) {
                        this.artisanTypes2 = pageProps.artisanTypes;
                    }
                } else if (Array.isArray(pageProps)) {
                    // From dropdown selection (direct array) - non-paginated
                    this.artisans2 = pageProps;
                    this.resetPagination();
                } else {
                    this.artisans2 = [];
                    this.resetPagination();
                }
            },

            updateSearchedArtisans(pageProps) {
                // End loading state
                this.isSearching = false;
                this.isLoadingMore = false;
                
                // Mark as search mode
                this.isSearchMode = true;
                
                // Handle different data structures from Axios response
                let searchResults = null;
                
                if (pageProps && pageProps.data) {
                    // Check if paginated
                    if (pageProps.data.data) {
                        searchResults = pageProps.data.data;
                        this.currentPage = pageProps.data.current_page || 1;
                        this.lastPage = pageProps.data.last_page || 1;
                        this.nextPageUrl = pageProps.data.next_page_url;
                    } else {
                        // Direct data from Axios response
                        searchResults = pageProps.data;
                        this.resetPagination();
                    }
                } else if (pageProps && pageProps.flash && pageProps.flash.success) {
                    // Fallback to flash session data (if still using redirect)
                    searchResults = pageProps.flash.success;
                    this.resetPagination();
                } else if (Array.isArray(pageProps)) {
                    // Direct array (fallback)
                    searchResults = pageProps;
                    this.resetPagination();
                }
                
                if (searchResults) {
                    this.artisans2 = searchResults;
                } else {
                    this.artisans2 = [];
                    this.resetPagination();
                }
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
                        this.loadMoreArtisans();
                    }
                }
            },
            
            hasMorePages() {
                return this.currentPage < this.lastPage && this.nextPageUrl !== null;
            },
            
            loadMoreArtisans() {
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
                
                router.post('/artisans', params, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.artisans && page.props.artisans.data) {
                            // Append new artisans to existing list
                            this.artisans2 = [...this.artisans2, ...page.props.artisans.data];
                            this.currentPage = page.props.artisans.current_page;
                            this.lastPage = page.props.artisans.last_page;
                            this.nextPageUrl = page.props.artisans.next_page_url;
                            
                            // Update Vuex store with appended data
                            this.$store.dispatch('appendArtisans', { value: page.props.artisans.data });
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
                        // Append new artisans to existing list
                        this.artisans2 = [...this.artisans2, ...response.data.data.data];
                        this.currentPage = response.data.data.current_page;
                        this.lastPage = response.data.data.last_page;
                        this.nextPageUrl = response.data.data.next_page_url;
                        
                        // Update Vuex store with appended search results
                        this.$store.dispatch('appendArtisans', { value: response.data.data.data });
                    }
                    this.isLoadingMore = false;
                } catch (error) {
                    console.log('Search pagination error:', error);
                    this.isLoadingMore = false;
                }
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
            if (this.artisans) {
                // Check if paginated data
                if (this.artisans.data && Array.isArray(this.artisans.data)) {
                    this.artisans2 = this.artisans.data;
                    this.currentPage = this.artisans.current_page || 1;
                    this.lastPage = this.artisans.last_page || 1;
                    this.nextPageUrl = this.artisans.next_page_url;
                } else if (Array.isArray(this.artisans) && this.artisans.length > 0) {
                    this.artisans2 = this.artisans;
                } else {
                    this.artisans2 = [];
                }
            } else {
                this.artisans2 = [];
            }
            
            // Update store with artisan types
            if (this.$store && this.$store.commit) {
                this.$store.commit('updateArtisanTypes', { value: this.artisanTypes });
            }
            
            // Add scroll event listener for infinite scrolling
            window.addEventListener('scroll', this.handleScroll);
            
            console.log('🏁 Final artisans2 after mount:', this.artisans2);
            console.log('🏁 artisans2 length:', this.artisans2.length);
            console.log('🏁 Pagination info - Current:', this.currentPage, 'Last:', this.lastPage);
        },
        
        beforeUnmount() {
            // Remove scroll event listener
            window.removeEventListener('scroll', this.handleScroll);
        }
    }
</script>