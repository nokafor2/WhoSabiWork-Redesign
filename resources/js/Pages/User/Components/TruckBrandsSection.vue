<template>
    <div class="accordion-item">
        <h2 class="accordion-header" :id="techHeading()">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="techCollapse2()" aria-expanded="false" :aria-controls="techCollapse()">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">{{ accordionTitle() }} Truck brands:</dt>
                    <dd class="col-sm-9 mb-0">
                        <ul class="list-inline mb-0">
                            <li v-for="(truckBrand, index) in selectedTruckBrands" :key="index" class="list-inline-item">{{ truckBrand }}</li>
                        </ul>
                    </dd>
                </dl>
            </button>
        </h2>
        <div :id="techCollapse()" class="accordion-collapse collapse" :aria-labelledby="techHeading()">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <div v-for="(truckBrand, index) in allTruckBrands" :key="index" class="form-check form-check-inline">
                            <input class="form-check-input" name="truck_brands[]" type="checkbox" :id="newId(index, 'truck')" :value="index" v-model="truckBrandsInput">
                            <label class="form-check-label" :for="newId(index, 'truck')">{{ truckBrand }}</label>
                        </div>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateTruckBrands" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearTruckBrands" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['allTruckBrands', 'selectedTruckBrands', 'userId', 'techOrSpare'],
        emits: [],
        data() {
            return {
                truckBrandsInput: []
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.truckBrandsInput,
                    businessCategory: this.techOrSpare
                });
                dataToSend.put(route('truckbrand.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.truckBrandsInput = [];
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            },
            techHeading() {
                if (this.techOrSpare === 'technical_service') {
                    return 'headingTwentyTwo';
                } else if (this.techOrSpare === 'spare_part') {
                    return 'headingTwentyEight';
                }
            },
            techCollapse() {
                if (this.techOrSpare === 'technical_service') {
                    return 'collapseTwentyTwo';
                } else if (this.techOrSpare === 'spare_part') {
                    return 'collapseTwentyEight';
                }
            },
            techCollapse2() {
                if (this.techOrSpare === 'technical_service') {
                    return '#collapseTwentyTwo';
                } else if (this.techOrSpare === 'spare_part') {
                    return '#collapseTwentyEight';
                }
            }
        },
        computed: {
            
        },
    }
</script>