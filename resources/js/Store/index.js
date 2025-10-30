import { createStore } from 'vuex';
import creatAccountModule from './Modules/createAccount.js';
import appointmentModule from './Modules/appointment.js';

const store = createStore({
    modules: {
        createAccount: creatAccountModule,
        appointment: appointmentModule
    },
    state() {
        return {
            sellerProducts: [],
            artisanTypes: [],
            technicalServices: [],
            spareParts: [],
            images: [],
            user: [],
            customerCommentsAndReplies: [],
        };
    },
    mutations: {
        updateProducts(state, payload) {
            state.sellerProducts = payload.value;
        },
        updateArtisans(state, payload) {
            state.artisanTypes = payload.value;
        },
        updateTechServicess(state, payload) {
            state.technicalServices = payload.value;
        },
        updateSpareParts(state, payload) {
            state.spareParts = payload.value;
        },
        updateImages(state, payload) {
            state.images = payload.value;
        },
        updateUser(state, payload) {
            state.user = payload.value;
        },
        updateCustomerCommentsAndReplies(state, payload) {
            state.customerCommentsAndReplies = payload.value;
        },
    },
    actions: {
        updateProducts(context, payload) {
            context.commit('updateProducts', payload);
        },
        updateArtisans(context, payload) {
            context.commit('updateArtisans', payload);
        },
        updateTechServicess(context, payload) {
            context.commit('updateTechServicess', payload);
        },
        updateSpareParts(context, payload) {
            context.commit('updateSpareParts', payload);
        },
        updateImages(context, payload) {
            context.commit('updateImages', payload);
        },
        updateUser(context, payload) {
            context.commit('updateUser', payload);
        },
        updateCustomerCommentsAndReplies(context, payload) {
            context.commit('updateCustomerCommentsAndReplies', payload);
        },
    },
    getters: {
        getProducts(state) {
            return state.sellerProducts;
        },
        getArtisans(state) {
            return state.artisanTypes;
        },
        getTechServices(state) {
            return state.technicalServices;
        },
        getSpareParts(state) {
            return state.spareParts;
        },
        getImages(state) {
            return state.images;
        },
        getUser(state) {
            return state.user;
        },
        getCustomerCommentsAndReplies(state) {
            return state.customerCommentsAndReplies;
        },
    }
});

export default store;