<template>
  <div>
    <div class="mx-auto max-w-7xl">
      <div
        class="
          overflow-hidden
          bg-white
          shadow-md
          sm:rounded-b-lg sm:rounded-t-none
        "
      >
        <div class="flex flex-col">
          <div class="overflow-x-auto -my-2 -mx-2 lg:-mx-4">
            <div class="inline-block py-2 min-w-full align-middle px-3">
              <div
                class="
                  overflow-hidden
                  border-b border-gray-200
                  shadow
                  sm:rounded-b-lg sm:rounded-t-none
                "
              >
                <table class="table table-auto">
                  <thead class="border-b  text-gray-400">
                    <tr>
                      <th
                        scope="col"
                        class="
                          py-3
                          px-2
                          w-3/12
                          text-xs
                          font-semibold
                          tracking-wider
                          text-left text-white
                          uppercase
                        "
                      >
                        #
                      </th>
                      <th
                        scope="col"
                        class="
                          py-3
                          px-2
                          w-3/12
                          text-xs
                          font-semibold
                          tracking-wider
                          text-left text-white
                          uppercase
                        "
                        v-for="(column, index) in columns"
                        :key="index"
                      >
                        <span class="font-bold">{{ column.name }}</span>
                      </th>
                      <th
                        scope="col"
                        class="
                          py-3
                          px-2
                          w-3/12
                          text-xs
                          font-semibold
                          tracking-wider
                          text-left text-white
                          uppercase
                        "
                      >
                        Actions
                      </th>
                    </tr>
                  </thead>

                  <tbody class="bg-white divide-y divide-gray-200">
                    <template v-if="models.length > 0">
                      <tr v-for="(model, modelIndex) in models" :key="model.id">
                        <td
                          class="
                            py-4
                            px-2
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap
                          "
                        >
                          <div class="form-check">
                            <input
                              class="
                                form-check-input
                                appearance-none
                                h-4
                                w-4
                                border border-gray-400
                                rounded-sm
                                bg-white
                                checked:bg-blue-600 checked:border-blue-600
                                focus:outline-none
                                transition
                                duration-200
                                my-1
                                align-top
                                bg-no-repeat bg-center bg-contain
                                float-left
                                cursor-pointer
                              "
                              type="checkbox"
                              :model="selectedModels[model.id]"
                              :id="'model' + model.id"
                              ref="input"
                              @change="
                                selectedModels[model.id] =
                                  !selectedModels[model.id]
                              "
                            />
                          </div>
                        </td>
                        <td
                          class="
                            py-4
                            px-2
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap
                          "
                          v-for="(item, rowIndex) in rows[modelIndex]"
                          :key="rowIndex"
                          v-html="item"
                        ></td>

                        <td
                          class="
                            py-4
                            px-2
                            text-xs
                            lg:text-sm
                            text-gray-500
                            whitespace-nowrap
                            flex
                            justify-start
                          "
                        >
                          <Link
                            :href="viewLink + '?id=' + model.id"
                            class="mr-2 text-lg"
                            ><i class="far fa-eye"></i
                          ></Link>
                          <select
                            :id="'model_actions' + model.id"
                            class="form-control text-xs lg:text-sm"
                            @change="runAction($event.target.value, model.id)"
                          >
                            <option :value="'null'">Actions</option>
                            <option
                              v-for="(action, actionIndex) in actions"
                              :key="actionIndex"
                              :value="action.value"
                            >
                              {{ action.name }}
                            </option>
                          </select>
                        </td>
                      </tr>
                    </template>
                    <tr v-else>
                      <td class="py-2 px-2">No Records Available</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Dropdown from "@/Jetstream/Dropdown.vue";
import DropdownLink from "@/Jetstream/DropdownLink.vue";
import Checkbox from "@/components/Checkbox.vue";
import Actions from "@/components/Actions.vue";
import Actions2 from "@/components/Actions2.vue";
import Actions3 from "@/components/Actions3.vue";
import FilterButton from "@/components/FilterButton.vue";
import { pickBy, throttle } from "lodash";

export default {
  components: {
    Checkbox,
    DropdownLink,
    Dropdown,
    FilterButton,
    Actions,
    Actions2,
    Actions3,
  },
  props: {
    models: Object,
    title: String,
    actions: Array,
    rows: {
      type: Array,
      required: true,
    },
    columns: {
      type: Array,
      required: true,
    },
    viewLink: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      selectedModels: [],
    };
  },
  methods: {
    setModels() {
      this.selectedModels = [];
      this.models.forEach((el) => {
        this.selectedModels[el.id] = false;
      });
    },
    runAction(action, id = null) {
      if (id) {
        document.getElementById("model" + id).checked = true;
        this.selectedModels[id] = true;
      }

      var ids = [];
      this.selectedModels.forEach((el, index) => {
        if (el) {
          ids.push(index);
        }
      });
      //   Export Action TO Parent
      this.$emit("runAction", { action: action, ids: ids });

      if (id) {
        document.getElementById("model_actions" + id).value = null;
      }
      var general = document.getElementById("general_actions");
      if (general) {
        general.value = null;
      }
    },
  },
  mounted() {
    this.setModels();
  },
};
</script>
