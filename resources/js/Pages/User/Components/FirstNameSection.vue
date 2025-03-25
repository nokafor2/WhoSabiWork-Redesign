<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">First Name:</dt>
                    <dd class="col-sm-9 mb-0">{{ getFirstName }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="first_name" value="" placeholder="First name" class="form-control" v-model="firstNameInput">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateFirstName" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="button" name="clearFirstName" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['firstName', 'userId'],
        emits: [],
        data() {
            return {
                firstNameInput: ""
            }
        },
        methods: {
            submitAction() {
                console.log(this.firstNameInput);
                const dataToSend = useForm({
                    updateVal: this.firstNameInput,
                    updateField: 'first_name',
                });
                // dataToSend.put('/users/'+this.userId);
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
            getFirstName() {
                return this.capitalizeFirstLetter(this.firstName);
            }
        },
    }
</script>