<template>
    <VueDatePicker v-model="date" inline auto-apply :min-date="new Date()" model-type="timestamp" />
    <p v-if="date">Selected date: {{ date }}</p>

    <TimePicker></TimePicker>
</template>
  
<script setup>
    // import { ref } from 'vue';
    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css';

    // const date = ref(new Date());
</script>

<script>
    import TimePicker from './TimePicker.vue';

    export default {
        components: { TimePicker },
        data() {
            return {
                date: null,
            }
        }
    }
</script>