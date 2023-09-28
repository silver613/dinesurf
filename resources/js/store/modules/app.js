const app = {
    state: () => ({
        data: {},
        vendorSidebar: {
            menuOpen: true,
        },
    }),
    mutations: {
        setData(state, data) {
            state.data = data;
        },
        toggleMenuOpen(state) {
            state.vendorSidebar.menuOpen = !state.vendorSidebar.menuOpen;
        },
    },
    getters: {
        vendorSidebar(state) {
            return state.vendorSidebar;
        },
    },
};

export default app;
