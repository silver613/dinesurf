<template>
    <div class="py-5 md:py-8 px-3 mx-auto sm:px-2 mb-5 bg-white">
        <div class="flex flex-wrap w-full justify-between mb-3 md:mb-0 md:px-6">
            <form
                @submit.prevent="filterData()"
                class="flex flex-wrap items-center md:order-first w-full mb-5 md:w-[40%]"
            >
                <div class="flex items-center w-full">
                    <div
                        class="relative rounded-lg text-gray-600 focus-within:text-gray-400 mb-4 w-full mt-5 mr-2 bg-red-300"
                    >
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-2"
                        >
                            <button
                                type="submit"
                                class="p-1 focus:outline-none focus:shadow-outline"
                            >
                                <svg
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                    class="w-6 h-4"
                                >
                                    <path
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                    ></path>
                                </svg>
                            </button>
                        </span>
                        <input
                            v-model="params.search"
                            type="search"
                            name="q"
                            class="pl-10 block rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs lg:text-sm mr-4 w-full"
                            placeholder="Search..."
                            autocomplete="off"
                        />
                    </div>

                    <div class="w-auto">
                        <filter-button
                            v-if="filterable"
                            :showFilter="showFilter"
                            :text="'Filters'"
                            @click="showFilter = !showFilter"
                            class="mb-3"
                        ></filter-button>
                    </div>
                </div>
                <div
                    v-if="showFilter"
                    class="flex flex-wrap items-center w-full"
                >
                    <div class="mb-3 w-[48%] mr-3" v-if="!noDateFilter">
                        <label class="block text-sm font-light text-gray-400"
                            >Start Date:</label
                        >
                        <input
                            v-model="start_date"
                            name="start_date"
                            id="start_date"
                            class="block form-control w-full"
                            type="date"
                        />
                    </div>
                    <div class="mb-3 w-[48%]" v-if="!noDateFilter">
                        <label class="block text-sm font-light text-gray-400"
                            >End Date:</label
                        >
                        <input
                            v-model="end_date"
                            name="end_date"
                            id="end_date"
                            class="block form-control w-full"
                            type="date"
                        />
                    </div>

                    <slot name="outerFilters" class="mb-3"> </slot>

                    <div class="w-[48%]">
                        <button
                            type="submit"
                            class="w-full rounded-md bg-dinesurf-green-500 text-white h-11 md:mt-4 active:bg-gray-900"
                        >
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            <div
                class="flex flex-col justify-center md:w-auto w-full items-center md:h-20 order-first md:order-second"
            >
                <div class="rounded-full bg-gray-100 p-2 mb-3">
                    <svg
                        width="27"
                        height="29"
                        viewBox="0 0 37 39"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M18.8646 15.7201C22.2662 15.7201 24.9876 13.1686 24.9876 10.0501C24.9876 6.93161 22.2662 4.38011 18.8646 4.38011C15.4629 4.38011 12.7416 6.93161 12.7416 10.0501C12.7416 13.1686 15.4629 15.7201 18.8646 15.7201ZM18.8646 19.5001C13.2618 19.5001 8.73959 15.2665 8.73959 10.0501C8.73959 4.83371 13.2618 0.600113 18.8646 0.600113C24.4673 0.600113 28.9896 4.83371 28.9896 10.0501C28.9896 15.2665 24.4673 19.5001 18.8646 19.5001ZM4.85768 34.6201H32.8715V32.1064C32.8715 28.7989 28.2492 25.378 18.8646 25.378C9.47995 25.378 4.85768 28.7989 4.85768 32.1064V34.6201ZM18.8646 21.598C32.1911 21.598 36.8734 27.8917 36.8734 32.1064V38.4001H0.855713V32.1064C0.855713 27.8917 5.53802 21.598 18.8646 21.598Z"
                            fill="#9DC64E"
                        />
                    </svg>
                </div>

                <span
                    class="ml-2 capitalize text-gray-400 text-xs"
                    v-if="extendedTitle"
                    >Total {{ extendedTitle }}</span
                >
                <span class="ml-2 capitalize text-gray-400 text-xs" v-else>
                    Total {{ title }}s</span
                >
                <span>{{ models.total }}</span>
            </div>

            <div class="flex justify-between md:mt-4">
                <div v-if="actions.length > 0" class="mr-3">
                    <div class="flex w-full" v-if="this.UseGuestAction">
                        <button
                            @click="runAction('email_users')"
                            class="h-10 bg-dinesurf-green-600 text-white md:w-[5rem] w-full rounded-md p-1 px-2 mr-2"
                        >
                            Send Mail
                        </button>
                        <button
                            @click="runAction('text_users')"
                            class="bg-dinesurf-green-600 text-white md:w-[5rem] w-full rounded-md p-1 px-2 h-10 mr-2"
                        >
                            Send Text
                        </button>
                        <!-- <a
                            :href="route('vendors.export-guests')"
                            class="text-black border md:w-[5rem] w-full rounded-md p-1 px-2 h-10 mr-2"
                        >
                            Export
                        </a> -->
                    </div>

                    <select
                        v-else
                        v-show="!noAction"
                        :id="'general_actions'"
                        class="form-control text-xs w-full lg:text-sm h-9 md:h-auto"
                        @change="runAction($event.target.value)"
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
                </div>
                <div>
                    <button
                        v-if="noAction && checkIfAsDelete"
                        @click="runAction('delete')"
                        class="bg-dinesurf-red-600 text-white font-semibold uppercase border md:w-[8rem] w-full rounded-md p-1 px-2 h-10 mr-2"
                    >
                        Delete
                    </button>
                </div>

                <div v-if="canCreate && !createAsButton" class="flex  ">
                    <Link
                        class="jet-btn w-full active:bg-gray-900 text-xs  h-10"
                        :href="createLink"
                        as="button" type="button"
                    >
                       <div class="mr-2">
                          <create-icon />
                        </div>
                         
                        Create {{ title }}
                    </Link>
                </div>

                <div v-if="canCreate && createAsButton" class="flex">
                    <button
                        class="jet-btn w-full active:bg-gray-900 text-xs h-10"
                        @click="createButton()"
                    >
                    <div class="mr-2">
                          <create-icon />
                     </div>
                        Create {{ title }}
                </button>
                </div>
            </div>
        </div>

        <div class="overflow-hidden md:pl-7 md:pr-10 sm:rounded-lg">
            <div class="flex flex-col">
                <div class="overflow-x-auto -my-2 -mx-2 lg:-mx-4">
                    <div class="inline-block py-2 min-w-full align-middle px-3">
                        <div
                            class="border-b border-gray-200 sm:rounded-lg w-[5rem]"
                        >
                            <table class="table table-auto">
                                <thead class="border-b text-gray-400">
                                    <tr class="w-full">
                                        <th
                                            scope="col"
                                            class="pl-2 py-3 px-10 text-xs font-semibold tracking-wider text-left text-gray-400 uppercase flex flex-col"
                                        >
                                            <div class="form-check mb-1">
                                                <input
                                                    class="form-check-input checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600 checkbox select_all"
                                                    type="checkbox"
                                                    :model="select_all"
                                                    id="selectAll"
                                                    ref="input"
                                                    @change="select('all')"
                                                />
                                            </div>
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600 checkbox select_all_matching"
                                                    type="checkbox"
                                                    :model="select_all_matching"
                                                    id="selectAllMatching"
                                                    ref="input"
                                                    @change="
                                                        select('all_matching')
                                                    "
                                                />
                                            </div>
                                        </th>
                                        <th
                                            scope="col"
                                            class="py-3 px-2 text-left text-xs font-bold tracking-wider text-left text-gray-400 capitalize md:w-32"
                                            v-for="(column, index) in columns"
                                            :key="index"
                                        >
                                            <span
                                                v-if="column.sortable"
                                                class="inline-flex px-2 justify-start items-center font-bold cursor-pointer"
                                                @click="sort(column.db_name)"
                                            >
                                                <span class="mr-1 w-min">{{
                                                    column.name
                                                }}</span>
                                                <span
                                                    class="fa fa-angle-down text-xs text-dinesurf-green-400"
                                                    v-if="
                                                        params.field ===
                                                            column.db_name &&
                                                        params.direction ===
                                                            'desc'
                                                    "
                                                ></span>

                                                <span
                                                    class="fa fa-angle-up text-xs text-dinesurf-green-400"
                                                    v-if="
                                                        params.field ===
                                                            column.db_name &&
                                                        params.direction ===
                                                            'asc'
                                                    "
                                                ></span>
                                            </span>
                                            <span v-else class="font-bold">{{
                                                column.name
                                            }}</span>
                                        </th>
                                        <th
                                            v-if="actions.length > 0 || canView"
                                            scope="col"
                                            class="py-3 text-xs font-semibold tracking-wider text-left text-white uppercase md:w-32"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <draggable
                                    class="bg-white divide-y divide-gray-200"
                                    v-if="loaded"
                                    @change="
                                        isDraggable
                                            ? runAction('dragged', null)
                                            : console.log('dragged')
                                    "
                                    tag="tbody"
                                    :list="models.data"
                                >
                                    <!-- <tbody > -->
                                    <template v-if="models.data.length > 0">
                                        <tr
                                            aria-rowspan="12"
                                            class="hover:shadow-lg hover:border-l-4 h-9 border-l-blue-400 cursor-move w-[40rem]"
                                            v-for="(
                                                model, modelIndex
                                            ) in models.data"
                                            :key="model.id"
                                        >
                                            <td
                                                class="py-4 px-2 text-left text-xs lg:text-sm text-gray-500 whitespace-nowrap"
                                            >
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input checked:bg-blue-600 checked:border-blue-600 checkbox"
                                                        type="checkbox"
                                                        :model="
                                                            selectedModels[
                                                                model.id
                                                            ]
                                                        "
                                                        :id="'model' + model.id"
                                                        ref="input"
                                                        @change="
                                                            selectedModels[
                                                                model.id
                                                            ] =
                                                                !selectedModels[
                                                                    model.id
                                                                ]
                                                        "
                                                    />
                                                </div>
                                            </td>
                                            <td
                                                class="text-xs lg:text-sm text-gray-500 px-2 text-left"
                                                v-for="(item, rowIndex) in rows[
                                                    modelIndex
                                                ]"
                                                :key="rowIndex"
                                                v-html="item"
                                            ></td>

                                            <td
                                                class="py-4 px-2 text-xs lg:text-sm text-gray-500 whitespace-nowrap flex justify-center content-center items-center"
                                            >
                                                <Link
                                                    v-if="canView  && !editAsButton"
                                                    :href="
                                                        viewLink +
                                                        '/' +
                                                        model.id
                                                    "
                                                    class="edit_row mr-5 text-lg"
                                                    id="editRow"
                                                >
                                                    <svg
                                                        width="17"
                                                        height="20"
                                                        viewBox="0 0 17 20"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M15.2516 5.59269C15.6139 5.23044 15.8132 4.74936 15.8132 4.23761C15.8132 3.72586 15.6139 3.24478 15.2516 2.88253L13.7317 1.36261C13.3695 1.00036 12.8884 0.801025 12.3766 0.801025C11.8649 0.801025 11.3838 1.00036 11.0225 1.36165L0.833496 11.519V15.7501H5.06262L15.2516 5.59269ZM12.3766 2.71769L13.8975 4.23665L12.3737 5.75465L10.8538 4.23569L12.3766 2.71769ZM2.75016 13.8334V12.3144L9.49683 5.58886L11.0167 7.10878L4.27104 13.8334H2.75016ZM0.833496 17.6667H16.1668V19.5834H0.833496V17.6667Z"
                                                            fill="#3B82F6"
                                                        />
                                                    </svg>
                                                </Link>

                                                <button  v-if="editAsButton"  @click="editButton(model.id)">
                                                    <svg
                                                        width="17"
                                                        height="20"
                                                        viewBox="0 0 17 20"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M15.2516 5.59269C15.6139 5.23044 15.8132 4.74936 15.8132 4.23761C15.8132 3.72586 15.6139 3.24478 15.2516 2.88253L13.7317 1.36261C13.3695 1.00036 12.8884 0.801025 12.3766 0.801025C11.8649 0.801025 11.3838 1.00036 11.0225 1.36165L0.833496 11.519V15.7501H5.06262L15.2516 5.59269ZM12.3766 2.71769L13.8975 4.23665L12.3737 5.75465L10.8538 4.23569L12.3766 2.71769ZM2.75016 13.8334V12.3144L9.49683 5.58886L11.0167 7.10878L4.27104 13.8334H2.75016ZM0.833496 17.6667H16.1668V19.5834H0.833496V17.6667Z"
                                                            fill="#3B82F6"
                                                        />
                                                    </svg>
                                                </button>



                                                <span
                                                    v-if="deleteAsButton"
                                                >
                                                    <span
                                                        @click="
                                                            runAction(
                                                                'delete',
                                                                model.id
                                                            )
                                                        "
                                                        id="deleteRow"
                                                        class="delete_row cursor-pointer mx-3"
                                                        v-if="
                                                            this.actions[0]
                                                                .value ==
                                                                'delete' ||
                                                            this.actions[0]
                                                                .value ==
                                                                'remove_admin' ||
                                                            noAction
                                                        "
                                                    >
                                                        <i
                                                            class="fa fa-trash text-dinesurf-red-400"
                                                        ></i>
                                                    </span>
                                                </span>

                                                <label
                                                    class="relative inline-flex items-center cursor-pointer mx-5 mb-5"
                                                    v-if="
                                                        this.actions?.find(
                                                            (item) =>
                                                                item.value ===
                                                                'toggle'
                                                        )
                                                    "
                                                >
                                                    <input
                                                        type="checkbox"
                                                        value=""
                                                        class="sr-only peer"
                                                        @click="
                                                            runAction(
                                                                'toggle',
                                                                model.id
                                                            )
                                                        "
                                                        :checked="
                                                            model.status ===
                                                            'on'
                                                        "
                                                    />
                                                    <div
                                                        class="w-11 h-6 mt-5 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1.4rem] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
                                                    ></div>
                                                </label>

                                                <!-- <div>

                            </div> -->

                                                <select
                                                    v-if="
                                                        actions.length > 0 &&
                                                        this.actions.length !=
                                                            1 &&
                                                        !noAction
                                                    "
                                                    :id="
                                                        'model_actions' +
                                                        model.id
                                                    "
                                                    class="form-control text-xs lg:text-sm"
                                                    style="padding-right: 24px !important; "
                                                    @change="
                                                        runAction(
                                                            $event.target.value,
                                                            model.id
                                                        )
                                                    "
                                                >
                                                    <option :value="'null'">
                                                        Actions
                                                    </option>
                                                    <option
                                                        v-for="(
                                                            action, actionIndex
                                                        ) in actions"
                                                        :key="actionIndex"
                                                        :value="action.value"
                                                    >
                                                        {{ action.name }}
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-if="models.data.length <= 0">
                                        <tr aria-rowspan="12" class="w-auto">
                                            <td
                                                class="py-2 px-2 pb-10"
                                                :colspan="columns.length + 2"
                                            >
                                                <!-- No Records Available here.. -->
                                                <slot name="notFound"></slot>
                                            </td>
                                        </tr>
                                    </template>
                                </draggable>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                    v-if="!loaded"
                                >
                                    <template v-if="loading">
                                        <tr>
                                            <td
                                                :colspan="columns.length + 2"
                                                class="py-2 px-2 relative overflow-hidden"
                                            >
                                                <div
                                                    class="flex items-center justify-center z-30 p-6"
                                                    style="min-height: 150px"
                                                >
                                                    <span class="mr-2"
                                                        >Loading</span
                                                    >
                                                    <svg
                                                        class="block text-gray-300"
                                                        viewBox="0 0 120 30"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor"
                                                        style="width: 50px"
                                                    >
                                                        <circle
                                                            cx="15"
                                                            cy="15"
                                                            r="15"
                                                        >
                                                            <animate
                                                                attributeName="r"
                                                                from="15"
                                                                to="15"
                                                                begin="0s"
                                                                dur="0.8s"
                                                                values="15;9;15"
                                                                calcMode="linear"
                                                                repeatCount="indefinite"
                                                            ></animate>
                                                            <animate
                                                                attributeName="fill-opacity"
                                                                from="1"
                                                                to="1"
                                                                begin="0s"
                                                                dur="0.8s"
                                                                values="1;.5;1"
                                                                calcMode="linear"
                                                                repeatCount="indefinite"
                                                            ></animate>
                                                        </circle>
                                                        <circle
                                                            cx="60"
                                                            cy="15"
                                                            r="9"
                                                            fill-opacity="0.3"
                                                        >
                                                            <animate
                                                                attributeName="r"
                                                                from="9"
                                                                to="9"
                                                                begin="0s"
                                                                dur="0.8s"
                                                                values="9;15;9"
                                                                calcMode="linear"
                                                                repeatCount="indefinite"
                                                            ></animate>
                                                            <animate
                                                                attributeName="fill-opacity"
                                                                from="0.5"
                                                                to="0.5"
                                                                begin="0s"
                                                                dur="0.8s"
                                                                values=".5;1;.5"
                                                                calcMode="linear"
                                                                repeatCount="indefinite"
                                                            ></animate>
                                                        </circle>
                                                        <circle
                                                            cx="105"
                                                            cy="15"
                                                            r="15"
                                                        >
                                                            <animate
                                                                attributeName="r"
                                                                from="15"
                                                                to="15"
                                                                begin="0s"
                                                                dur="0.8s"
                                                                values="15;9;15"
                                                                calcMode="linear"
                                                                repeatCount="indefinite"
                                                            ></animate>
                                                            <animate
                                                                attributeName="fill-opacity"
                                                                from="1"
                                                                to="1"
                                                                begin="0s"
                                                                dur="0.8s"
                                                                values="1;.5;1"
                                                                calcMode="linear"
                                                                repeatCount="indefinite"
                                                            ></animate>
                                                        </circle>
                                                    </svg>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-if="!loading">
                                        <tr>
                                            <td
                                                :colspan="columns.length + 2"
                                                class="py-2 px-2 relative"
                                            >
                                                <div
                                                    class="bg-white rounded-lg shadow flex flex-col justify-center items-center px-6 py-8"
                                                >
                                                    <svg
                                                        class="inline-block text-gray-500"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="65"
                                                        height="51"
                                                        viewBox="0 0 65 51"
                                                    >
                                                        <path
                                                            class="fill-current"
                                                            d="M56 40h2c.552285 0 1 .447715 1 1s-.447715 1-1 1h-2v2c0 .552285-.447715 1-1 1s-1-.447715-1-1v-2h-2c-.552285 0-1-.447715-1-1s.447715-1 1-1h2v-2c0-.552285.447715-1 1-1s1 .447715 1 1v2zm-5.364125-8H38v8h7.049375c.350333-3.528515 2.534789-6.517471 5.5865-8zm-5.5865 10H6c-3.313708 0-6-2.686292-6-6V6c0-3.313708 2.686292-6 6-6h44c3.313708 0 6 2.686292 6 6v25.049375C61.053323 31.5511 65 35.814652 65 41c0 5.522847-4.477153 10-10 10-5.185348 0-9.4489-3.946677-9.950625-9zM20 30h16v-8H20v8zm0 2v8h16v-8H20zm34-2v-8H38v8h16zM2 30h16v-8H2v8zm0 2v4c0 2.209139 1.790861 4 4 4h12v-8H2zm18-12h16v-8H20v8zm34 0v-8H38v8h16zM2 20h16v-8H2v8zm52-10V6c0-2.209139-1.790861-4-4-4H6C3.790861 2 2 3.790861 2 6v4h52zm1 39c4.418278 0 8-3.581722 8-8s-3.581722-8-8-8-8 3.581722-8 8 3.581722 8 8 8z"
                                                        ></path>
                                                    </svg>
                                                    <h3
                                                        class="text-base font-normal mt-3 text-red-500"
                                                    >
                                                        Failed to load
                                                        <span
                                                            v-if="extendedTitle"
                                                            >{{
                                                                extendedTitle
                                                            }}</span
                                                        ><span v-else
                                                            >{{ title }}s</span
                                                        >!
                                                    </h3>

                                                    <button
                                                        class="jet-btn active:bg-gray-900"
                                                        @click="filterData()"
                                                    >
                                                        Reload
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <pagination
            class="mt-10 pl-5"
            :links="models.links"
            v-if="models.data.length > 8"
        />
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
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling
import { VueDraggableNext } from "vue-draggable-next";

