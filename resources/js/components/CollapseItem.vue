<template>
    <div class="">
        <div class="flex items-center px-2 py-2 bg-40 rounded-lg">
            <h3 class="capitalize">{{ name }}</h3>
            <div class="ml-auto">
                <span class="cursor-pointer font-bold text-xl" v-if="showCollapse" @click="toggleCollapse">&minus;</span>
                <span class="cursor-pointer font-bold text-xl" v-else @click="toggleCollapse">&plus;</span>
            </div>
        </div>

        <div v-show="showCollapse" class="w-1/3 bg-20 px-2 py-2 border-l border-r border-b border-50 rounded-b-lg absolute">
            <div
                    v-for="(permission, option) in permissions"
                    :key="option"
                    class="flex-auto"
            >
                <span
                        class="inline-block rounded-full w-2 h-2"
                        :class="optionClass(permission.option)"
                />
                <span>{{ fixNaming(permission.label) }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CollapseItem",
        props: [ 'name', 'permissions', 'value' ],
        data() {
            return {
                showCollapse: false,
                activeItem: null,
            }
        },
        methods: {
            toggleCollapse() {
                this.showCollapse = !this.showCollapse
            },
            optionClass(option) {
                return {
                    'bg-success': this.value ? this.value.includes(option) : false,
                    'bg-danger': this.value ? !this.value.includes(option) : true,
                }
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