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


/**
 * USER GOOGLE MAP API VERSION 2
 * Refer: http://code.google.com/apis/maps/documentation/reference.html
*/

JAWidgetMap = new Class({
	initialize: function (container, defaults) {
		this.idPrefix = 'ja-widget-map';
		this.container = container;
		this.containerSV = container + '-streeview';
		this.containerR = container + '-route';
		this.containerR_height = 200;
		this.options = defaults;
		
		//
		this.context_menu = null;
		this.toolbar_control_style = null;
		this.maptype_control_style = null;
		this.GScaleControl = null;
		this.GOverviewMapControl = null;
		this.GScaleControl = null;
		this.layer = null;
		//
		if(this.options.size)
			this.objMap = new GMap2($(this.container), {size: this.options.size});
		else
			this.objMap = new GMap2($(this.container));
		
		this.geocoder = new GClientGeocoder();
		//direction
		this.objDirections = null;
		if ($(this.containerR)) {
			this.objDirectionsPanel = $(this.containerR);
			this.objDirections = new GDirections(this.objMap, this.objDirectionsPanel);
		}
		//
		this.createElement();
	},
	
	createElement: function(){
		this.map_type = this.getMapType(this.options.maptype);
		this.objMap.setMapType(this.map_type);
		
		// Add ContextMenuControl
		if (this.options.context_menu == 1) {
			this.context_menu = new ContextMenuControl();
			this.objMap.addControl(this.context_menu);
		} else {
			this.context_menu = null;
		}

		//tollbar
		if (this.options.toolbar_control_display == 1) {
			var controlPos = new GControlPosition(this.getPosition(this.options.toolbar_control_position), new GSize(10, 10));
			var toolbar_control_style;
			switch (this.options.toolbar_control_style) {
			case 'small':
				toolbar_control_style = new GSmallMapControl();
				break;
			case 'large':
				toolbar_control_style = new GLargeMapControl();
				break;
			case 'zoom_only':
				toolbar_control_style = new GSmallZoomControl();
				break;
			case 'large_3d':
				toolbar_control_style = new GLargeMapControl3D();
				break;
			case 'small_3d':
			default:
				toolbar_control_style = new GSmallZoomControl3D();
				break;
			}
			this.toolbar_control_style = toolbar_control_style;
			this.objMap.addControl(toolbar_control_style, controlPos);
		} else {
			this.toolbar_control_style = null;
		}

		//maptype control
		if (this.options.maptype_control_display == 1) {
			var maptypeControlPos = new GControlPosition(this.getPosition(this.options.maptype_control_position), new GSize(10, 10));
			var maptype_control_style;
			switch (this.options.maptype_control_style) {
			case 'standard':
				maptype_control_style = new GMapTypeControl();
				break;
			case 'hierarchical':
				maptype_control_style = new GHierarchicalMapTypeControl();
				break;
			case 'drop_down':
			default:
				maptype_control_style = new GMenuMapTypeControl();
				break;
			}
			this.maptype_control_style = maptype_control_style;
			this.objMap.addControl(maptype_control_style, maptypeControlPos);
		} else {
			this.maptype_control_style = null;
		}
		//scalse
		if (this.options.display_scale == 1) {
			this.GScaleControl = new GScaleControl();
			this.objMap.addControl(this.GScaleControl);
		} else {
			this.GScaleControl = null;
		}
		//overview
		if (this.options.display_overview == 1) {
			this.GOverviewMapControl = new GOverviewMapControl();
			this.objMap.addControl(this.GOverviewMapControl);
		} else {
			this.GOverviewMapControl = null;
		}

		//layers
		if (this.options.display_layer != 'none') {
			this.layer = new GLayer(this.options.display_layer);
			this.objMap.addOverlay(this.layer);
		} else {
			this.layer = null;
		}
	},
	
	resetMap: function() {
		//20091005 - dont remove maptype
		//this.objMap.removeMapType(this.map_type);
		//remove control
		/*if(this.context_menu != null) this.objMap.removeControl(this.context_menu);*/
		if(this.toolbar_control_style != null) this.objMap.removeControl(this.toolbar_control_style);
		if(this.maptype_control_style != null) this.objMap.removeControl(this.maptype_control_style);
		
		if(this.GScaleControl != null) this.objMap.removeControl(this.GScaleControl);
		if(this.GOverviewMapControl != null) this.objMap.removeControl(this.GOverviewMapControl);
		if(this.GScaleControl != null) this.objMap.removeControl(this.GScaleControl);
		
		if(this.layer != null) this.objMap.removeOverlay(this.layer);
	},
	
	setMap: function(aOptions) {
		this.resetMap();
		
		this.options = aOptions;
		this.createElement();
	},
	
	setCenter: function (source) {
		if(source.objMap) {
			this.objMap.setCenter(source.objMap.getCenter(), source.objMap.getZoom());
		}
	},

	displayMap: function (fromA, toB, userInput) {
		fromA = (fromA != '' || userInput) ? fromA : this.options.from_location;
		toB = (toB != '') ? toB : this.options.to_location;
		
		if (this.objDirections != null) {
			//Clears any existing directions results, removes overlays from the map and panel, and cancels any pending load() requests. 
			this.objDirections.clear();
		}
		if (toB == '') {
			alert('Please select a target Location!');
			return false;
		}
		if (fromA != '') {
			this.showDirections(fromA, toB);
		} else {
			var lat = this.options.target_lat.toFloat();
			var lon = this.options.target_lon.toFloat();
			if(!userInput && this.isLatLon(lat) && this.isLatLon(lon))
				this.showLocation2(lat, lon);
			else
				this.showLocation(toB);
		}
	},

	showLocation: function (address) {
		this.hideRoute();
		
		var lvZoom = this.options.zoom;
		var info = this.options.to_location_info.trim();
		var objMap = this.objMap;

		if (this.geocoder) {
			this.geocoder.getLatLng( address, function (point) {
				if (!point) {
					alert(address + " not found");
				} else {
					objMap.setCenter(point, lvZoom);
					var marker = new GMarker(point, {draggable: true});
					objMap.addOverlay(marker);
					if(info != '') {
						marker.openInfoWindowHtml(info);
					}
				}
			});
		}

	},

	showLocation2: function (lat, lon) {
		this.hideRoute();
		
		var lvZoom = this.options.zoom;
		var info = this.options.to_location_info.trim();
		var objMap = this.objMap;
		
		var point = new GLatLng(lat, lon);
		objMap.setCenter(point, lvZoom);
		var marker = new GMarker(point, {draggable: true});
		objMap.addOverlay(marker);
		if(info != '') {
			marker.openInfoWindowHtml(info);
		}
	},

	showDirections: function (fromAddress, toAddress) {
		if (this.objDirections != null) {
			this.objDirections.load("from: " + fromAddress + " to: " + toAddress, {
				travelMode: G_TRAVEL_MODE_DRIVING
			});
			//G_TRAVEL_MODE_WALKING
			//this.hideRoute();
			this.showRoute(this.containerR_height);
		}
	},
	
	isLatLon: function(number) {
		return (number == 0.00 || number.toString() == "NaN") ? false : true;
	},

	showRoute: function (height) {
		if($(this.containerR)) {
			if (!$(this.containerR).fx) {
				$(this.containerR).fx = new Fx.Style($(this.containerR), 'height');
			}
			$(this.containerR).fx.start(height);
		}
	},
	hideRoute: function () {
		if($(this.containerR)) {
			if (!$(this.containerR).fx) {
				$(this.containerR).fx = new Fx.Style($(this.containerR), 'height');
			}
			$(this.containerR).fx.start(0);
		}
	},

	handleNoFlash: function (errorCode) {
		if (errorCode == FLASH_UNAVAILABLE) {
			alert("Error: Flash doesn't appear to be supported by your browser");
			return;
		}
	},
	
	getMapType: function(type) {
		var maptype = G_NORMAL_MAP;
		switch(type) {
			case 'normal': maptype = G_NORMAL_MAP; break;
			case 'satellite': maptype = G_SATELLITE_MAP; break;
			case 'hybrid': maptype = G_HYBRID_MAP; break;
			case 'physical': maptype = G_PHYSICAL_MAP; break;
		}
		return maptype;
	},
	
	getPosition: function(pos) {
		var position = G_ANCHOR_TOP_RIGHT;
		switch(pos) {
			case 'top_right': position = G_ANCHOR_TOP_RIGHT; break;
			case 'top_left': position = G_ANCHOR_TOP_LEFT; break;
			case 'bottom_right': position = G_ANCHOR_BOTTOM_RIGHT; break;
			case 'bottom_left': position = G_ANCHOR_BOTTOM_LEFT; break;
		}
		return position;
	}
});