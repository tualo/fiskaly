Ext.define('Tualo.Fiskaly.lazy.models.Setup', {
  extend: 'Ext.app.ViewModel',
  alias: 'viewmodel.fiskaly_setup',
  data: {
    message: 'OK',

    state: 1,
    devicetoken_message: -1,
    devicetoken_expires_in: -1,
    devicetoken_interval: 1000,
    devicetoken_user_code: -1,
    devicetoken_verification_uri: '',

    displayName: '',
    email: ''
  },
  formulas: {
    ftitle: function (get) {
      let txt = 'MS Login';
      return txt;
    },


  },
  stores: {
    messages: {
      type: 'store',
      autoLoad: false,
      fields: ['message', 'color', 'icon'],
      proxy: {
        type: 'ajax',
        url: './fiskaly/state',
        reader: {
          type: 'json',
          rootProperty: 'messages'
        }
      },
      listeners: {
        load: 'onMessagesLoad'
      }
    }

  }
});
