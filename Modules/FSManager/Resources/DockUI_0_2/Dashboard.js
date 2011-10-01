Ext.define('DockUI.Dashboard', {
	extend: 'Ext.panel.Panel',
	
	alias: 'widget.dockuidashboard',
    /*animCollapse: true,
    collapsible: true,
    */
    layout: {
        type: 'hbox',
        align:'stretch'
    },
    layoutConfig:{
        animate: true
    },
    render: function(){
    	this.callParent(arguments);
    }
});