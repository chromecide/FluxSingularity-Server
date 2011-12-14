Ext.define('FSManager.components.EntityDefinitionEditor',{
	extend: 'Ext.container.Container',
	uses:[
		'FSManager.components.NodeDisplay'
	],
	constructor: function(args){
		this.callParent(arguments);
		
		this.on('render', this.initDD, this);
	}
})
