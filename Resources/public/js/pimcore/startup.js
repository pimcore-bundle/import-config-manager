pimcore.registerNS("pimcore.plugin.ImportConfigManagerBundle");

pimcore.plugin.ImportConfigManagerBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.ImportConfigManagerBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("ImportConfigManagerBundle ready!");
    }
});

var ImportConfigManagerBundlePlugin = new pimcore.plugin.ImportConfigManagerBundle();
