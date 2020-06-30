<template>
    <default-field :field="field" :full-width-content="true">
        <template slot="field">
            <div class="w-full mb-4">
                <span class="ml-auto btn btn-primary btn-default mr-3 custom-button" @click="checkAll()">{{ __('Select all')}}</span>
                <span class="ml-auto btn btn-primary btn-default custom-button" @click="uncheckAll()">{{ __('Clear Selection') }}</span>
            </div>

            <div class="flex flex-wrap" v-if="field.withGroups">
                <div
                        v-for="(permissions, group) in field.options"
                        :key="group"
                        class="mb-2 pl-2 w-1/2"
                >

                    <div class="cursor-pointer flex items-center px-2 py-2 bg-40 rounded-lg" @click="showItem(group)">
                        <div class="w-full flex items-center">
                            <h3 class="capitalize flex-1">{{ fixNaming(group) }}</h3>
                            <div class="flex flex-wrap">
                                <div
                                        v-for="(permission, option) in permissions"
                                        :key="permission.option"
                                        class="pr-2"
                                >
                                    <span class="inline-block rounded-full w-2 h-2" :class="optionClass(permission.option)"></span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <span class="font-bold text-xl" v-if="activeItem === group">&minus;</span>
                            <span class="font-bold text-xl" v-else>&plus;</span>
                        </div>
                    </div>

                    <div v-show="activeItem === group" class="w-1/3 bg-20 px-2 py-2 border-l border-r border-b border-50 rounded-b-lg absolute z-50">
                        <div
                                v-for="(permission, option) in permissions"
                                :key="permission.option"
                                class="px-1 py-1 items-center"
                        >
                            <checkbox
                                    :value="permission.option"
                                    :checked="isChecked(permission.option)"
                                    @input="toggleOption(permission.option)"
                                    class="pr-2"
                            ></checkbox>
                            <label
                                    :for="field.name"
                                    v-text="fixNaming(permission.label)"
                                    @click="toggleOption(permission.option)"
                            ></label>
                        </div>
                    </div>
                </div>
            </div>
            <p
                    v-if="hasError"
                    class="my-2 text-danger"
            >{{ firstError }}</p>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from "laravel-nova";
    import VTooltip from 'v-tooltip'
    export default {
        component: [ VTooltip ],
        mixins: [FormField, HandlesValidationErrors],
        props: ["resourceName", "resourceId", "field"],
        data() {
            return {
                activeItem: null,
            }
        },
        methods: {
            showItem(group) {
                if(!this.activeItem) {
                    this.activeItem = group
                }
                else if(this.activeItem !== group) {
                    this.activeItem = group
                }
                else {
                    this.activeItem = null
                }
            },
            checkAll() {
                // With Groups
                if (this.field.withGroups) {
                    let permissions = _.flatMap(this.field.options);
                    for (var i = 0; i < permissions.length; i++) {
                        this.check(permissions[i].option);
                    }
                }
            },
            uncheckAll() {
                // With Groups
                if (this.field.withGroups) {
                    let permissions = _.flatMap(this.field.options);
                    for (var i = 0; i < permissions.length; i++) {
                        this.uncheck(permissions[i].option);
                    }
                }
            },
            check(option) {
                if (!this.isChecked(option)) {
                    this.value.push(option);
                }
            },
            uncheck(option) {
                if (this.isChecked(option)) {
                    this.$set(this, "value", this.value.filter(item => item !== option));
                }
            },
            toggleOption(option) {
                if (this.isChecked(option)) {
                    return this.uncheck(option);
                }
                this.check(option);
            },
            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
                this.value = this.field.value || [];
            },
            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                formData.append(this.field.attribute, this.value || []);
            },
            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = value;
            },
            groupName: (group) => {
                return group.replace('_', ' ')
            },
            fixNaming: (name) => {
                if(name.includes("_") === true) {
                    name = name.replace('_', ' ')
                }
                if(name.includes("-") === true) {
                    name = name.replace('-', ' ')
                }
                return name
            },
            isChecked(option) {
                return this.value ? this.value.includes(option) : false;
            },
            optionClass(option) {
                return {
                    'bg-success': this.value ? this.value.includes(option) : false,
                    'bg-danger': this.value ? !this.value.includes(option) : true,
                }
            },
        }
    };
</script>

<style>
    .custom-button:hover {
        cursor: pointer;
    }
</style>