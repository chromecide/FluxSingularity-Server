Ext.define('DockUI.applications.ViewportEditor',{
	extend: 'DockUI.Application',
	view: 'icon',
	appProperties: {
		title:'DockUI Viewport Editor',
		version:'1.0'
	},
	title: 'Viewport Settings',
	width: 300,
	initComponent: function(){
		var vp = Ext.getCmp('dockui-viewport');
		Ext.apply(this, {
			items:[
				this.formPanel = Ext.create('Ext.form.FormPanel',{
					items:[
						{
							xtype:'fieldset',
							title: 'Top Bar',
							checkboxToggle:true,
							items:[
								{
									xtype:'slider',
									width: 200,
							        value: 40,
							        increment: 1,
							        minValue: 30,
							        maxValue: 100,
									fieldLabel:'Height',
									fieldLabel:'Height',
									listeners:{
										change: function(slider, value){
											var tb = Ext.getCmp('dockui-viewport-topbar');
											tb.setHeight(value);
										}
									}
								}
							],
							listeners:{
								render:function(){
									this.checkboxCmp.on('change', function(checkbox, checked){
						    			if(checked){
						    				vp.enableTopBar();
						    			}else{
						    				vp.disableTopBar();
						    			}
									});
								}
							}
						},
						{
							xtype:'fieldset',
							title: 'Left Bar',
							checkboxToggle:true,
							items:[
								{
									xtype:'checkbox',
									fieldLabel:'Fixed Width',
									listeners:{
										change:function(cb, checked){
											var lbWidthObj = this.formPanel.getForm().findField('leftBarWidth');
											var lb = Ext.getCmp('dockui-viewport-leftbar');
											var splitbar = lb.ownerCt.layout.splitters.west;
											
											if(checked){
												splitbar.setVisible(false);
												lbWidthObj.enable();
												lbWidthObj.suspendEvents();
												lbWidthObj.setValue(lb.getWidth());
												lbWidthObj.resumeEvents();
											}else{
												splitbar.setVisible(true);
												splitbar.enable();
												lbWidthObj.disable();
											}
										},
										scope:this
									}
								},
								{
									name:'leftBarWidth',
									xtype:'slider',
									width: 200,
							        value: 150,
							        increment: 1,
							        minValue: 70,
							        maxValue: 400,
									fieldLabel:'Width',
									listeners:{
										change: function(slider, value){
											var lb = Ext.getCmp('dockui-viewport-leftbar');
											lb.setWidth(value);
										},
										scope:this
									}
								}
							],
							listeners:{
								render:function(){
									this.checkboxCmp.on('change', function(checkbox, checked){
						    			if(checked){
						    				vp.enableLeftBar();
						    			}else{
						    				vp.disableLeftBar();
						    			}
									});
								}
							}
						},
						{
							xtype:'fieldset',
							title: 'Right Bar',
							checkboxToggle:true,
							items:[
								{
									xtype:'checkbox',
									fieldLabel:'Fixed Width',
									listeners:{
										change:function(cb, checked){
											var rbWidthObj = this.formPanel.getForm().findField('rightBarWidth');
											var rb = Ext.getCmp('dockui-viewport-rightbar');
											var splitbar = rb.ownerCt.layout.splitters.east;
											if(checked){
												splitbar.setVisible(false);
												rbWidthObj.setValue(rb.getWidth());
											}else{
												splitbar.setVisible(true);
												splitbar.enable();
												rbWidthObj.disable();
											}
										},
										scope:this
									}
								},
								{
									name:'rightBarWidth',
									xtype:'slider',
									width: 200,
							        value: 150,
							        increment: 1,
							        minValue: 70,
							        maxValue: 400,
									fieldLabel:'Width',
									listeners:{
										change: function(slider, value){
											var rb = Ext.getCmp('dockui-viewport-rightbar');
											rb.setWidth(value);
										},
										scope:this
									}
								}
							],
							listeners:{
								render:function(){
									this.checkboxCmp.on('change', function(checkbox, checked){
						    			if(checked){
						    				vp.enableRightBar();
						    			}else{
						    				vp.disableRightBar();
						    			}
									});
								}
							}
						},
						{
							xtype:'fieldset',
							title: 'Bottom Bar',
							checkboxToggle:true,
							items:[
								{
									xtype:'slider',
									width: 200,
							        value: 40,
							        increment: 1,
							        minValue: 30,
							        maxValue: 100,
									fieldLabel:'Height',
									listeners:{
										change: function(slider, value){
											var bb = Ext.getCmp('dockui-viewport-bottombar');
											bb.setHeight(value);
										}
									}
								}
							],
							listeners:{
								render:function(){
									this.checkboxCmp.on('change', function(checkbox, checked){
						    			if(checked){
						    				vp.enableBottomBar();
						    			}else{
						    				vp.disableBottomBar();
						    			}
									});
								}
							}
						}
					]
				})
			]
		});
		this.callParent(arguments);
	},
	applySettings:function(){
		
	}
});