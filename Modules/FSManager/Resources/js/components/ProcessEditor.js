Ext.define('FSManager.components.ProcessEditor',{
	extend: 'Ext.container.Container',
	uses:[
		'FSManager.components.NodeDisplay'
	],
	constructor: function(args){
		this.callParent(arguments);
		
		//this.on('render', this.initDD, this);
	},
	initDD: function(){
		var thisEditor=this;
		this.dropZone = new Ext.dd.DropZone(this.el, {

	        // If the mouse is over a grid row, return that node. This is
	        // provided as the "target" parameter in all "onNodeXXXX" node event handling functions
	        getTargetFromEvent: function(e) {
	        	return thisEditor;
	            //return e.getTarget(myGridPanel.getView().rowSelector);
	        },
	
	        // On entry into a target node, highlight that node.
	        onNodeEnter : function(target, dd, e, data){ 
	        	//console.log(dd);
	        	//console.log(data);
	        	console.log(e);
	            //Ext.fly(target).addCls('my-row-highlight-class');
	        },
	
	        // On exit from a target node, unhighlight that node.
	        onNodeOut : function(target, dd, e, data){ 
	            //Ext.fly(target).removeCls('my-row-highlight-class');
	        },
	
	        // While over a target node, return the default drop allowed class which
	        // places a "tick" icon into the drag proxy.
	        onNodeOver : function(target, dd, e, data){ 
	            return Ext.dd.DropZone.prototype.dropAllowed;
	        },
	
	        // On node drop we can interrogate the target to find the underlying
	        // application object that is the real target of the dragged data.
	        // In this case, it is a Record in the GridPanel's Store.
	        // We can use the data set up by the DragZone's getDragData method to read
	        // any data we decided to attach in the DragZone's getDragData method.
	        onNodeDrop : function(target, dd, e, data){
	        	//console.log(target);
	        	//console.log(dd);
	        	//console.log(e);
	        	//console.log(data);
	            //var rowIndex = myGridPanel.getView().findRowIndex(target);
	            //var r = myGridPanel.getStore().getAt(rowIndex);
	            //Ext.Msg.alert('Drop gesture', 'Dropped Record id ' + data.draggedRecord.id +
	            //    ' on Record id ' + r.id);
	            return true;
	        }
	    });
	}
})
