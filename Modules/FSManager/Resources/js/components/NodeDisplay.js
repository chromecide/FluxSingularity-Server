Ext.define('FSManager.components.NodeDisplay', {
	extend: 'Ext.panel.Panel',
	require:[
		'FSManager.models.NodeInput'
	],
	width:200,
	height:100,
	x:200,
	y: 100,
	layout:{
		type:'vbox',
		align:'stretch',
	},
	items:[
		{
			xtype:'panel',
			flex:1,
			items:[
				{
					xtype:'dataview',
					tpl: '<div class="FSManagerNodeInput">&nbsp;</div>',
					store: Ext.create('Ext.data.Store', {
						model: 'FSManager.models.NodeInput',
						data:[
							{Title:'Test', 'Type': 'Test2'}
						]
					})
				}
			]
		},
		{
			xtype:'panel',
			html:'Information',
			flex:4
		},
		{
			xtype:'panel',
			html:'Outputs',
			flex:1
		}
	],
	constructor: function(){
		this.bodyCls = 'FSManagerNode';
		//this.html = 'AND';
		this.callParent(arguments);
		
		//this.on('render', this.initDD, this);
	},
	getRecord: function(sourceEl){
		//console.log(sourceEl);
	},
	initDD: function(v){
		this.dragZone = new Ext.dd.DragZone(this.getEl(), {

//      On receipt of a mousedown event, see if it is within a DataView node.
//      Return a drag data object if so.
		onBeforeDrag: function(){
			//console.log(arguments);
		},
        getDragData: function(e) {

//          Use the DataView's own itemSelector (a mandatory property) to
//          test if the mousedown is within one of the DataView's nodes.
            var sourceEl = e.getTarget(v.itemSelector, 10);

//          If the mousedown is within a DataView node, clone the node to produce
//          a ddel element for use by the drag proxy. Also add application data
//          to the returned data object.
            if (sourceEl) {
                d = sourceEl.cloneNode(true);
                d.id = Ext.id();
                return {
                    ddel: d,
                    sourceEl: sourceEl,
                    repairXY: Ext.fly(sourceEl).getXY(),
                    sourceStore: v.store,
                    draggedRecord: v.getRecord(sourceEl)
                }
            }
        },

//      Provide coordinates for the proxy to slide back to on failed drag.
//      This is the original XY coordinates of the draggable element captured
//      in the getDragData method.
        getRepairXY: function() {
            return this.dragData.repairXY;
        }
  });
  }
});
