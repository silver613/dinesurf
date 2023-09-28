import IndexPage from "@/Pages/Index.vue";
import "@/tests/init-test";
import { shallowMount } from "@vue/test-utils";

describe('Index Page', () => {
    test("Render without crash", () => {
        const wrapper = shallowMount(IndexPage);
        expect(wrapper).toBeTruthy();
    });
})
