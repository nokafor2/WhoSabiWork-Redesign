<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSix">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Email:</dt>
                    <dd class="col-sm-9 mb-0">{{ email }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="email" name="email" value="" placeholder="Email" class="form-control" v-model="emailInput">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateEmail" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearEmail" class="btn btn-danger col-auto">Clear</button>
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
        props: ['email', 'userId'],
        emits: [],
        data() {
            return {
                emailInput: ''
            }
        },
        methods: {
            submitAction() {
                console.log(this.emailInput);
                const dataToSend = useForm({
                    updateVal: this.emailInput,
                    updateField: 'email',
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
        }
    }
</script>