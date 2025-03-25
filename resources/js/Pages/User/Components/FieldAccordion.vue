<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">{{ fieldTitle }}</dt>
                    <dd class="col-sm-9 mb-0">{{ getFieldVal }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="first_name" value="" placeholder="First name" class="form-control" v-model="inputVal">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateFirstName" class="btn btn-danger col-auto">Update</button>
                        <button type="button" name="clearFirstName" class="btn btn-danger col-auto">Clear</button>
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
        props: ['fieldName', 'fieldVal', 'userId'],
        emits: [],
        data() {
            return {
                inputVal: ""
            }
        },
        methods: {
            submitAction() {
                console.log(this.inputVal);
                const dataToSend = useForm({
                    updateVal: this.inputVal,
                    updateField: this.fieldName,
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
            fieldTitle() {
                if (this.fieldName === 'first_name') {
                    return 'First Name:';
                } else if (this.fieldName === 'last_name') {
                    return 'Last Name:';
                }
            },
            getFieldVal() {
                if (this.fieldName === 'first_name') {
                    return this.capitalizeFirstLetter(this.fieldVal);
                } else if (this.fieldName === 'last_name') {
                    return this.capitalizeFirstLetter(this.fieldVal);
                }
            }
        },
    }
</script>