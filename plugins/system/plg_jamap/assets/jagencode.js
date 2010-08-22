/*
# ------------------------------------------------------------------------
# JA Map Plugin for Joomla 1.5
# ------------------------------------------------------------------------
# Copyright (C) 2004-2009 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - GNU/GPL, http://www.gnu.org/copyleft/gpl.html
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# ------------------------------------------------------------------------
*/

JAElementGenCode = new Class({
	initialize: function () {
		this.code = '{jamap ';
		this.prefix = 'params';
		this.objText = this.prefix + 'code_container';
		this.objCheckboxes = this.prefix + '[list_params][]';
		this.mapPreviewId = 'jaMapPreview';
		this.form = document.adminForm;
		
		this.mapHolder = 'map-preview-container';
		this.mapId = 'ja-widget-map';
		this.objMap = null;
		this.aUserSetting = new Array();
		//
		this.scanItem();
		this.getUserSetting();
	},
	
	getUserSetting: function() {
		this.aUserSetting = new Array();
		var aKey = new Array();
		var aValue = new Array();
		//get user setting
		var sConfig = $(this.objText).getValue();
		sConfig = sConfig.trim();
		sConfig = sConfig.substr(this.code.length);
		sConfig = sConfig.substr(0, sConfig.length -1);
		
		var aConfig = new Array();
		aConfig = sConfig.split("' ");
		aConfig.each(function(item) {
			if(item.match(/='/)){
				item = item.trim();
				var v = item.split("='");
				aKey[aKey.length] = v[0];
				aValue[aValue.length] = v[1];
			}
		});
		this.aUserSetting = aValue.associate(aKey);
	},
	
	genCode: function() {
		this.scanItem();
		this.getUserSetting();
		//
		var str = this.code;
		var i;
		for(i=0; i < this.form.elements[this.objCheckboxes].length; i++) {
			var item = this.form.elements[this.objCheckboxes][i];
			if(item.checked && !item.disabled) {
				var e = this.prefix + '['+ item.value +']';
				var value = '';
				if(this.form.elements[e].value) {
					value = this.form.elements[e].value;
				} else if (this.form.elements[e].length) {
					var j = 0;
					for(j=0; j < this.form.elements[e].length; j++) {
						if(this.form.elements[e][j].checked || this.form.elements[e][j].selected) {
							value = this.form.elements[e][j].value;
							break;
						}
					}
					//auto change value if is 0,1
					/*if(value == 1 || value == 0) {
						value = value ? 0 : 1;
					}*/
				}
				//check user setting
				if(this.aUserSetting[item.value]) {
					value = this.aUserSetting[item.value];
				}
				//
				
				str += item.value + "='" + this.addslashes(value.toString()) + "' ";
			}
		}
		str += '}';
		
		str = str.replace(/\\\\/g, '');
		
		$(this.objText).value = str;
		
		//reset user setting
		this.getUserSetting();
	},
	/**
	 * Scan for check item is enable or diabled
	*/
	scanItem: function() {
		var i;
		for(i=0; i < this.form.elements[this.objCheckboxes].length; i++) {
			var item = this.form.elements[this.objCheckboxes][i];
			if(item.alt) {
				var disabled = (!item.checked || item.disabled) ? true : false;
				this.setChildren(item.alt, disabled);
			}
		}
	},
	
	setChildren: function(children, disabled) {
		aChild = children.split(',');
		var i;
		var j;
		for(j=0; j<aChild.length; j++) {
			for(i=0; i < this.form.elements[this.objCheckboxes].length; i++) {
				var item = this.form.elements[this.objCheckboxes][i];
				if(item.value == aChild[j]) {
					item.disabled = disabled;
					var label = item.id + '-label';
					if($(label)) {
						if(disabled)
							$(label).addClass('item_disable');
						else
							$(label).removeClass('item_disable');
					}
					break;
				}
			}
			
		}
	},
	
	previewMap: function() {
		var elements = this.form.elements;
		var prefix = this.prefix;
		
		var i;
		var aKey = new Array();
		var aValue = new Array();
		for(i=0; i < this.form.elements[this.objCheckboxes].length; i++) {
			var item = this.form.elements[this.objCheckboxes][i];
			
			var e = this.prefix + '['+ item.value +']';
			var value = '';
			if(this.form.elements[e].value) {
				value = this.form.elements[e].value;
			} else if (this.form.elements[e].length) {
				var j = 0;
				for(j=0; j < this.form.elements[e].length; j++) {
					if(this.form.elements[e][j].checked || this.form.elements[e][j].selected) {
						value = this.form.elements[e][j].value;
						break;
					}
				}
			}
			
			aKey[aKey.length] = item.value;
			aValue[aValue.length] = value;
		
		}
		var aParams = aValue.associate(aKey);
		
		this.getUserSetting();
		$each(this.aUserSetting, function(item, index){
			aParams[index] = item;
		});
		aParams['context_menu'] = 0;
		aParams["target_lat"] = aParams["target_lat"].toFloat();
		aParams["target_lon"] = aParams["target_lon"].toFloat();
		aParams["map_width"] = aParams["map_width"].toInt();
		aParams["map_height"] = aParams["map_height"].toInt();
		aParams["maptype_control_display"] = aParams["maptype_control_display"].toInt();
		aParams["toolbar_control_display"] = aParams["toolbar_control_display"].toInt();
		aParams["display_scale"] = aParams["display_scale"].toInt();
		aParams["display_overview"] = aParams["display_overview"].toInt();
		aParams["zoom"] = aParams["zoom"].toInt();
		
		//alert(Object.toQueryString(aParams));
		this.createMap(aParams);
		//
		if(this.objMap == null) {
			this.objMap = new JAWidgetMap(this.mapId, aParams);
			this.objMap.displayMap('','',false);
		} else {
			this.objMap.setMap(aParams);
			this.objMap.displayMap('','',false);
		}
	},
	
	
	createMap: function(aParams){
		/**
			<div id="ja-widget-map-container" class="map-container" style="overflow:hidden;">
				<div id="ja-widget-map" style="width:420px; height:300px;"></div>
				<div id="ja-widget-route" class="map-route"></div>
			</div>
		*/
		var map_container = this.mapId + '-container';
		
		if(!$(this.mapId)) {
			var container = new Element('div', {'id': map_container, 'class': 'map-container'});
			var map = new Element('div', {'id': this.mapId, 'styles': { 'width': aParams.map_width, 'height':  aParams.map_height }});
			var route = new Element('div', {'id': this.mapId + '-route', 'class': 'map-route'});
			//
			SqueezeBox.applyContent('', {x: aParams.map_width + 20, y: aParams.map_height + 40});
			container.inject($('sbox-content'));
			map.inject($(map_container));
			route.inject($(map_container));
		} else {
			$(this.mapId).setStyles({ 'width': aParams.map_width, 'height':  aParams.map_height });
			SqueezeBox.applyContent('', {x: aParams.map_width + 20, y: aParams.map_height + 40});
			$(map_container).inject($('sbox-content'));
		}
		
		if(aParams.display_popup == 1) {
			var a = new Element('a', {
				'id': 'open_new_window',
				'events': {
					'click': function(){
						alert('Only work on Front-End!');
					}
				},
				'href': '#mapPreview'
			});
			a.appendText('OPEN IN NEW WINDOW');
			
			a.inject($('sbox-content'), 'top');
		} else {
			if($('open_new_window')) $('open_new_window').remove();
		}
		
	},
	
	addslashes: function(str) {
		str=str.replace(/\\/g,'\\\\');
		str=str.replace(/\'/g,'\\\'');
		str=str.replace(/\"/g,'\\"');
		str=str.replace(/\0/g,'\\0');
		return str;
	},
	
	stripslashes: function(str) {
		str=str.replace(/\\'/g,'\'');
		str=str.replace(/\\"/g,'"');
		str=str.replace(/\\0/g,'\0');
		str=str.replace(/\\\\/g,'\\');
		return str;
	}
});


function CopyToClipboard(obj)
{
	$(obj).focus();
	$(obj).select();
	var CopiedTxt = '';
	if(document.selection) {
		CopiedTxt = document.selection.createRange();
		CopiedTxt.execCommand("Copy");
	}
}

window.addEvent('domready', function(){
	var objGencode = new JAElementGenCode();
	var i;
	for(i=0; i < objGencode.form.elements[objGencode.objCheckboxes].length; i++) {
		$(objGencode.form.elements[objGencode.objCheckboxes][i]).addEvent('click', function() {
			objGencode.genCode();
		});
	}
	
	/*$(objGencode.objText).addEvent('keyup', function() {
		objGencode.getUserSetting();
	});*/
	
	//preview map
	/*$(objGencode.mapPreviewId).addEvent('click', function() {
		objGencode.previewMap();
	});*/
	SqueezeBox.initialize({'string': 'Preview Map'});

	$(objGencode.mapPreviewId).addEvent('click', function(e) {
		//
		new Event(e).stop();
		
		if($(objGencode.mapId)) {
			$(objGencode.mapId + '-container').inject($('map-preview-container'));
		}
		SqueezeBox.fromElement('map-preview-container');
		
		objGencode.previewMap();
	});
});