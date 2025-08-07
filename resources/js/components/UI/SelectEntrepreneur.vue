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
                <div v-if="vehServiceVisible" class="col-md mb-2">
                    <select class="form-select" aria-label="Default select example" v-model="selectedVehService">
                        <option value="default">Select vehicle service</option>
                        <option v-for="(techServ, index) in technicalServices" :key="index" :value="index">{{ techServ }}</option>
                    </select>
                </div>
                <div v-if="vehSparePartVisible" class="col-md mb-2">
                    <select class="form-select" aria-label="Default select example" v-model="selectedVehSparePart">
                        <option value="default">Select vehicle spare part</option>
                        <option v-for="(sparePart, index) in spareParts" :key="index" :value="index">{{ sparePart }}</option>
                    </select>
                </div>
                <div v-if="vehTypeVisible" class="col-md mb-2">
                    <select class="form-select" aria-label="Default select example" v-model="selectedVehType">
                        <option value="default">Select vehicle type</option>
                        <option v-for="(vehCategory, index) in vehicleCategories" :key="index" :value="index">{{ vehCategory }}</option>
                    </select>
                </div>
                <div v-if="vehBrandVisible" class="col-md mb-2">
                    <select class="form-select" aria-label="Default select example" v-model="selectedVehBrand">
                        <option value="default">Select vehicle brand</option>
                        <option v-for="(vehBrand, index) in vehicleBrands" :key="index" :value="index">{{ vehBrand }}</option>
                    </select>
                </div>
                <div v-if="stateVisible" class="col-md mb-2">
                    <select class="form-select" aria-label="Default select example" v-model="selectedState">
                        <option value="default">Select state</option>
                        <option v-for="(state, index) in states" :key="index" :value="state">{{ state }}</option>
                    </select>
                </div>
                <div v-if="townVisible" class="col-md mb-2">
                    <select class="form-select" aria-label="Default select example" v-model="selectedTown">
                        <option value="default">Select town</option>
                        <option v-for="(town, index) in towns" :key="index" :value="town">{{ town }}</option>
                    </select>
                </div>
                <!-- <div class="col-xs-6 col-md-2 col-lg-1 align-middle"> -->
                <div v-if="submitBtnVisible" class="col-md align-middle">
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
    import axios from 'axios';

    export default {
        // inject: ['products'],
        props: ['pageName', 'selectArray'],
        emits: ['send-artisans'],
        data() {
            return {
                selectedOption: 'default',
                selectedState: 'default',
                selectedTown: 'default',
                selectedVehService: 'default',
                selectedVehSparePart: 'default',
                selectedVehType: 'default',
                selectedVehBrand: 'default',
                states: [],
                towns: [],
                artisans: [],
                mobileMarketers: [],
                technicalServices: [],
                spareParts: [],
                vehicleCategories: [],
                vehicleBrands: [],
                stateVisible: false,
                townVisible: false,
                submitBtnVisible: false,
                vehSparePartVisible: false,
                vehServiceVisible: false,
                vehTypeVisible: false,
                vehBrandVisible: false,
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
                this.dataToSend = {
                    businessCategory: this.pageName,
                    categoryType: this.selectedOption,
                    state: this.selectedState,
                    town: this.selectedTown,
                    pageName: this.pageName,
                };
                if (this.selectedOption === 'mechanic') {
                    this.dataToSend.selectedVehService = this.selectedVehService;
                    this.dataToSend.selectedVehType = this.selectedVehType;
                    this.dataToSend.selectedVehBrand = this.selectedVehBrand;
                } else if (this.selectedOption === 'spare_parts') {
                    this.dataToSend.selectedVehSparePart = this.selectedVehSparePart;
                    this.dataToSend.selectedVehType = this.selectedVehType;
                    this.dataToSend.selectedVehBrand = this.selectedVehBrand;
                }
                const formData = reactive(this.dataToSend);

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
            },

            resetSelectMenus() {
                this.vehServiceVisible = false;
                this.vehSparePartVisible = false;
                this.vehTypeVisible = false;
                this.vehBrandVisible = false;
                this.stateVisible = false;
                this.townVisible = false;
                this.submitBtnVisible = false;
            }
        },
        watch: {
            async selectedOption() {
                console.log(this.selectedOption);
                const formData = reactive({
                    'selectedOption': this.selectedOption,
                    'pageName': this.pageName,
                });
                this.resetSelectMenus();
                this.$emit('send-category-type', []);
                
                if (this.selectedOption === 'mechanic') {
                    const response = await fetch('/api/technicalServices', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    if (response.ok) {
                        const responseData = await response.json();
                        if (responseData) {
                            console.log(responseData);
                            this.technicalServices = responseData;
                            this.vehServiceVisible = true;
                        }
                    } else {
                        // There is error
                        console.log('There is an error!');
                    }
                } else if (this.selectedOption === 'spare_parts') {
                    const response = await fetch('/api/spareParts', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    if (response.ok) {
                        const responseData = await response.json();
                        if (responseData) {
                            console.log(responseData);
                            this.spareParts = responseData;
                            this.vehSparePartVisible = true;
                        }
                    } else {
                        // There is error
                        console.log('There is an error!');
                    }
                } else {
                    // This method is used when data has to expected back and waited for
                    // const response = await fetch('/api/states', {
                    //     method: 'POST',
                    //     headers: {
                    //         'Content-Type': 'application/json'
                    //     },
                    //     body: JSON.stringify(formData)
                    // });

                    // if (response.ok) {
                    //     const responseData = await response.json();
                    //     if (responseData) {
                    //         // console.log('running state block');
                    //         console.log(responseData);
                    //         this.states = responseData;
                    //         this.stateVisible = true;
                    //         this.vehSparePartVisible = false;
                    //     }
                    // } else {
                    //     // There is an error
                    //     console.log('There is an error!');
                    // }

                    try {
                        const { data } = await axios.post('/api/states', formData);
                        if (data.success) {
                            this.states = data.data;

                            console.log(data);
                            this.stateVisible = true;
                            this.vehSparePartVisible = false;
                        } else {
                            this.$toast.error(data.error || 'Failed to fetch states');
                        }
                    } catch (error) {
                        this.$toast.error(error.response?.data?.error || 'An unexpected error occurred');
                    }
                }
            },

            async selectedVehService() {
                console.log(this.selectedVehService);
                const formData = reactive({
                    'selectedVehService': this.selectedVehService,
                    'pageName': this.pageName,
                });
                this.vehSparePartVisible = false;
                this.vehBrandVisible = false;
                this.stateVisible = false;
                this.townVisible = false;
                this.submitBtnVisible = false;
                this.$emit('send-category-type', []);

                // router.post('/api/vehicleCategories', formData, {
                //     preserveState: true, // Prevents a full page reload
                //     onSuccess: (page) => {
                //         console.log(page);
                //     },
                //     onError: (errors) => {
                //         console.log('Error: ', errors);
                //     }
                // });
                
                const response = await fetch('/api/vehicleCategories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    const responseData = await response.json();
                    if (responseData) {
                        console.log(responseData);
                        this.vehicleCategories = responseData;
                        this.vehTypeVisible = true;
                    }
                } else {
                    // There is error
                    console.log('There is an error!');
                }
            },

            async selectedVehSparePart() {
                console.log(this.selectedVehSparePart);
                const formData = reactive({
                    'selectedVehSparePart': this.selectedVehSparePart,
                    'pageName': this.pageName,
                });

                // Reset visibility select menus
                this.vehServiceVisible = false;
                this.vehBrandVisible = false;
                this.stateVisible = false;
                this.townVisible = false;
                this.submitBtnVisible = false;
                this.$emit('send-category-type', []);
                
                const response = await fetch('/api/vehicleCategories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    const responseData = await response.json();
                    if (responseData) {
                        console.log(responseData);
                        this.vehicleCategories = responseData;
                        this.vehTypeVisible = true;
                    }
                } else {
                    // There is error
                    console.log('There is an error!');
                }
            },

            async selectedVehType() {
                console.log(this.selectedVehType);
                this.dataToSend = {
                    'selectedVehType': this.selectedVehType,
                    'pageName': this.pageName,
                };
                if (this.selectedOption === 'mechanic') {
                    this.dataToSend.selectedVehService = this.selectedVehService;
                } else if (this.selectedOption === 'spare_parts') {
                    this.dataToSend.selectedVehSparePart = this.selectedVehSparePart;
                }
                const formData = reactive(this.dataToSend);

                // Reset visibility select menus
                this.stateVisible = false;
                this.townVisible = false;
                this.submitBtnVisible = false;
                this.$emit('send-category-type', []);

                const response = await fetch('/api/vehicleBrands', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    const responseData = await response.json();
                    if (responseData) {
                        console.log(responseData);
                        this.vehicleBrands = responseData;
                        this.vehBrandVisible = true;
                    }
                } else {
                    // There is error
                    console.log('There is an error!');
                }
            },

            async selectedVehBrand() {
                console.log(this.selectedVehBrand);
                this.dataToSend = {
                    'selectedOption': this.selectedOption,
                    'selectedVehType': this.selectedVehType,
                    'pageName': this.pageName,
                    'selectedVehBrand': this.selectedVehBrand,
                };
                if (this.selectedOption === 'mechanic') {
                    this.dataToSend.selectedVehService = this.selectedVehService;
                } else if (this.selectedOption === 'spare_parts') {
                    this.dataToSend.selectedVehSparePart = this.selectedVehSparePart;
                }
                const formData = reactive(this.dataToSend);

                // Reset visibility select menus
                this.townVisible = false;
                this.submitBtnVisible = false;
                this.$emit('send-category-type', []);

                // router.post('/api/states', formData, {
                //     preserveState: true, // Prevents a full page reload
                //     onSuccess: (page) => {
                //         console.log(page);
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
                        console.log(responseData);
                        this.states = responseData;
                        this.stateVisible = true;
                    }
                } else {
                    // There is an error
                    console.log('There is an error!');
                }
            },

            async selectedState() {
                console.log(this.selectedState);
                this.dataToSend = {
                    'selectedOption': this.selectedOption,
                    'selectedState': this.selectedState,
                    'pageName': this.pageName,
                };
                if (this.selectedOption === 'mechanic') {
                    this.dataToSend.selectedVehService = this.selectedVehService;
                    this.dataToSend.selectedVehType = this.selectedVehType;
                    this.dataToSend.selectedVehBrand = this.selectedVehBrand;
                } else if (this.selectedOption === 'spare_parts') {
                    this.dataToSend.selectedVehSparePart = this.selectedVehSparePart;
                    this.dataToSend.selectedVehType = this.selectedVehType;
                    this.dataToSend.selectedVehBrand = this.selectedVehBrand;
                }
                const formData = reactive(this.dataToSend);

                // Reset visibility select menus
                this.submitBtnVisible = false;
                this.$emit('send-category-type', []);

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
                        console.log(responseData);
                        this.towns = responseData;
                        this.townVisible = true;
                    }
                } else {
                    // There is error
                    console.log('There is an error!');
                }
                
            },

            selectedTown() {
                this.submitBtnVisible = true;
                this.$emit('send-category-type', []);
            }
        }
    }
</script>