export default {
    namespaced: true,
    state() {
        return {
            artisanCheckboxId: '',
            artisanCheckboxActive: false,
            selectedArtisans: [],

            mobileMarketChkBoxId: false,
            mobileMarketChkBoxActive: false,
            selectedMobileMarketers: [],
        };
    },
    mutations: {
        updateSelectedArtisans(state, payload) {
            state.selectedArtisans = payload.value;
        },
        updateArtisanCheckboxId(state, payload) {
            state.artisanCheckboxId = payload.value;
        },
        updateArtisanCheckboxActive(state, payload) {
            state.artisanCheckboxActive = payload.value;
        },

        updateMobileMarketChkBoxId(state, payload) {
            state.mobileMarketChkBoxId = payload.value;
        },
        updateMobileMarketChkBoxActive(state, payload) {
            state.mobileMarketChkBoxActive = payload.value;
        },
        updateSelectedMobileMarketers(state, payload) {
            state.selectedMobileMarketers = payload.value;
        },
    },
    actions: {
        updateSelectedArtisans(context, payload) {
            context.commit('updateSelectedArtisans', payload);
        },
        updateArtisanCheckboxId(context, payload) {
            context.commit('updateArtisanCheckboxId', payload);
        },
        updateArtisanCheckboxActive(context, payload) {
            context.commit('updateArtisanCheckboxActive', payload);
        },

        updateMobileMarketChkBoxId(context, payload) {
            context.commit('updateMobileMarketChkBoxId', payload);
        },
        updateMobileMarketChkBoxActive(context, payload) {
            context.commit('updateMobileMarketChkBoxActive', payload);
        },
        updateSelectedMobileMarketers(context, payload) {
            context.commit('updateSelectedMobileMarketers', payload);
        },
    },
    getters: {
        getSelectedArtisans(state) {
            return state.selectedArtisans;
        },
        getArtisanCheckboxId(state) {
            return state.artisanCheckboxId;
        },
        getArtisanCheckboxActive(state) {
            return state.artisanCheckboxActive;
        },

        getMobileMarketChkBoxId(state) {
            return state.mobileMarketChkBoxId;
        },
        getMobileMarketChkBoxActive(state) {
            return state.mobileMarketChkBoxActive;
        },
        getSelectedMobileMarketers(state) {
            return state.selectedMobileMarketers;
        },
    }
};