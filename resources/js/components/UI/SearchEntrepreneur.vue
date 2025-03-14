<template>
    <section class="pt-3">
        <!-- <form class="d-flex justify-content-center" >
            <div class="col-10 col-sm-6 col-md-4 me-3">
                <input type="search" class="form-control col-md-6" id="searchInput" placeholder="Search an entrepreneur">
            </div>
            <button class="btn btn-sm btn-outline-success" type="submit">Search</button>
        </form> -->
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
                });

                router.post('api/search', formData, {
                    preserveState: true, // Prevents a full page reload
                    onSuccess: (page) => {
                        console.log(page);
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        watch: {
            searchVal() {
                console.log(this.searchVal);
            }
        }
    }
</script>