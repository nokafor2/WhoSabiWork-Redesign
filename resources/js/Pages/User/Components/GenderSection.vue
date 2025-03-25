<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Gender:</dt>
                    <dd class="col-sm-9 mb-0">{{ getGender }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <select name="gender" id="gender" class="form-control" v-model="genderInput">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateGender" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearGender" class="btn btn-danger col-auto">Clear</button>
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
        props: ['gender', 'userId'],
        emits: [],
        data() {
            return {
                genderInput: ''
            }
        },
        methods: {
            submitAction() {
                console.log(this.genderInput);
                const dataToSend = useForm({
                    updateVal: this.genderInput,
                    updateField: 'gender',
                });
                dataToSend.put(route('users.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log(page.props.updateStatus);
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        computed: {
            getGender() {
                return this.capitalizeFirstLetter(this.gender);
            }
        }
    }
</script>