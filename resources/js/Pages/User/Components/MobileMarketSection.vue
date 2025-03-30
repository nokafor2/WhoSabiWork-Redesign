<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSeventeen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Mobile market:</dt>
                    <dd class="col-sm-9 mb-0">
                        <ul class="list-inline mb-0">
                            <li v-for="(product, index) in selectedProducts" :key="index" class="list-inline-item">{{ product }},</li>
                        </ul>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseSeventeen" class="accordion-collapse collapse" aria-labelledby="headingSeventeen">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <div v-for="(product, index) in allProducts" :key="index" class="form-check form-check-inline">
                            <input class="form-check-input" name="products[]" type="checkbox" :id="index" :value="index" v-model="productsInput">
                            <label class="form-check-label" :for="index">{{ product }}</label>
                        </div>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateSeller" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearSeller" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['allProducts', 'selectedProducts', 'userId'],
        emits: [],
        data() {
            return {
                productsInput: []
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.productsInput,
                });
                dataToSend.put(route('mobileMarketers.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.productsInput = [];
                        }
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