Ext.define('DockUI.Application',{
	extend: 'Ext.panel.Panel',
	draggable:true,
	preventHeader:true,
	config: {
		title:'DockUI Application Base',
		version:'1.0'
	},
	launchers:{
		icon: function(){
			return new DockUI.applicationlauncher.Icon({
				application:{
					appName: this.appProperties.ApplicationId,
					appCfg:{}
				}
			});
		},
		menu: function(){
			return new DockUI.applicationlauncher.ToolbarButton({
				application:{
					appName: this.appProperties.ApplicationId,
					appCfg:{}
				}
			});
		}
	},
	initComponent: function(){
		return this.callParent(arguments);
	},
	constructor: function(){
		this.callParent(arguments);
	}
});

Ext.define('DockUI.applicationlauncher.Icon', {
	extend: 'Ext.panel.Panel',
	border:0,
	alias: 'widget.applicationlaunchericon',
	image:'/engine/applications/defaultIcon.png',
	label:'Launcher',
	width: 70,
	height: 70,
	margin:10,
	draggable:true,
	html: '<img width="50" height="50" src=""/><span>Icon</span>',
	initComponent: function(){
		this.html= '<img width="50" height="50" src="'+this.image+'"/><span>'+this.label+'</span>';
		this.callParent(arguments);
	},
	selectItem: function(){
		this.addClass('dockui-application-launcher-icon-selected');
		this.fireEvent('selected', this);
	},
	listeners: {
		render: function(){
			this.getEl().on('dblclick', function(){
				console.log(this.application);
				var appPanel = Ext.create(this.application.appName, this.application.appCfg);
				
				var appWin = Ext.create('Ext.window.Window', {title: appPanel.title, items:[appPanel]});
				appPanel.title='';
				appWin.show();
			}, this);
			
			this.getEl().on('click', function(){
				this.selectItem();
			}, this);
			
			return true;
		}
	}
});

Ext.define('DockUI.applicationlauncher.ToolbarButton', {
	extend: 'Ext.button.Button',
	border:0,
	alias: 'widget.applicationlaunchertoolbarbutton',
	draggable:true,
	initComponent: function(){
		
		this.callParent(arguments);
	},
	listeners: {
		render: function(){
			
			this.getEl().on('click', function(){
				var appPanel = Ext.create(this.application.appName, this.application.appCfg);
				
				var appWin = Ext.create('Ext.window.Window', {title: appPanel.title, items:[appPanel]});
				appPanel.title='';
				appWin.show();
			}, this);
			
			return true;
		}
	}
});

Ext.define('DockUI.ApplicationExplorer',{
	extend: 'DockUI.Application',
	alias: 'widget.dockuiapplicationexplorer',
	view: 'icon',
	config: {
		title:'DockUI Application Explorer',
		version:'1.0',
		ApplicationId:'DockUI.ApplicationExplorer'
	},
	title: 'Application Explorer',
	itemSelected: function(){
		console.log(itemSelected);
		return true;
	},
	items:[
		
	],
	initComponent: function(){
		this.callParent(arguments);
		this.add({
			xtype: 'applicationlaunchericon',
			label: 'Viewport Settings',
			listeners:{
				selected: function(){
					
				},
				scope:this
			},
			application:{
				appName: 'DockUI.applications.ViewportEditor',
				appCfg:{}
			}
		});
	}
});