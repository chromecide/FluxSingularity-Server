Ext.define('FSManager.components.EntityEditor',{
	extend: 'Ext.container.Container',
	uses:[
		'FSManager.components.NodeDisplay'
	],
	constructor: function(args){
		this.callParent(arguments);
		
		this.on('render', this.initDD, this);
	},
	initDD: function(){
		var thisEditor=this;
		this.dd = new Ext.dd.DD(this.getEl(), "group1");
	}
})
