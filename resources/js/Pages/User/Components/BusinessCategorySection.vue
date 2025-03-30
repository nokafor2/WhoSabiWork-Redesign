<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFifteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Business Category:</dt>
                    <dd class="col-sm-9 mb-0">{{ getBusinessCategories }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseFifteen" class="accordion-collapse collapse" aria-labelledby="headingFifteen">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="business_categories[]" id="artisan" class="form-check-input" v-model="categoryInput" value="artisan">
                            <label for="artisan" class="form-check-label">Artisan</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="business_categories[]" id="seller" class="form-check-input" v-model="categoryInput" value="seller">
                            <label for="seller" class="form-check-label">Mobile market</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="business_categories[]" id="mechanic" class="form-check-input" v-model="categoryInput" value="technician">
                            <label for="mechanic" class="form-check-label">Mechanic</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="business_categories[]" id="spare_part_seller" class="form-check-input" v-model="categoryInput" value="spare_part_seller">
                            <label for="spare_part_seller" class="form-check-label">Spare Part Seller</label>
                        </div>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateBusinessCategory" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearBusinessCategory" class="btn btn-danger col-auto">Clear</button> -->
                    </div>
                </form>   
            </div>
        </div>
    </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';
    import { useForm } from '@inertiajs/vue3';

    export default {
        mixins: [MethodsMixin],
        props: ['businessCategory', 'userId'],
        emits: [],
        data() {
            return {
                categoryInput: []
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.categoryInput,
                });
                dataToSend.put(route('businesscategory.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.categoryInput = [];
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        computed: {
            getTown() {
                return this.capitalizeFirstLetter(this.town);
            },
            getBusinessCategories() {
                var categories = [];
                if (this.businessCategory.artisan) {
                    categories.push('Artisan');
                }
                if (this.businessCategory.seller) {
                    categories.push('Mobile Marketer');
                }
                if (this.businessCategory.technician) {
                    categories.push('Technician');
                }
                if (this.businessCategory.spare_part_seller) {
                    categories.push('Spare Part Seller');
                }

                return this.explodeArray(categories, ", ");
            }
        },
    }
</script>