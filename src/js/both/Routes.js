Ext.define('Tualo.routes.Fiskaly.Setup', {
    statics: {
        load: async function () {
            return [
                {
                    name: 'fiskaly/setup',
                    path: '#fiskaly/setup'
                }
            ]
        }
    },
    url: 'fiskaly/setup',
    handler: {
        action: function () {

            let mainView = Ext.getApplication().getMainView(),
                stage = mainView.getComponent('dashboard_dashboard').getComponent('stage'),
                component = null,
                cmp_id = 'fiskaly_setup';
            component = stage.down(cmp_id);
            if (component) {
                stage.setActiveItem(component);
            } else {
                Ext.getApplication().addView('Tualo.Fiskaly.lazy.Setup', {

                });
            }


        },
        before: function (action) {

            action.resume();
        }
    }
});