Ext.define('DockUI.DashboardColumn', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.dockuidashboardcolumn',
    layout: {
        type: 'vbox',
        align:'stretch'
    },
    layoutConfig:{
        animate: true
    },
    render: function(){
    	this.callParent(arguments);
    }
});