<template>
    <section class="mt-3">
        <form class="justify-content-center" @submit.prevent="submitForm">
            <div class="row row-cols-sm-2 row-cols-md-2 row-cols-lg-4">
                <div class="col-md mb-2">
                    <!--  @click="runFunc" -->
                    <select class="form-select" aria-label="Default select example" v-model="selectedOption">
                        <option value="default"  >{{ selectType() }}</option>
                        <option v-for="(option, index) in selectArray" :key="index" :value="index">{{ option }}</option>
                    </select>
                </div>
                <div class="col-md mb-2">
                    <select class="form-select" aria-label="Default select example" v-model="selectedState">
                        <option value="default">Select state</option>
                        <option v-for="(state, index) in states" :key="index" :value="state">{{ state }}</option>
                    </select>
                </div>
                <div class="col-md mb-2">
                    <select class="form-select" aria-label="Default select example" v-model="selectedTown">
                        <option value="default">Select town</option>
                        <option v-for="(town, index) in towns" :key="index" :value="town">{{ town }}</option>
                    </select>
                </div>
                <!-- <div class="col-xs-6 col-md-2 col-lg-1 align-middle"> -->
                <div class="col-md align-middle">
                    <button class="btn btn-sm btn-outline-danger w-100" type="submit">Search</button>
                </div>
            </div>
        </form>
    </section>
</template>

<script>
    // import functions for use
    import { reactive } from 'vue';
    import { router, useForm, usePage } from '@inertiajs/vue3';

    export default {
        // inject: ['products'],
        props: ['pageName', 'selectArray'],
        emits: ['send-artisans'],
        data() {
            return {
                selectedOption: 'default',
                selectedState: 'default',
                selectedTown: 'default',
                states: [],
                towns: [],
                artisans: [],
                mobileMarketers: [],
            }
        },
        methods: {
            runFunc() {
                if (this.pageName == "seller") {
                    // this.selectArray = this.$store.getters.getProducts;

                } else if (this.pageName == "artisan") {
                    // this.selectArray = this.$store.getters.getArtisans;
                    
                }
                console.log(this.selectedOption);
            },
            selectType() {
                if (this.pageName == "seller") {
                    // this.selectedOption = "default";
                    return "Select a seller type";
                } else if (this.pageName == "artisan") {
                    return "Select an artisan type";
                }
            },
            async submitForm() {
                // Set variables to submit
                const formData = reactive({
                    businessCategory: this.pageName,
                    categoryType: this.selectedOption,
                    state: this.selectedState,
                    town: this.selectedTown,
                    pageName: this.pageName,
                });

                if (this.pageName === 'artisan') {
                    router.post('/artisans', formData, {
                        preserveState: true, // Prevents a full page reload
                        onSuccess: (page) => {
                            console.log('Data fetched: ');
                            console.log(page.props.artisans);
                            this.artisans = [];
                            this.artisans = page.props.artisans;
                            this.$emit('send-category-type', this.artisans);
                        },
                        onError: (errors) => {
                            console.log('Error: ', errors);
                        }
                    });
                } else if (this.pageName === 'seller') {
                    router.post('/mobileMarketers', formData, {
                        preserveState: true, // Prevents a full page reload
                        onSuccess: (page) => {
                            console.log('Data fetched: ');
                            console.log(page.props.mobileMarketers);
                            this.mobileMarketers = [];
                            this.mobileMarketers = page.props.mobileMarketers;
                            this.$emit('send-category-type', this.mobileMarketers);
                        },
                        onError: (errors) => {
                            console.log('Error: ', errors);
                        }
                    });
                }
            }
        },
        watch: {
            async selectedOption() {
                console.log(this.selectedOption);
                const formData = reactive({
                    'selectedOption': this.selectedOption,
                    'pageName': this.pageName,
                });
                
                // router.post('/api/states', formData, {
                //     preserveState: true, // Prevents a full page reload
                //     onSuccess: (page) => {
                //         console.log('Data fetched: ');
                //         console.log(page.props.states);
                //         this.states = [];
                //         this.states = page.props.states;
                //     },
                //     onError: (errors) => {
                //         console.log('Error: ', errors);
                //     }
                // });

                // This method is used when data has to expected back and waited for
                const response = await fetch('/api/states', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    const responseData = await response.json();
                    if (responseData) {
                        console.log('received states data:')
                        console.log(responseData);
                        this.states = responseData;
                    }
                } else {
                    // There is error
                    console.log('There is an error!');
                }
            },

            async selectedState() {
                console.log(this.selectedState);
                const formData = reactive({
                    'selectedOption': this.selectedOption,
                    'selectedState': this.selectedState,
                    'pageName': this.pageName,
                });

                // router.post('/api/towns', formData, {
                //     preserveState: true, // Prevents a full page reload
                //     onSuccess: (page) => {
                //         console.log('Data fetched: ');
                //         console.log(page.props.towns);
                //         // this.states = [];
                //         // this.states = page.props.towns;
                //     },
                //     onError: (errors) => {
                //         console.log('Error: ', errors);
                //     }
                // });

                // This method is used when data has to expected back and waited for
                const response = await fetch('/api/towns', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    const responseData = await response.json();
                    if (responseData) {
                        console.log('received towns data:')
                        console.log(responseData);
                        this.towns = responseData;
                    }
                } else {
                    // There is error
                    console.log('There is an error!');
                }
                
            }
        }
    }
</script>