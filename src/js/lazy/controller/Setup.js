Ext.define('Tualo.Fiskaly.lazy.controller.Setup', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.fiskaly_setup',

    onBoxReady: function () {
        let me = this;
        setTimeout(me.queryState.bind(me), 1000)
    },

    queryState: async function () {
        let me = this;
        try {
            me.getViewModel().getStore('messages').load();
            /*
            let response = await fetch('./fiskaly/state');
            let data = await response.json();
            if (data.success) {

                /*
                me.getView().getComponent('startpanel').hide();
                me.getView().getComponent('devicetoken').hide();
                me.getView().getComponent('apiconfig').hide();
                me.getView().getComponent('user').show();
    
                me.getViewModel().set('displayName', data.data.displayName)
                me.getViewModel().set('mail', data.data.mail)
                * /

            } else {


            }
            */
        } catch (e) {
            console.error('Error querying state:', e);
            Ext.toast({
                title: 'Fehler',
                html: 'Es ist ein Fehler aufgetreten, die API antwortet nicht.',
                timeout: 5000,
                closable: true
            });
        }
    },

    onMessagesLoad: function (store, records, successful, operation, eOpts) {
        let me = this,
            view = me.getView(),
            startpanel = view.getComponent('startpanel'),
            messages = view.getComponent('messages');

        if (records.length > 0) {
            startpanel.hide();
            messages.show();
        } else {
            startpanel.show();
            messages.hide();
        }

        if (successful) {
            store.each(function (record) {
                console.log('Message:', record.get('message'));
            });
        } else {
            console.error('Failed to load messages:', operation.getError());
        }
    }

});
