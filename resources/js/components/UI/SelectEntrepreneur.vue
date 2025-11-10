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
        emits: ['send-artisans', 'send-category-type', 'search-started', 'update-search-params'],
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
                    // this.selectArray = this.$store.getters.getArtisanTypes;
                    
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
                    // Ensure CSRF token is available
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    
                    // Emit search started event
                    this.$emit('search-started');
                    
                    router.post('/artisans', formData, {
                        preserveState: true, // Prevents a full page reload
                        preserveScroll: true,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                        },
                        onStart: () => {
                            console.log('Starting artisans search request...');
                        },
                        onSuccess: (page) => {
                            this.artisans = [];
                            this.artisans = page.props.artisans;
                            // Update artisan in Vuex state
                            this.$store.dispatch('updateArtisans', { value: this.artisans });
                            this.$emit('send-category-type', page.props);
                            // Emit search params to parent for pagination
                            this.$emit('update-search-params', this.dataToSend);
                        },
                        onError: (errors) => {
                            console.log('Artisans search error: ', errors);
                            console.log('Error details:', {
                                status: errors.status || 'Unknown',
                                message: errors.message || 'Unknown error',
                                response: errors.response || 'No response'
                            });
                        }
                    });
                } else if (this.pageName === 'seller') {
                    // Ensure CSRF token is available
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    // console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');
                    
                    // Emit search started event
                    this.$emit('search-started');
                    
                    router.post('/mobilemarketers', formData, {
                        preserveState: true, // Prevents a full page reload
                        preserveScroll: true,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                        },
                        onStart: () => {
                            console.log('Starting mobilemarketers search request...');
                        },
                        onSuccess: (page) => {
                            console.log('Mobilemarketers search success!');
                            console.log('Data fetched: ');
                            console.log(page.props.mobileMarketers);
                            this.mobileMarketers = [];
                            this.mobileMarketers = page.props.mobileMarketers;
                            // Update mobile marketers in Vuex state
                            this.$store.dispatch('updateMobileMarketers', { value: this.mobileMarketers });
                            this.$emit('send-category-type', page.props);
                            // Emit search params to parent for pagination
                            this.$emit('update-search-params', this.dataToSend);
                        },
                        onError: (errors) => {
                            console.log('Mobilemarketers search error: ', errors);
                            console.log('Error details:', {
                                status: errors.status || 'Unknown',
                                message: errors.message || 'Unknown error',
                                response: errors.response || 'No response'
                            });
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
                // console.log(this.selectedOption);
                const formData = reactive({
                    'selectedOption': this.selectedOption,
                    'pageName': this.pageName,
                });
                this.resetSelectMenus();
                this.$emit('send-category-type', []);
                
                if (this.selectedOption === 'mechanic') {
                    try {
                        const response = await fetch('/api/technicalservice', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(formData),
                            signal: AbortSignal.timeout(10000) // 10 second timeout
                        });

                        if (response.ok) {
                            const responseData = await response.json();
                            if (responseData && responseData.success) {
                                // console.log(responseData);
                                this.technicalServices = responseData.data || responseData;
                                this.vehServiceVisible = true;
                            } else {
                                console.error('Invalid response format:', responseData);
                            }
                        } else {
                            console.error('HTTP Error:', response.status, response.statusText);
                        }
                    } catch (error) {
                        if (error.name === 'AbortError') {
                            console.error('Request timeout');
                        } else {
                            console.error('Network error:', error);
                        }
                    }
                } else if (this.selectedOption === 'spare_parts') {
                    try {
                        const response = await fetch('/api/spareparts', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(formData),
                            signal: AbortSignal.timeout(10000)
                        });

                        if (response.ok) {
                            const responseData = await response.json();
                            if (responseData && responseData.success) {
                                // console.log(responseData);
                                this.spareParts = responseData.data || responseData;
                                this.stateVisible = true;
                                this.vehSparePartVisible = true;
                            } else {
                                console.error('Invalid response format:', responseData);
                            }
                        } else {
                            console.error('HTTP Error:', response.status, response.statusText);
                        }
                    } catch (error) {
                        if (error.name === 'AbortError') {
                            console.error('Request timeout');
                        } else {
                            console.error('Network error:', error);
                        }
                    }
                } else {
                    try {
                        const { data } = await axios.post('/api/states', formData, {
                            timeout: 10000,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        if (data.success) {
                            this.states = data.data;
                            // console.log(data);
                            this.stateVisible = true;
                            this.vehSparePartVisible = false;
                        } else {
                            console.error('API Error:', data.error || 'Failed to fetch states');
                        }
                    } catch (error) {
                        console.error('Axios error:', error.response?.data || error.message);
                    }
                }
            },

            async selectedVehService() {
                // console.log(this.selectedVehService);
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

                try {
                    const { data } = await axios.post('/api/vehiclecategories', formData, {
                        timeout: 10000,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (data.success) {
                        // console.log(data);
                        this.vehicleCategories = data.data || data;
                        this.vehTypeVisible = true;
                    } else {
                        console.error('API Error:', data.error || 'Failed to fetch vehicle categories');
                    }
                } catch (error) {
                    console.error('Axios error:', error.response?.data || error.message);
                }
            },

            async selectedVehSparePart() {
                // console.log(this.selectedVehSparePart);
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
                
                try {
                    const { data } = await axios.post('/api/vehiclecategories', formData, {
                        timeout: 10000,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (data.success) {
                        // console.log(data);
                        this.vehicleCategories = data.data || data;
                        this.vehTypeVisible = true;
                    } else {
                        console.error('API Error:', data.error || 'Failed to fetch vehicle categories');
                    }
                } catch (error) {
                    console.error('Axios error:', error.response?.data || error.message);
                }
            },

            async selectedVehType() {
                // console.log(this.selectedVehType);
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

                try {
                    const { data } = await axios.post('/api/vehiclebrands', formData, {
                        timeout: 10000,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (data.success) {
                        // console.log(data);
                        this.vehicleBrands = data.data || data;
                        this.vehBrandVisible = true;
                    } else {
                        console.error('API Error:', data.error || 'Failed to fetch vehicle brands');
                    }
                } catch (error) {
                    console.error('Axios error:', error.response?.data || error.message);
                }
            },

            async selectedVehBrand() {
                // console.log(this.selectedVehBrand);
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

                try {
                    const { data } = await axios.post('/api/states', formData, {
                        timeout: 10000,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (data.success) {
                        // console.log(data);
                        this.states = data.data || data;
                        this.stateVisible = true;
                    } else {
                        console.error('API Error:', data.error || 'Failed to fetch states');
                    }
                } catch (error) {
                    console.error('Axios error:', error.response?.data || error.message);
                }
            },

            async selectedState() {
                // console.log(this.selectedState);
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

                try {
                    const { data } = await axios.post('/api/towns', formData, {
                        timeout: 10000,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (data.success) {
                        // console.log(data);
                        this.towns = data.data || data;
                        this.townVisible = true;
                    } else {
                        console.error('API Error:', data.error || 'Failed to fetch towns');
                    }
                } catch (error) {
                    console.error('Axios error:', error.response?.data || error.message);
                }
            },

            selectedTown() {
                this.submitBtnVisible = true;
                this.$emit('send-category-type', []);
            }
        }
    }
</script>