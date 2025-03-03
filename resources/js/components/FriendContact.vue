<template>
    <li>
        <h2>{{ name }} {{ isFavorite ? '(Favorite)' : '' }}</h2>
        <button @click="toggleFavorite"> Toggle Favorite</button>
        <button @click="toggleDetails">{{ detailsAreVisible ? 'Hide' : 'Show' }} Details</button>
        <ul v-if="detailsAreVisible">
            <li><strong>Phone:</strong> {{ phoneNumber }} </li>
            <li><strong>Email:</strong> {{ emailAddress }} </li>
        </ul>
        <button @click="$emit('delete', id)">Delete</button>
    </li>
</template>

<script>
export default {
    // This variables would be available globally
    // props: [ 'name', 'phoneNumber', 'emailAddress', 'isFavorite' ],
    props: {
        id: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: true,
        },
        phoneNumber: {
            type: String,
            required: true
        },
        emailAddress: {
            type: String,
            required: true
        },
        isFavorite: {
            type: Boolean,
            required: false,
            default: false,
            // validator: function(value) {
            //     return value === '1' || value === '0'
            // }
        }
    },
    // Emits helps to inform what functions would send data to the parent components
    emits: ['toggle-favorite', 'delete'],
    // This definition can be optional
    // emits: {
    //     'toggle-favorite': function(id) {
    //         if (id) {
    //             return true;
    //         } else {
    //             console.warn('Id is missing!');
    //             return false;
    //         }
    //     }
    // },
    data() {
        return {
            detailsAreVisible: false,
            // This strategy is not used anymore
            // friendIsFavorite: this.isFavorite,
        };
    },
    methods: {
        toggleDetails() {
            this.detailsAreVisible = !this.detailsAreVisible;
        },
        toggleFavorite() {
            // this.friendIsFavorite = !this.friendIsFavorite;
            this.$emit('toggle-favorite', this.id); // In emit, use cavop case writing
        },
        // deleteFriend() {
        //     this.$emit('delete', this.id);
        // }
    }
};
</script>
