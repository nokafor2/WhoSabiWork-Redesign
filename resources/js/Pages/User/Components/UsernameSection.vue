<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFive">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Username:</dt>
                    <dd class="col-sm-9 mb-0">{{ username }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="username" value="" placeholder="Username" class="form-control" v-model="usernameInput">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateUsername" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearUsername" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['username', 'userId'],
        emits: [],
        data() {
            return {
                usernameInput: ''
            }
        },
        methods: {
            submitAction() {
                console.log(this.usernameInput);
                const dataToSend = useForm({
                    updateVal: this.usernameInput,
                    updateField: 'username',
                });
                dataToSend.put(route('users.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.usernameInput = '';
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