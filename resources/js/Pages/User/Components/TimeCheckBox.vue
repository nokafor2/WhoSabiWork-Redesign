<template>
    <input type="checkbox" class="btn-check" :id="idVal" autocomplete="off" v-model="timeSelected">
    <label class="btn btn-outline-primary" :for="idVal">
        <p class="mb-0">{{ time }}</p>
        <p class="my-0 d-flex justify-content-end" style="font-size: 10px;">{{ period }}</p>
    </label>
</template>


<script>
    export default {
        props: [ 'count', 'time', 'period', 'name' ],
        emits: [ 'time-selected' ],
        data() {
            return {
                timeSelected: false,
            }
        },
        methods: {
            
        },
        computed: {
            idVal() {
                return 'btncheck'+this.count;
            }
        },
        watch: {
            timeSelected() {
                console.log(this.timeSelected + ' ' + this.name);
                this.$emit('time-selected', this.timeSelected, this.name);
            }
        }
    }
</script>