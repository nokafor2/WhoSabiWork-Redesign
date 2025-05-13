export default {
    namespaced: true,
    state() {
        return {
            subAptVisible: true,
            mainAptVisible: false,
        };
    },
    mutations: {
        updateSubAptVisible(state, payload) {
            state.subAptVisible = payload.value;
        },
        updateMainAptVisible(state, payload) {
            state.mainAptVisible = payload.value;
        }
    },
    actions: {
        updateSubAptVisible(context, payload) {
            context.commit('updateSubAptVisible', payload);
        },
        updateMainAptVisible(context, payload) {
            context.commit('updateMainAptVisible', payload);
        },
    },
    getters: {
        getSubAptVisible(state) {
            return state.subAptVisible;
        },
        getMainAptVisible(state) {
            return state.mainAptVisible;
        }
    }
};