<template>
    <div class="col-sm-5 col-11 mt-3 mt-sm-3 shadow rounded text-light" style="background-color: #040D14">
        <div class="row mt-2">
            <div class="row">
                <p class="col-auto mb-0">Business Category:</p>
                <!-- <ul class="col list-inline"> -->
                <ul class="list-inline">
                    <li class="list-inline-item" v-for="(category, index) in user.userCategories" :key="index"><i class="fa-regular fa-circle-dot px-2"></i>{{ showCategory(index) }}</li>
                </ul>
            </div>
            <div class="row" v-for="(category, index) in user.userCategories" :key="index">
                <p class="col-auto mb-0">{{ categoryHeading(index) }}</p>
                <ul v-if="isArtisan(index)" class="list-inline">
                    <li class="list-inline-item" v-for="(type, id) in category" :key="id"><i class="fa-solid fa-helmet-safety px-2"></i>{{ type }}</li>
                </ul>
                <ul v-if="isTechnician(index)" class="list-inline">
                    <li class="list-inline-item" v-for="(type, id) in category" :key="id"><i class="fa-solid fa-helmet-safety px-2"></i>{{ type }}</li>
                </ul>
                <ul v-if="isMobileMarketer(index)" class="list-inline">
                    <li class="list-inline-item" v-for="(type, id) in category" :key="id"><i class="fa-regular fa-circle-dot px-2"></i>{{ type }}</li>
                </ul>
                <ul v-if="isSparePart(index)" class="list-inline">
                    <li class="list-inline-item" v-for="(type, id) in category" :key="id"><i class="fa-regular fa-circle-dot px-2"></i>{{ type }}</li>
                </ul>
                <div v-if="index === 'mechanic'" class="row">
                    <div v-if="user.vehicleBrands.tech_car" class="row px-4">
                        <p class="col-auto mb-0">Car Brands:</p>
                        <ul class="list-inline">
                            <!-- <i class="fa-solid fa-car"></i> -->
                            <li v-for="(carBrand, index) in user.vehicleBrands.tech_car" :key="index" class="list-inline-item"><i class="fa-solid fa-car-side px-2"></i>{{ carBrand }}</li>
                        </ul>
                    </div>
                    <div v-if="user.vehicleBrands.tech_bus" class="row px-4">
                        <p class="col-auto mb-0">Bus Brands:</p>
                        <ul class="list-inline">
                            <!-- <i class="fa-solid fa-bus"></i> -->
                            <li v-for="(busBrand, index) in user.vehicleBrands.tech_bus" :key="index" class="list-inline-item"><i class="fa-solid fa-van-shuttle px-2"></i>{{ busBrand }}</li>
                        </ul>
                    </div>
                    <div v-if="user.vehicleBrands.tech_truck" class="row px-4">
                        <p class="col-auto mb-0">Truck Brands:</p>
                        <ul class="list-inline">
                            <li v-for="(truckBrand, index) in user.vehicleBrands.tech_truck" :key="index" class="list-inline-item"><i class="fa-solid fa-truck-moving px-2"></i>{{ truckBrand }}</li>
                        </ul>
                    </div>
                </div>
                <div v-if="index === 'spare_part_seller'" class="row">
                    <div v-if="user.vehicleBrands.sPart_car" class="row px-4">
                        <p class="col-auto mb-0">Car Brands:</p>
                        <ul class="list-inline">
                            <li v-for="(carBrand, index) in user.vehicleBrands.sPart_car" :key="index" class="list-inline-item"><i class="fa-solid fa-car-side px-2"></i>{{ carBrand }}</li>
                        </ul>
                    </div>
                    <div v-if="user.vehicleBrands.sPart_bus" class="row px-4">
                        <p class="col-auto mb-0">Bus Brands:</p>
                        <ul class="list-inline">
                            <li v-for="(busBrand, index) in user.vehicleBrands.tech_bus" :key="index" class="list-inline-item"><i class="fa-solid fa-van-shuttle px-2"></i>{{ busBrand }}</li>
                        </ul>
                    </div>
                    <div v-if="user.vehicleBrands.sPart_truck" class="row px-4">
                        <p class="col-auto mb-0">Truck Brands:</p>
                        <ul class="list-inline">
                            <li v-for="(truckBrand, index) in user.vehicleBrands.sPart_truck" :key="index" class="list-inline-item"><i class="fa-solid fa-truck-moving px-2"></i>{{ truckBrand }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <p class="col-auto mb-0">Description</p>
                <p>{{ businessDescription }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                artisans: [],
                products: [],
                spareParts: [],
                technicalServices: []
            }
        },
        methods: {
            showCategory(category) {
                category = this.capitalizeFirstLetter(category);
                return category.replace(/_/g, " ");
            },
            capitalizeFirstLetter(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            },
            categoryHeading(category) {
                if (category === 'artisan') {
                    return 'Artisan Services:';
                } else if (category === 'mobile_marketer') {
                    return 'Products:';
                } else if (category === 'mechanic') {
                    return 'Technical Services:';
                } else if (category === 'spare_part_seller') {
                    return 'Spare Part Products:';
                }
            },
            isArtisan(category) {
                return (category === 'artisan') ? true : false;
            },
            isMobileMarketer(category) {
                return (category === 'mobile_marketer') ? true : false;
            },
            isTechnician(category) {
                return (category === 'mechanic') ? true : false;
            },
            isSparePart(category) {
                return (category === 'spare_part_seller') ? true : false;
            }
        },
        computed: {
            businessDescription() {
                return this.user.userDetails.business_category.business_description;
            },
            businessCategory() {
                if (this.user.userCategories.artisan?.length) {
                    this.artisans = this.user.userCategories.artisan;
                }
                if (this.user.userCategories.product?.length) {
                    this.products = this.user.userCategories.mobile_marketer;
                }
                if (this.user.userCategories.spare_part?.length) {
                    this.spareParts = this.user.userCategories.spare_part_seller;
                }
                if (this.user.userCategories.technical_service?.length) {
                    this.technicalServices = this.user.userCategories.mechanic;
                }
                return "";
            },
            checkSparePart() {
                return "";
            }
        }
    }
</script>