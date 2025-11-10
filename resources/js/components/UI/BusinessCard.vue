<template>
    <div class="card m-3 shadow bg-light" style="width: 20rem;">
        <img src="../../../Images/photoSample.jpg" style="height: 18rem;" class="card-img-top mt-2 img-fluid" alt="...">
        <div class="card-body px-0">
            <a :href="route('entrepreneur.show', userId)" class="text-dark text-center"><h5 class="card-title">{{ businessName }}</h5></a>
            <p class="card-text mb-1 border px-2 rounded-pill"><i class="fa-regular fa-user"></i> {{ fullName }}</p>
            <p class="card-text mb-1 border px-2 rounded-pill"><i class="fa-solid fa-location-dot"></i> {{ addressLine1 }}</p>
            <p class="card-text mb-1 border px-2 rounded-pill"><i class="fa-solid fa-phone"></i> {{ phoneNumber }}</p>
            <span class="border px-2 rounded-pill d-block">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-regular fa-star-half-stroke"></i>
                <i class="fa-regular fa-star"></i>
            </span>
        </div>
    </div>
</template>


<script>
    // import { computed } from 'vue';

    // export default {
    //     props: ['firstName', 'lastName', 'phoneNumber', 'addressLine1', 'addressLine2', 'addressLine3', 'businessName'],
    //     computed: {
    //         fullName() {
    //             return this.firstName + ' ' + this.lastName;
    //         }
    //     }
    // }
</script>

<script setup>
    import { computed } from 'vue';
    const props = defineProps(['userId', 'firstName', 'lastName', 'phoneNumber', 'addressLine1', 'addressLine2', 'addressLine3', 'businessName']);
    const fullName = computed (() => {
        return props.firstName + ' ' + props.lastName;
    });
</script>