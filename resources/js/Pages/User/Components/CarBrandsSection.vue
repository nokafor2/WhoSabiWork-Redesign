<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwenty">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Car brands:</dt>
                    <dd class="col-sm-9 mb-0">
                        <ul class="list-inline mb-0">
                            <li v-for="(carBrand, index) in selectedCarBrands" :key="index" class="list-inline-item">{{ carBrand }},</li>
                        </ul>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTwenty" class="accordion-collapse collapse" aria-labelledby="headingTwenty">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <div v-for="(carBrand, index) in allCarBrands" :key="index" class="form-check form-check-inline">
                            <input class="form-check-input" name="car_brands[]" type="checkbox" :id="newId(index, 'car')" :value="index" v-model="carBrandsInput">
                            <label class="form-check-label" :for="newId(index, 'car')">{{ carBrand }}</label>
                        </div>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateCarBrands" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearCarBrands" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['allCarBrands', 'selectedCarBrands', 'userId'],
        emits: [],
        data() {
            return {
                carBrandsInput: []
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.carBrandsInput,
                });
                dataToSend.put(route('carbrand.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log(page);
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        computed: {
            
        },
    }
</script>