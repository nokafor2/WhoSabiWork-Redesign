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
            mobileMarketers: [],
            artisans: [],
            products: [],
            artisanTypes: [],
            technicalServices: [],
            spareParts: [],
            images: [],
            galleryPhotos: [],
            authenticatedUser: null,
            customerCommentsAndReplies: [],
            userCommentsAndReplies: [],
            isAuthenticated: false,
            entrepreneur: [],
            photoFeedData: [],
        };
    },
    mutations: {
        updateProducts(state, payload) {
            state.products = payload.value;
        },
        updateArtisanTypes(state, payload) {
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
        updateGalleryPhotos(state, payload) {
            state.galleryPhotos = payload.value;
        },
        updateAuthenticatedUser(state, payload) {
            state.authenticatedUser = payload.value;
        },
        updateCustomerCommentsAndReplies(state, payload) {
            state.customerCommentsAndReplies = payload.value;
        },
        updateUserCommentsAndReplies(state, payload) {
            state.userCommentsAndReplies = payload.value;
        },
        updateIsAuthenticated(state, payload) {
            state.isAuthenticated = payload.value;
        },
        updateEntrepreneur(state, payload) {
            state.entrepreneur = payload.value;
        },
        updatePhotoFeedData(state, payload) {
            state.photoFeedData = payload.value;
        },
        appendPhotoFeedData(state, payload) {
            // Append new photos to existing data
            if (state.photoFeedData && state.photoFeedData.data) {
                state.photoFeedData.data = [...state.photoFeedData.data, ...payload.value.data];
                // Update pagination metadata
                state.photoFeedData.current_page = payload.value.current_page;
                state.photoFeedData.last_page = payload.value.last_page;
                state.photoFeedData.next_page_url = payload.value.next_page_url;
            } else {
                state.photoFeedData = payload.value;
            }
        },
        updateMobileMarketers(state, payload) {
            state.mobileMarketers = payload.value;
        },
        updateArtisans(state, payload) {
            state.artisans = payload.value;
        },
        appendArtisans(state, payload) {
            // Append new artisans to existing data
            if (Array.isArray(state.artisans) && Array.isArray(payload.value)) {
                state.artisans = [...state.artisans, ...payload.value];
            } else {
                state.artisans = payload.value;
            }
        },
        appendMobileMarketers(state, payload) {
            // Append new mobile marketers to existing data
            if (Array.isArray(state.mobileMarketers) && Array.isArray(payload.value)) {
                state.mobileMarketers = [...state.mobileMarketers, ...payload.value];
            } else {
                state.mobileMarketers = payload.value;
            }
        },
    },
    actions: {
        updateProducts(context, payload) {
            context.commit('updateProducts', payload);
        },
        updateArtisanTypes(context, payload) {
            context.commit('updateArtisanTypes', payload);
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
        updateAuthenticatedUser(context, payload) {
            context.commit('updateAuthenticatedUser', payload);
        },
        updateGalleryPhotos(context, payload) {
            context.commit('updateGalleryPhotos', payload);
        },
        updateCustomerCommentsAndReplies(context, payload) {
            context.commit('updateCustomerCommentsAndReplies', payload);
        },
        updateUserCommentsAndReplies(context, payload) {
            context.commit('updateUserCommentsAndReplies', payload);
        },
        updateIsAuthenticated(context, payload) {
            context.commit('updateIsAuthenticated', payload);
        },
        updateEntrepreneur(context, payload) {
            context.commit('updateEntrepreneur', payload);
        },
        updatePhotoFeedData(context, payload) {
            context.commit('updatePhotoFeedData', payload);
        },
        appendPhotoFeedData(context, payload) {
            context.commit('appendPhotoFeedData', payload);
        },
        updateMobileMarketers(context, payload) {
            context.commit('updateMobileMarketers', payload);
        },
        updateArtisans(context, payload) {
            context.commit('updateArtisans', payload);
        },
        appendArtisans(context, payload) {
            context.commit('appendArtisans', payload);
        },
        appendMobileMarketers(context, payload) {
            context.commit('appendMobileMarketers', payload);
        },
    },
    getters: {
        getProducts(state) {
            return state.products;
        },
        getArtisanTypes(state) {
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
        getGalleryPhotos(state) {
            return state.galleryPhotos;
        },
        getAuthenticatedUser(state) {
            return state.authenticatedUser;
        },
        getCustomerCommentsAndReplies(state) {
            return state.customerCommentsAndReplies;
        },
        getUserCommentsAndReplies(state) {
            return state.userCommentsAndReplies;
        },
        getIsAuthenticated(state) {
            return state.isAuthenticated;
        },
        getEntrepreneur(state) {
            return state.entrepreneur;
        },
        getPhotoFeedData(state) {
            return state.photoFeedData;
        },
        getMobileMarketers(state) {
            return state.mobileMarketers;
        },
        getArtisans(state) {
            return state.artisans;
        },
    }
});

export default store;