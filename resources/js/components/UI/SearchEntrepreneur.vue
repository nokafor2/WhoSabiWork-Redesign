<template>
    <section class="pt-3">
        <form class="row justify-content-center" @submit.prevent="submitForm">
            <div class="col-12 col-sm-6 col-md-6 align-items-center">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search an entrepreneur" aria-label="Search an entrepreneur" aria-describedby="button-addon2" v-model.trim="searchVal"  @input="sendSearchVal">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                </div>
            </div>
        </form>
    </section>
</template>

<script>
    // import functions for use
    import { reactive } from 'vue';
    // import { route } from 'Ziggy';
    import { router, useForm, usePage } from '@inertiajs/vue3';
    // import { route } from 'vendor/tightenco/ziggy/src/js';
    import axios from 'axios';

    export default {
        props: ['pageName'],
        emits: ['send-search-result', 'search-started'],
        data() {
            return {
                searchVal: ""
            }
        },
        methods: {
            // sendSearchVal() {
            //     console.log(this.searchVal);
            // },
            async submitForm() {
                // Ensure CSRF token is available
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('Search CSRF Token found:', csrfToken ? 'Yes' : 'No');

                if (!csrfToken) {
                    console.error('❌ CSRF token not found');
                    alert('Security token missing. Please refresh the page and try again.');
                    return;
                }

                // Emit search started event
                this.$emit('search-started');

                console.log('Starting search request...', {
                    searchVal: this.searchVal,
                    pageName: this.pageName
                });

                try {
                    const response = await axios.post(route('search.store'), {
                        searchVal: this.searchVal.trim(),
                        pageName: this.pageName,
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    });

                    console.log('🔍 SearchEntrepreneur - Search success response:', response);
                    console.log('🔍 SearchEntrepreneur - Response data:', response.data);

                    if (response.data) {
                        console.log('✅ Search results received:', response.data);
                        this.$emit('send-search-result', response.data);
                    } else {
                        console.warn('❌ No search results in response');
                        this.$emit('send-search-result', { flash: { success: [] } });
                    }

                } catch (error) {
                    console.error('❌ Search error:', error);
                    
                    if (error.response) {
                        // Server responded with error status
                        if (error.response.status === 419) {
                            alert('Session expired. Please refresh the page and try again.');
                        } else if (error.response.status === 422) {
                            alert('Invalid search data. Please check your input and try again.');
                        } else if (error.response.status === 500) {
                            alert('Server error. Please try again later.');
                        } else {
                            alert(`Search failed (${error.response.status}). Please try again.`);
                        }
                    } else if (error.request) {
                        // Request was made but no response received
                        alert('No response from server. Please check your connection and try again.');
                    } else {
                        // Something else happened
                        alert('Search failed. Please try again.');
                    }
                    
                    // Emit empty results on error
                    this.$emit('send-search-result', { flash: { success: [] } });
                }
            },

            // submitForm() {
            //     // Ensure CSRF token is available
            //     const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            //     console.log('Search CSRF Token found:', csrfToken ? 'Yes' : 'No');

            //     const formData = useForm({
            //         searchVal: this.searchVal.trim(),
            //         pageName: this.pageName,
            //     });

            //     console.log('Starting search request...', {
            //         searchVal: this.searchVal,
            //         pageName: this.pageName
            //     });

            //     // Emit search started event
            //     this.$emit('search-started');

            //     formData.post(route('search.store'), {
            //         preserveState: true, // Prevents a full page reload
            //         preserveScroll: true,
            //         headers: {
            //             'X-CSRF-TOKEN': csrfToken,
            //             'X-Requested-With': 'XMLHttpRequest'
            //         },
            //         onSuccess: (page) => {
            //             console.log('🔍 SearchEntrepreneur - Search success full response:', page);

            //             // Check if we have search results in flash data
            //             if (page.props?.flash?.success) {
            //                 console.log('✅ Search results found in flash.success, length:', page.props.flash.success.length);
            //                 console.log('✅ First result:', page.props.flash.success[0]);
            //             } else {
            //                 console.warn('❌ No search results found in flash.success');
            //                 console.warn('❌ Flash data structure:', page.props?.flash);
            //             }

            //             console.log('📤 SearchEntrepreneur - Emitting send-search-result with:', page.props);
            //             this.$emit('send-search-result', page.props);
            //         },
            //         onError: (errors) => {
            //             console.log('Search error:', errors);
                        
            //             // Show user-friendly error message
            //             if (errors.status === 419) {
            //                 alert('Session expired. Please refresh the page and try again.');
            //             } else {
            //                 alert('Search failed. Please try again.');
            //             }
            //         }
            //     });
            // }
        },
        watch: {
            // searchVal() {
            //     console.log(this.searchVal);
            // }
        }
    }
</script>