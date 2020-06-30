<template>
    <panel-item :field="field">
        <p slot="value" class="text-90">
            <span class="flex flex-wrap">
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
                                :key="option"
                                class="flex-auto"
                        >
                            <span
                                    class="inline-block rounded-full w-2 h-2"
                                    :class="optionClass(permission.option)"></span>
                            <span>{{ fixNaming(permission.label) }}</span>
                        </div>
                    </div>
                    <!--                    <collapse-item :name="fixNaming(group)" :permissions="permissions" :value="field.value"></collapse-item>-->
                </div>
            </span>
        </p>
    </panel-item>
</template>

<script>
    import CollapseItem from "./CollapseItem";
    export default {
        components: { CollapseItem },
        props: ['resource', 'resourceName', 'resourceId', 'field'],
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
            optionClass(option) {
                return {
                    'bg-success': this.field.value ? this.field.value.includes(option) : false,
                    'bg-danger': this.field.value ? !this.field.value.includes(option) : true,
                }
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
            }
        }
    }
</script>