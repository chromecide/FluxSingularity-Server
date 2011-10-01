
Ext.define('DockUI.Viewport', {
	extend: 'Ext.container.Viewport',
	uses: [
		'DockUI.Toolbar',
		'DockUI.SideBar',
		'DockUI.Application',
		'DockUI.ViewportDropTarget',
		'DockUI.Dashboard',
		'DockUI.DashboardColumn'
	],
	/**
	 * Viewport settings
	 */
	settings:{
		bottomBar:{
			enabled: false,
			title:'Bottom Bar'
		},
		topBar:{
			enabled: false,
			title:'DockUI'
		},
		leftBar:{
			enabled:false,
			title: 'Applications'
		},
		rightBar:{
			enabled:false,
			title: 'Viewport Settings'
		},
		settingsForm:{
			location: 'rightBar'
		},
		isReadOnly: false,
		theme: 'fseDefault'
	},
	/**
	 * Viewport Methods
	 */
	enableLeftBar: function(){
		var lb = Ext.getCmp('dockui-viewport-leftbar');
		this.settings.leftBar.enabled = true;
		lb.show();
	},
	disableLeftBar: function(){
		var lb = Ext.getCmp('dockui-viewport-leftbar');
		this.settings.leftBar.enabled = false;
		lb.hide();
	},
	enableRightBar: function(){
		var rb = Ext.getCmp('dockui-viewport-rightbar');
		this.settings.rightBar.enabled = true;
		rb.show();
	},
	disableRightBar: function(){
		var rb = Ext.getCmp('dockui-viewport-rightbar');
		this.settings.rightBar.enabled = false;
		rb.hide();
	},
	enableTopBar: function(){
		var tb = Ext.getCmp('dockui-viewport-topbar');
		this.settings.topBar.enabled = true;
		tb.show();
	},
	disableTopBar: function(){
		var tb = Ext.getCmp('dockui-viewport-topbar');
		this.settings.topBar.enabled = false;
		tb.hide();
	},
	enableBottomBar: function(){
		var bb = Ext.getCmp('dockui-viewport-bottombar');
		this.settings.bottomBar.enabled = true;
		bb.show();
	},
	disableBottomBar: function(){
		var bb = Ext.getCmp('dockui-viewport-bottombar');
		this.settings.bottomBar.enabled = false;
		bb.hide();
	},
	saveViewport:function(){
		console.log(this.record);
	},
	loadViewport: function(record){
		this.record = record;

		var leftBar = record.get('LeftBar');
		var rightBar = record.get('RightBar');
		var topBar = record.get('TopBar');
		var bottomBar = record.get('BottomBar');

		if(leftBar.Title){
			var lb = Ext.getCmp('dockui-viewport-leftbar');
			lb.setTitle(leftBar.Title);
		}
		
		if(leftBar.Enabled){
			this.enableLeftBar();
		}else{
			this.disableLeftBar();
		}
		
		if(rightBar.Title){
			var rb = Ext.getCmp('dockui-viewport-rightbar');
			rb.setTitle(rightBar.Title);
		}
		
		if(rightBar.Enabled){
			this.enableRightBar();
		}else{
			this.disableRightBar();
		}
		
		if(topBar.Enabled){
			this.enableTopBar();
		}else{
			this.disableTopBar();
		}
		
		if(topBar.Title){
			var tb = Ext.getCmp('dockui-viewport-topbar');
			tb.setTitle(topBar.Title);
		}
		
		if(bottomBar.Enabled){
			this.enableBottomBar();
		}else{
			this.disableBottomBar();
		}
		
		if(bottomBar.Title){
			var bb = Ext.getCmp('dockui-viewport-bottombar');
			bb.setTitle(bottomBar.Title);
		}
		
	},
	/**
	 * Internal settings
	 */
	config:{
		settings:{
			bottomBar:{
				enabled: false,
				title:'Bottom Bar'
			},
			topBar:{
				enabled: false,
				title:'DockUI'
			},
			leftBar:{
				enabled:false,
				title: 'Applications'
			},
			rightBar:{
				enabled:false,
				title: 'Settings'
			}
		}
	},
	
	/**
	 * Internal Functions
	 */
	initComponent: function(){
		Ext.apply(this, {
			id: 'dockui-viewport',
            layout: {
                type: 'border',
                padding: '0 5 5 5' // pad the layout from the window edges
            },
            items:[
            	{
	                id: 'dockui-viewport-topbar',
	                xtype: 'dockuitoolbar',
	                region: 'north',
	                height: 40,
	                hidden: !this.settings.topBar.enabled,
	                items:[
		                {
							xtype: 'applicationlaunchertoolbarbutton',
							text: 'Viewport Settings',
							application:{
								appName: 'DockUI.applications.ViewportEditor',
								appCfg:{}
							}
						}
	                ]
	            },
	            {
	                id: 'dockui-viewport-bottombar',
	                xtype: 'dockuitoolbar',
	                region: 'south',
	                height: 40,
	                hidden: !this.settings.bottomBar.enabled
	            },
	            {
	                xtype: 'panel',
	                region: 'center',
	                layout: 'border',
	                settings:this.settings,
	                listeners:{
	                	render: function(){
	                		this.dd = new DockUI.ViewportDropTarget(this, {
								listeners:{
									drop: function(){
										console.log('itemDropped');
									}
								}
							});
	                	}
	                },
	                items: [
	                {
	                	xtype:'dockuisidebar',
	                    id: 'dockui-viewport-leftbar',
	                    title: this.settings.leftBar.title,
	                    region: 'west',
	                    hidden:!this.settings.leftBar.enabled,
	                    items: [
	                    {
	                    	xtype:'panel',
	                    	layout:'fit',
			                draggable:true,
	                    	items:[
			                    {
			                    	xtype: 'dockuiapplicationexplorer'
			                    }
		                    ],
		                    listeners:{
			                    render: function(){
			                    	this.setTitle(this.items.get(0).title);
			                    }
		                    }
	                    }
	                    ]
	                },
		            {
	                    id: 'dockui-viewport-main',
	                    xtype: 'dockuidashboard',
	                    region: 'center',
	                    items:[
		                    {
		                    	xtype:'dockuidashboardcolumn',
		                    	flex:1
		                    }
	                    ]
		            },
	                {
	                	xtype:'dockuisidebar',
	                    id: 'dockui-viewport-rightbar',
	                    title: this.settings.rightBar.title,
	                    region: 'east',
	                    hidden:!this.settings.rightBar.enabled,
	                    items: [
	                    ]
	                }]
	            }
            ]
		});
		
		this.addClass("x-portal");
		
       return this.callParent(arguments);
	},
	constructor: function(config) {
		Ext.applyIf(config, this.config);
        this.initConfig(config);
        
        return this.callParent(arguments);
    }
    /**
     * Support Objects
     */
    
});
