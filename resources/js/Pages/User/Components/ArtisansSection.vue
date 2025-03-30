<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSixteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Artisans:</dt>
                    <dd class="col-sm-9 mb-0">
                        <ul class="list-inline mb-0">
                            <li v-for="(artisan, index) in selectedArtisans" :key="index" class="list-inline-item">{{ artisan }},</li>
                        </ul>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseSixteen" class="accordion-collapse collapse" aria-labelledby="headingSixteen">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <div v-for="(artisan, index) in allArtisans" :key="index" class="form-check form-check-inline">
                            <input class="form-check-input" name="artisans[]" type="checkbox" :id="index" :value="index" v-model="artisansInput">
                            <label class="form-check-label" :for="index">{{ artisan }}</label>
                            <!-- <div class="invalid-feedback">Select at least one business category</div> -->
                        </div>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateArtisan" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearArtisan" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['allArtisans', 'selectedArtisans', 'userId'],
        emits: [],
        data() {
            return {
                artisansInput: []
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.artisansInput,
                });
                dataToSend.put(route('artisans.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.artisansInput = [];
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