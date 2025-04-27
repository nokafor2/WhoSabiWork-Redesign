<template>
    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Launch static backdrop modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <VueDatePicker v-model="date" inline auto-apply :enable-time-picker="false" ignore-time-validation model-type="yyyy-MM-dd" :allowed-dates="allowedDates" />
                    <p v-if="date">Selected date: {{ date }}</p>
                </div>

                <div class="row row-cols-5 justify-content-center mt-3">
                    <div>
                        <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btncheck1">Checkbox 1</label>
                    </div>

                    <div>
                        <input type="checkbox" class="btn-check" id="btncheck2" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btncheck2">Checkbox 2</label>
                    </div>

                    <div>
                        <input type="checkbox" class="btn-check" id="btncheck3" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btncheck3">Checkbox 3</label>
                    </div>

                    <div>
                        <input type="checkbox" class="btn-check" id="btncheck4" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btncheck4">Checkbox 4</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css';
</script>

<script>
    import { useForm } from '@inertiajs/vue3';

    export default {
        data() {
            return {
                date: null,
            }
        },
        computed: {
            allowedDates() {
                return [
                    new Date(),
                    new Date('2025-04-26'),
                    new Date('2025-04-27'),
                    new Date('2025-04-28'),
                    new Date('2025-04-29'),
                    new Date('2025-04-30'),
                    new Date('2025-04-31'),
                ]
            }
        }
    }
</script>