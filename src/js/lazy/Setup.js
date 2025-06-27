Ext.define('Tualo.Fiskaly.lazy.Setup', {
    extend: 'Ext.panel.Panel',
    requires: [
        'Tualo.Fiskaly.lazy.models.Setup',
        'Tualo.Fiskaly.lazy.controller.Setup'
    ],
    alias: 'widget.fiskaly_setup',
    controller: 'fiskaly_setup',

    viewModel: {
        type: 'fiskaly_setup'
    },
    listeners: {
        boxReady: 'onBoxReady'
    },

    layout: 'fit',
    /*
    bind:{
        title: '{ftitle}'
    },
    */
    items: [

        {
            hidden: false,
            xtype: 'panel',
            itemId: 'startpanel',
            layout: {
                type: 'vbox',
                align: 'center'
            },
            items: [
                {
                    xtype: 'component',
                    cls: 'lds-container-compact',
                    html: '<div class=" "><div class="blobs-container"><div class="blob gray"></div></div></div>'
                        + '<div><h3>Fiskaly Zugriff</h3>'
                        + '<span>Einen Moment, die Daten werden geprüft.</span></div>'
                }
            ]
        },
        {
            hidden: true,
            xtype: 'grid',
            itemId: 'messages',
            layout: {
                type: 'fit',
                align: 'center'
            },
            bind: {
                store: '{messages}',
            },
            columns: [
                {
                    text: ' ',
                    dataIndex: 'text',
                    width: 50,
                    renderer: function (value, metaData, record) {
                        let color = record.get('color') || 'black';
                        let icon = record.get('icon') || 'info';
                        metaData.style = `color: ${color};`;
                        return `<i class="fa fa-${icon}"></i>`;
                    }
                },
                {
                    text: 'Nachricht',
                    dataIndex: 'text',
                    flex: 1
                },
            ],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'bottom',
                items: [
                    {
                        xtype: 'button', text: 'Prüfen', bind: {
                            handler: 'checkToken'
                        }
                    }
                ]
            }],
        },
        {
            hidden: true,
            xtype: 'panel',
            itemId: 'user',
            layout: {
                type: 'vbox',
                align: 'center'
            },
            items: [
                {
                    xtype: 'component',
                    cls: 'lds-container-compact',
                    bind: {
                        html: '{userhtml}'
                    }
                }
            ],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'bottom',
                items: [
                    {
                        xtype: 'button', text: 'Erneuern', bind: {
                            handler: 'getDeviceToken'
                        }
                    }
                ]
            }]
        },
        {
            hidden: true,
            xtype: 'panel',
            itemId: 'apiconfig',
            layout: {
                type: 'vbox',
                align: 'center'
            },
            items: [
                {
                    xtype: 'form',
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Client ID',
                            bind: {
                                value: '{client_id}'
                            }
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Tenant ID',
                            bind: {
                                value: '{tenant_id}'
                            }
                        },
                        {
                            xtype: 'button',
                            text: 'Speichern',
                            bind: {
                                handler: 'saveConfig'
                            }
                        }
                    ]
                }
            ]
        }
    ],

});