export default {
    components: {
        Checkbox,
        DropdownLink,
        Dropdown,
        FilterButton,
        Actions,
        Actions2,
        Actions3,
        draggable: VueDraggableNext,
    },
    props: {
        models: Object,
        title: String,
        extendedTitle: String,
        filters: Object,
        actions: Array,
        externalFilters: Object,
        loading: Boolean,
        loaded: Boolean,
        isDropDown: Boolean,
        UseGuestAction: Boolean,
        noDateFilter: Boolean,
        isDraggable: Boolean,
        noAction: Boolean,
        createAsButton: Boolean,
        editAsButton: Boolean,
        deleteAsButton: Boolean,
        rows: {
            type: Array,
            required: true,
        },
        columns: {
            type: Array,
            required: true,
        },
        createLink: {
            type: String,
            default: null,
        },
        viewLink: {
            type: String,
            default: null,
        },
        indexLink: {
            type: String,
            default: null,
        },
        filterable: {
            type: Boolean,
            default: true,
        },
        canCreate: {
            type: Boolean,
            default: true,
        },
        canView: {
            type: Boolean,
            default: true,
        },
        canCreate: {
            type: Boolean,
            default: true,
        },
        canView: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            select_all: false,
            select_all_matching: false,
            start_date: this.filters?.start_date,
            end_date: this.filters?.end_date,
            showFilter: false,
            selectedModels: [],
            params: {
                search: this.filters?.search,
                field: this.filters?.field ? this.filters?.field : "id",
                direction: this.filters?.direction
                    ? this.filters?.direction
                    : "desc",
            },
        };
    },
    methods: {
        checkIfAsDelete() {
            const findDelete = this.actions.find(
                (item) => item.value === "delete"
            );

            if (findDelete) {
                return true;
            }
        },
        select(type) {
            if (type == "all") {
                this.select_all = !this.select_all;

                var checkbox = document.getElementById("selectAllMatching");
                if (checkbox) {
                    if (checkbox.checked) {
                        checkbox.checked = false;
                        this.select_all_matching = false;
                    }
                }
            }

            if (type == "all_matching") {
                this.select_all_matching = !this.select_all_matching;
                var checkbox = document.getElementById("selectAll");
                if (checkbox) {
                    this.select_all = this.select_all_matching;
                    checkbox.checked = this.select_all_matching;
                }
            }

            if (this.select_all == true) {
                this.selectedModels.forEach((el, index) => {
                    var checkbox = document.getElementById("model" + index);
                    if (checkbox) {
                        if (!checkbox.checked) {
                            checkbox.click();
                        }
                    }
                });
            } else {
                this.selectedModels.forEach((el, index) => {
                    var checkbox = document.getElementById("model" + index);
                    if (checkbox) {
                        if (checkbox.checked) {
                            checkbox.click();
                        }
                    }
                });
            }
        },
        filterData() {
            this.$inertia.get(
                this.indexLink,
                {
                    start_date: this.start_date,
                    end_date: this.end_date,
                    ...this.externalFilters,
                    ...this.params,
                },
                {
                    replace: true,
                    preserveState: true,
                    onFinish: (visit) => {
                        this.loadData();
                        this.setModels();
                    },
                }
            );
        },
        editButton(id) {
            this.$emit("editButton", { id });
        },
        createButton() {
            this.$emit("createButton");
        },
        loadData() {
            this.$emit("loadData");
        },
        sort(field) {
            this.params.field = field;
            this.params.direction =
                this.params.direction === "asc" ? "desc" : "asc";
        },
        setModels() {
            this.selectedModels = [];
            this.models.data.forEach((el) => {
                this.selectedModels[el.id] = false;
            });

            if (this.models.total) {
                tippy(".select_all", {
                    content: "Select All",
                });

                tippy(".select_all_matching", {
                    content: "Select All Matching (" + this.models.total + ")",
                });
            }
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
            this.$emit("runAction", {
                action: action,
                ids: ids,
                all: this.select_all_matching,
            });

            if (id) {
                document.getElementById("model_actions" + id).value = null;
            }
            var general = document.getElementById("general_actions");
            if (general) {
                general.value = null;
            }
        },
    },
    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);

                this.$inertia.get(
                    this.indexLink,
                    {
                        ...params,
                        start_date: this.start_date,
                        end_date: this.end_date,
                        ...this.externalFilters,
                    },
                    {
                        replace: true,
                        preserveState: true,
                        onFinish: (visit) => {
                            // console.log("visit:", visit)
                            this.loadData();
                            this.setModels();
                        },
                    }
                );
            }, 150),
            deep: true,
        },
        models: {
            handler: throttle(function () {
                this.setModels();
            }, 150),
            deep: true,
        },
    },
    mounted() {
        this.checkIfAsDelete();
        this.setModels();

        tippy("#deleteRow", {
            content: "Delete/Remove this row",
        });

        tippy("#editRow", {
            content: "Edit this row",
        });
    },
};
</script>
