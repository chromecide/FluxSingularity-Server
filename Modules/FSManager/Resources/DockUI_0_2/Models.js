Ext.define('DockUIDashboardColumn', {
	extend:"Ext.data.Model",
	fields: ['id', 'title']
});

Ext.define('DockUIDashboard', {
	extend:"Ext.data.Model",
	fields: ['id', 'title'],
	hasMany: [
		{model:'DockUIDashboardColumn', name:'columns'}
	]
});

Ext.define('DockUIViewport', {
	extend:"Ext.data.Model",
	fields: ['id', 'title', 'readonly', 'settings'],
	hasMany: {model:'DockUIDashboard', name:'dashboards'},
	proxy: {
		type:'ajax',
		url: '/engine/server/UI/viewport/',
		reader:{
			root:'result',
			type:'json'
		}
	}
});

var DockUIDefaultViewportSettings = Ext.ModelManager.create({
    title: 'My Viewport',
    dashboards:[
	    {
	    	title:'My Dashboard'
	    }
    ]
}, 'DockUIViewport');