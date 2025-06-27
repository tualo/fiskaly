Ext.define('Tualo.Fiskaly.lazy.dashboard.State', {
    requires: [
        // 'Ext.chart.CartesianChart'
    ],
    extend: 'Ext.dashboard.Part',
    alias: 'part.tualodashboard_fiskaly_state',


    viewTemplate: {
        title: 'Fiskaly Verbindung',


        items: [
            {
                xtype: 'panel',
                layout: 'fit',
                items: [],
                listeners: {
                    boxready: function (me) {
                        var elem = Ext.create('Tualo.Fiskaly.lazy.Setup');
                        me.add(elem);
                    }
                }
            }
        ]
    }
});