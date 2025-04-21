<template>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="targetId('#collapse')" aria-expanded="false" :aria-controls="newId()">
                {{ date }}
            </button>
        </h2>
        <div :id="newId()" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <button v-for="(time, index) in times" :key="index" type="button" class="btn btn-primary mb-2 me-2 pe-none">
                    <p class="mb-0">{{ time.time }}</p>
                    <p class="my-0 d-flex justify-content-end" style="font-size: 10px;">{{ time.period }}</p>
                </button>

                <!-- <div class="btn-group my-2 mt-0" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary me-2 pe-none">Left</button>
                    <button type="button" class="btn btn-primary me-2 pe-none">Middle</button>
                    <button type="button" class="btn btn-primary me-2 pe-none">Middle</button>
                    <button type="button" class="btn btn-primary pe-none">Right</button>
                </div>

                <div class="btn-group my-2" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary me-2 pe-none">Left</button>
                    <button type="button" class="btn btn-primary me-2 pe-none">Middle</button>
                    <button type="button" class="btn btn-primary me-2 pe-none">Middle</button>
                    <button type="button" class="btn btn-primary pe-none">Right</button>
                </div> -->

                <div>
                    <button :id="targetId('deleteId')" class="btn btn-danger" @click="deleteSchedule">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import { useForm } from '@inertiajs/vue3';

    export default {
        props: ['count', 'date', 'times'],
        emits: ['updated-schedule'],
        data() {
            return {
                userId: 1,
            }
        },
        methods: {
            newId() {
                return "collapse"+this.count;
            },
            targetId(id) {
                return id+this.count;
            },
            deleteSchedule() {
                var formData = useForm({
                    user_id: this.userId,
                    date_available: this.date,
                });
                formData.delete(route('usersavailability.destroy', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            this.$emit('updated-schedule', page.props.flash.success);
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