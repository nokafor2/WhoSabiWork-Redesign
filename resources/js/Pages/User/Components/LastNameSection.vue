<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Last Name:</dt>
                    <dd class="col-sm-9 mb-0">{{ getLastName }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="last_name" value="" placeholder="Last name" class="form-control" v-model="lastNameInput">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateLastName" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearLastName" class="btn btn-danger col-auto">Clear</button>
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
        props: ['lastName', 'userId'],
        emits: [],
        data() {
            return {
                lastNameInput: '',
            }
        },
        methods: {
            submitAction() {
                console.log(this.lastNameInput);
                const dataToSend = useForm({
                    updateVal: this.lastNameInput,
                    updateField: 'last_name',
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
            getLastName() {
                return this.capitalizeFirstLetter(this.lastName);
            }
        }
    }
</script>