<template>
    <div class="row justify-content-end">
            <div class="col-10">
                <div class="row justify-content-between align-content-middle">
                    <div class="col-auto">
                        <div class="d-flex mt-2 mb-1 align-middle">
                            <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(index)>
                            <p class="col-auto mb-1">You replied</p>
                        </div>
                    </div>
                    <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                </div>
                <p class="col-12 bg-white text-body rounded">
                    {{ reply }}
                </p>
            </div>
        </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';

    export default {
        mixins: [MethodsMixin],
        props: ['index', 'reply'],
        emits: [],
        data() {
            return {
                
            }
        },
        methods: {
            
        }
    }
</script>