<template>
    <div class="accordion-item">
        <h2 class="accordion-header" :id="techHeading()">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="techCollapse2()" aria-expanded="false" :aria-controls="techCollapse()">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Vehicle Categories:</dt>
                    <dd class="col-sm-9 mb-0">
                        <ul class="list-inline mb-0">
                            <li v-for="(vehCategory, index) in selectedVehCategories" :key="index" class="list-inline-item">{{ vehCategory }},</li>
                        </ul>
                    </dd>
                </dl>
            </button>
        </h2>
        <div :id="techCollapse()" class="accordion-collapse collapse" :aria-labelledby="techHeading()">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <div v-for="(vehCategory, index) in allVehicleCategories" :key="index" class="form-check form-check-inline">
                            <input class="form-check-input" name="vehicle_categories[]" type="checkbox" :id="newId(index, techOrSpare)" :value="index" v-model="vehicleCategoriesInput">
                            <label class="form-check-label" :for="newId(index, techOrSpare)">{{ vehCategory }}</label>
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
        props: ['allVehicleCategories', 'selectedVehCategories', 'userId', 'techOrSpare'],
        emits: [],
        data() {
            return {
                vehicleCategoriesInput: [],
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.vehicleCategoriesInput,
                });
                dataToSend.put(route('vehiclecategory.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log(page);
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            },
            techHeading() {
                if (this.techOrSpare === 'tech') {
                    return 'headingTwentyFour';
                } else if (this.techOrSpare === 'spare') {
                    return 'headingTwentyFive';
                }
            },
            techCollapse() {
                if (this.techOrSpare === 'tech') {
                    return 'collapseTwentyFour';
                } else if (this.techOrSpare === 'spare') {
                    return 'collapseTwentyFive';
                }
            },
            techCollapse2() {
                if (this.techOrSpare === 'tech') {
                    return '#collapseTwentyFour';
                } else if (this.techOrSpare === 'spare') {
                    return '#collapseTwentyFive';
                }
            }
        },
        computed: {
            
        },
    }
</script>