Ext.define('DockUI.SideBar', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.dockuisidebar',
	title: 'Sidebar',
    animCollapse: true,
    width: 200,
    minWidth: 150,
    maxWidth: 400,
    split: true,
    collapsible: true,
    applications: [],
    layout: {
        type: 'accordion'
    },
    layoutConfig:{
        animate: true
    },
    render: function(){
    	this.callParent(arguments);
		//this.dd = new DockUI.SidebarDropTarget(this, {
		//	sidebar: this
		//});
    }
});