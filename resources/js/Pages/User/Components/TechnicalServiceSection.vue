<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingEighteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Technicians:</dt>
                    <dd class="col-sm-9 mb-0">
                        <ul class="list-inline mb-0">
                            <li v-for="(techServ, index) in selectedTechnicians" :key="index" class="list-inline-item">{{ techServ }},</li>
                        </ul>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseEighteen" class="accordion-collapse collapse" aria-labelledby="headingEighteen">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <div v-for="(techServ, index) in allTechnicalServices" :key="index" class="form-check form-check-inline">
                            <input class="form-check-input" name="technical_services[]" type="checkbox" :id="index" :value="index" v-model="techniciansInput">
                            <label class="form-check-label" :for="index">{{ techServ }}</label>
                        </div>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateTechnicalServices" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearTechnicalServices" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['allTechnicalServices', 'selectedTechnicians', 'userId'],
        emits: [],
        data() {
            return {
                techniciansInput: []
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.techniciansInput,
                });
                dataToSend.put(route('technicalServices.update', this.userId), {
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