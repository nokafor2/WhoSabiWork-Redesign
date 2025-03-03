import { createStore } from 'vuex';
import creatAccountModule from './Modules/createAccount.js';

const store = createStore({
    modules: {
        createAccount: creatAccountModule
    },
    state() {
        return {
            sellerProducts: [],
            artisanTypes: [],
            technicalServices: [],
            spareParts: [],
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
    }
});

export default store;