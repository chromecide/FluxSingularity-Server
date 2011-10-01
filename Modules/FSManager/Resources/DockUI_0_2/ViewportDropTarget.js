/**
 * @class DockUI.ViewportDropTarget
 * @extends Ext.dd.DropTarget
 * Internal class that manages drag/drop for {@link DockUI.Workspace}.
 */

Ext.define('DockUI.ViewportDropTarget', {
    extend: 'Ext.dd.DropTarget',

    constructor: function(target, cfg) {
        this.target = target;
        Ext.dd.ScrollManager.register(target.body);
        DockUI.ViewportDropTarget.superclass.constructor.call(this, target.body, cfg);
        target.body.ddScrollConfig = this.ddScrollConfig;
    },

    ddScrollConfig: {
        vthresh: 50,
        hthresh: -1,
        animate: true,
        increment: 200
    },

    createEvent: function(dd, e, data, col, c, pos) {
        return {
            target: this.target,
            panel: data.panel,
            columnIndex: col,
            column: c,
            position: pos,
            data: data,
            source: dd,
            rawEvent: e,
            status: this.dropAllowed
        };
    },

    notifyOver: function(dd, e, data) {
        var xy = e.getXY(),
            target = this.target,
            proxy = dd.proxy;

        if (!this.grid) {
            this.grid = this.getGrid();
        }

        var cw = target.body.dom.clientWidth;
        
        if (!this.lastCW) {
            this.lastCW = cw;
        } else if (this.lastCW != cw) {
            this.lastCW = cw;
            this.grid = this.getGrid();
        }

        // determine column
        var colIndex = 0,
            colRight = 0,
            cols = this.grid.columnX,
            len = cols.length,
            cmatch = false;

        for (len; colIndex < len; colIndex++) {
            colRight = cols[colIndex].x + cols[colIndex].w;
            if (xy[0] < colRight) {
                cmatch = true;
                break;
            }
        }
        // no match, fix last index
        if (!cmatch) {
            colIndex--;
        }

        // find insert position
        var overDoodad, pos = 0,
            h = 0,
            match = false,
            overcolumn,
            doodads,
            overSelf = false;

        if(colIndex==0||colIndex==len-1){
        	if(colIndex==0){//left sidebar
        		overColumn = target.items.getAt(colIndex);	
        	}else{//right sidebar
        		overColumn = target.items.getAt(2);
        	}
        }else{
        	var mainPanel = target.items.getAt(1);
        	overColumn = mainPanel.items.getAt(colIndex-1);
        }
        
        if(!overColumn){
        	return false;
        }
        doodads = overColumn.items.items;
        len = doodads.length;

        for (len; pos < len; pos++) {
            overDoodad = doodads[pos];
            h = overDoodad.el.getHeight();
            if (h === 0) {
                overSelf = true;
            } else if ((overDoodad.el.getY() + (h / 2)) > xy[1]) {
                match = true;
                break;
            }
        }

        pos = (match && overDoodad ? pos : overColumn.items.getCount()) + (overSelf ? -1 : 0);
        var overEvent = this.createEvent(dd, e, data, colIndex, overColumn, pos);

        if (target.fireEvent('validatedrop', overEvent) !== false && target.fireEvent('beforedragover', overEvent) !== false) {

            // make sure proxy width is fluid in different width columns
            proxy.getProxy().setWidth('auto');

            if (overDoodad) {
                proxy.moveProxy(overDoodad.el.dom.parentNode, match ? overDoodad.el.dom : null);
            } else {
                proxy.moveProxy(overColumn.el.dom, null);
            }

            this.lastPos = {
                c: overColumn,
                col: colIndex,
                p: overSelf || (match && overDoodad) ? pos : false
            };
            this.scrollPos = target.body.getScroll();

            target.fireEvent('dragover', overEvent);
            return overEvent.status;
        } else {
            return overEvent.status;
        }

    },

    notifyOut: function() {
        delete this.grid;
    },

    notifyDrop: function(dd, e, data) {
        delete this.grid;
        if (!this.lastPos) {
            return;
        }
        var c = this.lastPos.c,
            col = this.lastPos.col,
            pos = this.lastPos.p,
            panel = dd.panel,
            dropEvent = this.createEvent(dd, e, data, col, c, pos !== false ? pos : c.items.getCount());

        if (this.target.fireEvent('validatedrop', dropEvent) !== false && this.target.fireEvent('beforedrop', dropEvent) !== false) {

            // make sure panel is visible prior to inserting so that the layout doesn't ignore it
            panel.el.dom.style.display = '';
			
            var application = null;
            
			if(panel.application){
	    		application = Ext.create(panel.application.appName, {closable:true, draggable:true});
	    	}else{
	    		application = panel.items.get(0);
	    	} 
	    	
	    	newPanel = new Ext.panel.Panel({
    			title:application.title,
    			draggable:true,
    			collapsible:true,
    			items:[
    				application
    			]
    		});
	    		
            if (pos !== false) {
                c.insert(pos, newPanel);
            } else {
                c.add(newPanel);
            }

            //dd.proxy.hide();
            this.target.fireEvent('drop', dropEvent);

            // scroll position is lost on drop, fix it
            var st = this.scrollPos.top;
            if (st) {
                var d = this.target.body.dom;
                setTimeout(function() {
                    d.scrollTop = st;
                },
                10);
            }
            
			//TODO: find a better way to remove the old application wrapper
            if(!panel.application){
				Ext.Function.defer(panel.ownerCt.remove, 10, panel.ownerCt, [panel]);
            }
			
        }
        delete this.lastPos;
        return true;
    },

    // internal cache of body and column coords
    getGrid: function() {
        var box = this.target.body.getBox();
        box.columnX = [];
        var items = this.target.items;
        var targetSettings = this.target.settings;
        
        if(targetSettings.leftBar.enabled){
        	var leftBar = items.getAt(0);
        	box.columnX.push({
        		x: leftBar.el.getX(),
        		w: leftBar.el.getWidth()
        	});
        }
    
        var mainPanel = items.getAt(1);
        console.log(mainPanel);
        mainPanel.items.each(function(c){
        	box.columnX.push({
	    		x: c.el.getX(),
	    		w: c.el.getWidth()
	    	});
        });
    	
    
        if(targetSettings.rightBar.enabled){
        	console.log(items);
        	var rightBar = items.getAt(2);
        	box.columnX.push({
        		x: rightBar.el.getX(),
        		w: rightBar.el.getWidth()
        	});
        }
        
        return box;
    },

    // unregister the dropzone from ScrollManager
    unreg: function() {
        Ext.dd.ScrollManager.unregister(this.target.body);
        DockUI.ViewportDropTarget.superclass.unreg.call(this);
    }
});
