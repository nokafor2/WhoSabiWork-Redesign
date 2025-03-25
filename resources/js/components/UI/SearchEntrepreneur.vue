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

    export default {
        props: ['pageName'],
        emits: ['send-search-result'],
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
                const formData = reactive({
                    searchVal: this.searchVal,
                    pageName: this.pageName,
                });

                // router.post('/api/search', formData, {
                //     preserveState: true, // Prevents a full page reload
                //     preserveScroll: true,
                //     onSuccess: (page) => {
                //         console.log(page.props);
                //         this.$emit('send-search-result', page.props);
                //     },
                //     onError: (errors) => {
                //         console.log('Error: ', errors);
                //     }
                // });
                
                const response = await fetch('/api/search', {
                    method: 'POST',
                    headers: {
                            'Content-Type': 'application/json'
                        },
                    body: JSON.stringify(formData)
                });

                // console.log(response);
                if (response.ok) {
                    const responseData = await response.json();
                    if (responseData) {
                        console.log(responseData);
                        this.$emit('send-search-result', responseData);
                    }
                } else {
                    // There is error
                    console.log('There is an error!');
                }
            }
        },
        watch: {
            searchVal() {
                console.log(this.searchVal);
            }
        }
    }
</script>