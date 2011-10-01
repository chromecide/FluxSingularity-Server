Ext.define('DockUI.Toolbar', {
	extend: 'Ext.toolbar.Toolbar',
	alias: 'widget.dockuitoolbar',
	
	initComponent: function(){
		this.titleItem = new Ext.toolbar.TextItem({
			'text':'Powered By DockUI v1.0'
		});
		if(arguments[0]){
			if(arguments[0].Title){
				this.setTitle(arguments[0].title);
			}
		}
		this.callParent(arguments);
		
		this.insert(0, [this.titleItem, '->']);
	},
	config:{
		
	},
	constructor: function(config) {
		
		Ext.applyIf(config, this.config);
        this.initConfig(config);
        
        return this.callParent(arguments);
    },
	setTitle: function(title){
		this.titleItem.setText(title);
	}
});