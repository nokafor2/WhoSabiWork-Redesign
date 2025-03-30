<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingNineteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNineteen" aria-expanded="false" aria-controls="collapseNineteen">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Spare part sellers:</dt>
                    <dd class="col-sm-9 mb-0">
                        <ul class="list-inline mb-0">
                            <li v-for="(sparePart, index) in selectedSpareParts" :key="index" class="list-inline-item">{{ sparePart }},</li>
                        </ul>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseNineteen" class="accordion-collapse collapse" aria-labelledby="headingNineteen">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <div v-for="(sparePart, index) in allSpareParts" :key="index" class="form-check form-check-inline">
                            <input class="form-check-input" name="spare_parts[]" type="checkbox" :id="index" :value="index" v-model="sparePartsInput">
                            <label class="form-check-label" :for="index">{{ sparePart }}</label>
                        </div>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateSpareParts" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearSpareParts" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['allSpareParts', 'selectedSpareParts', 'userId'],
        emits: [],
        data() {
            return {
                sparePartsInput: []
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.sparePartsInput,
                });
                dataToSend.put(route('sparepart.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.sparePartsInput = [];
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