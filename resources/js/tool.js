Nova.booting((Vue, router) => {
    Vue.component('index-PermissionCheckBox', require('./components/IndexField'));
    Vue.component('detail-PermissionCheckBox', require('./components/DetailField'));
    Vue.component('form-PermissionCheckBox', require('./components/FormField'));

});
