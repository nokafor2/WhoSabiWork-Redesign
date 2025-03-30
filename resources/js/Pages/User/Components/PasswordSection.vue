<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingEight">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Password:</dt>
                    <dd class="col-sm-9 mb-0"></dd>
                </dl>
            </button>
        </h2>
        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="password" name="password" value="" placeholder="Password" class="form-control" v-model="password">
                        <input type="password" name="password_confirmation" placeholder="Confirm password" class="mt-2 form-control" v-model="confrimationPassword">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updatePassword" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearPassword" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['userId'],
        emits: [],
        data() {
            return {
                password: '',
                confrimationPassword: ''
            }
        },
        methods: {
            submitAction() {
                console.log(this.password);
                const dataToSend = useForm({
                    updateVal: this.password,
                    updateVal2: this.confrimationPassword,
                    updateField: 'password',
                });
                dataToSend.put(route('users.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.password = '';
                            this.confrimationPassword = '';
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        }
    }
</script>