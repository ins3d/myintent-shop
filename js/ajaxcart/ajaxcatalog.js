var NB = {
	onload : function(func) {
		if (typeof this._domLoadedFunction == 'undefined')
			NB._domLoadedFunction = new Array();
		NB._domLoadedFunction.push(func);
		document.observe("dom:loaded", NB._onload);
	},
	substr : function(strVal, startIndex, lengthSubstring) {
		strVal = strVal.toString();
		if (typeof lengthSubstring == 'undefined')
			lengthSubstring = false;
		if (typeof strVal.substr == 'function') {
			if (lengthSubstring)
				return strVal.substr(startIndex, lengthSubstring);
			return strVal.substr(startIndex);
		}
		var strLen = strVal.length;
		if (strLen<startIndex)
			return '';
		var buildStr = '';
		for (var i=startIndex;i<strLen;i++) {
			if (i>startIndex+lengthSubstring)
				return buildStr;
			buildStr+= strVal[i];
		}
		return buildStr;
	},
	evalJsOnContent : function(content) {
		if (typeof content == 'string')
			content = document.getElementById(content);
		if (!content) return;
		var scripts = content.getElementsByTagName('script');
		for (var i=0;i<scripts.length;i++) {
			eval(scripts[i].innerHTML);
		}
	},
	overlayElement : function(element) {
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (!element) return;
		element._psoverlay = document.createElement('div');
		element._psoverlay.className = 'nb-overlay';
		if (element.className && element.className.toString().length>0) {
			element._psoverlay.className+= ' nb-'+element.className.toString().split(' ').join(' nb-');
		}
		if (element.id && element.id.toString().length>0) {
			element._psoverlay.id+= 'nb-'+element.id.toString();
		}
		element._psoverlay.style.display = 'none';
		element._psoverlay.style.position = 'absolute';
		var temps = element.getElementsByTagName('*');
		if (temps.length) {
			element.insertBefore(element._psoverlay, temps[0]);
		} else {
			element.appendChild(element._psoverlay);
		}
		var dimm = $(element).getDimensions();
		element._psoverlay.style.width = dimm.width.toString()+'px';
		element._psoverlay.style.height = dimm.height.toString()+'px';
		element._psoverlay.style.display = 'block';
		return true;
	},
	unOverlayElement : function(element) {
		return;
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (!element) return;
		if (typeof element._psoverlay == 'undefined') return;
		element._psoverlay.style.display = 'none';
		element._psoverlay.parentNode.removeChild(element._psoverlay);
		element._psoverlay = false;
		return true;
	},
	eachFunc : function(selector, funcRef) {
		if (typeof selector == 'string') {
			selector = $$(selector);
		}
		for (var i=0;i<selector.length;i++) {
			if (typeof funcRef == 'string') {
				selector[i].funcRef();
			} else {
				funcRef(selector[i]);
			}
		}
	},
	extendConfig : function(baseConfObj, config) {
		for (var key in config) {
			if (typeof baseConfObj[key] == 'undefined') {
				baseConfObj[key] = config[key];
				continue;
			}
			if ((typeof config[key] == 'object') && (typeof config[key].length == 'undefined')) {
				NB.extendConfig(baseConfObj[key], config[key]);
				continue;
			}
			baseConfObj[key] = config[key];
		}
	},
	removeCurrentElement : function(el) {
		setTimeout(function(){el.parentNode.removeChild(el);},100);
	},
	getCacheKey : function(params) {
		var cacheKey = '';
		var cKType;
		for (var key in params) {
			cKType = typeof params[key];
			cacheKey+=key+':'+cKType+':';
			if (cKType == 'object') {
				cacheKey+= NB.getCacheKey(params[key]);
			} else {
				cacheKey+= params[key].toString();
			}
		}
		return cacheKey;
	},
	_onload : function() {
		if (typeof NB._domLoadedFunction == 'undefined')
			return;
		var funcRef;
		for (var i=0;i<NB._domLoadedFunction.length;i++) {
			funcRef = NB._domLoadedFunction[i];
			if (typeof funcRef == 'function') {
				funcRef();
			}
		}
	}
};
/**
 * NextBits Window function
 */
NB.window = {
	getWidth : function() {
		var myWidth = 0;
		if (typeof(window.innerWidth) == 'number') {
			myWidth = window.innerWidth;
		} else if (document.documentElement && document.documentElement.clientWidth) {
			myWidth = document.documentElement.clientWidth;
		} else if (document.body && document.body.clientWidth) {
			myWidth = document.body.clientWidth;
		}
		return myWidth;
	},
	getHeight : function() {
		var myHeight = 0;
		if (typeof(window.innerHeight) == 'number') {
			myHeight = window.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) {
			myHeight = document.documentElement.clientHeight;
		} else if (document.body && document.body.clientHeight) {
			myHeight = document.body.clientHeight;
		}
		return myHeight;
	},
	getScrollX : function() {
		var scrOfX = 0;
		if(typeof(window.pageXOffset)=='number') {
			scrOfX = window.pageXOffset;
		} else if(document.body && document.body.scrollLeft) {
			scrOfX = document.body.scrollLeft;
		} else if(document.documentElement && document.documentElement.scrollLeft) {
			scrOfX = document.documentElement.scrollLeft;
		}
		return scrOfX;
	},
	getScrollY : function() {
		var scrOfY = 0;
		if(typeof(window.pageYOffset)=='number') {
			scrOfY = window.pageYOffset;
		} else if(document.body && document.body.scrollTop) {
			scrOfY = document.body.scrollTop;
		} else if(document.documentElement && document.documentElement.scrollTop) {
			scrOfY = document.documentElement.scrollTop;
		}
		return scrOfY;
	},
	getDocumentWidth : function() {
		if (document.documentElement && document.documentElement.scrollWidth)
			return document.documentElement.scrollWidth;
		else if (document.body && document.body.scrollWidth)
			return document.body.scrollWidth;
		var D = document;
		return Math.max(
			Math.max(D.body.scrollWidth, D.documentElement.scrollWidth),
			Math.max(D.body.offsetWidth, D.documentElement.offsetWidth),
			Math.max(D.body.clientWidth, D.documentElement.clientWidth)
		);
	},
	getDocumentHeight : function() {
		if (document.documentElement && document.documentElement.scrollHeight)
			return document.documentElement.scrollHeight;
		else if (document.body && document.body.scrollHeight)
			return document.body.scrollHeight;
		var D = document;
		return Math.max(
			Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
			Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
			Math.max(D.body.clientHeight, D.documentElement.clientHeight)
		);
	}
};
/**
 * NextBits Layer function
 */
NB.layer = function(identifier) {
	this._container = document.getElementById('ps-'+identifier);
	if (!this._container) {
		this._container = document.createElement('div');
		this._container.id = 'ps-'+identifier;
		this._container.style.display = 'none';
		this._container.style.position = 'absolute';
		this._container.style.left = '0px';
		this._container.style.top = '0px';
		this._html = document.createElement('div');
		this._html.id = 'pshtml-'+identifier;
		document.body.appendChild(this._container);
		this._container.appendChild(this._html);
	}
}
NB.layer.prototype.setClass = function(className) {
	this._container.className = className;
	return this;
}
NB.layer.prototype.fullScreen = function() {
	this._container.style.width = NB.window.getDocumentWidth().toString()+'px';
	this._container.style.height = NB.window.getDocumentHeight().toString()+'px';
	this._container.style.display = 'block';
}
NB.layer.prototype.center = function() {
	var posX = (NB.window.getScrollX() + Math.round(NB.window.getWidth() / 2));
	var posY = (NB.window.getScrollY() + Math.round(NB.window.getHeight() / 2));
	var elWidth = $(this._container).getWidth();
	var elHeight = $(this._container).getHeight();
	this._container.style.left = (posX-Math.round(elWidth/2)).toString()+'px';
	this._container.style.top = (posY-Math.round(elHeight/2)).toString()+'px';
	this._container.style.display = 'block';
}
NB.layer.prototype.hide = function() {
	this._html.innerHTML = '';
	this._container.style.display = 'none';
}
NB.layer.prototype.isHide = function() {
	return (this._container.style.display == 'none')?true:false;
}
NB.layer.ajax = function(url, blocks, methodForm, postData, specialFunction, cacheOn) {
	if ((typeof window._NBLayerAjax != 'undefined') && window._NBLayerAjax)
		return false;
	window._NBLayerAjax = true;
	
	if (typeof postData == 'undefined') postData = {};
	if (typeof blocks == 'undefined') blocks = {'content':'content'};
	if (typeof methodForm == 'undefined') methodForm = 'GET';
	var params = {};
	for (var key in blocks) {
		params['nb'+key] = blocks[key];
	}
	var m = NB.layer.manager();
	m.background.fullScreen();
	m.loader.center();
	if (typeof specialFunction != 'function') {
		specialFunction = function(transport) {
			var response = transport.responseText || "";
			var data = eval('('+response+')');
			var m = NB.layer.manager();
			
			for (var key in data) {
				data[key]._html = document.createElement('div');
				data[key]._html.className = 'ps-'+data[key].selector;
				data[key]._html.innerHTML = data[key].html;
				m.content._html.appendChild(data[key]._html);
			}
			m.loader.hide();
			m.content.center();
			var scripts = m.content._html.getElementsByTagName('script');
			for (var i=0;i<scripts.length;i++) {
				eval(scripts[i].innerHTML);
			}
		};
	}
	if (typeof window._NBAjaxCacheData == 'undefined') window._NBAjaxCacheData = {};
	var cacheKey = false;
	if ((typeof cacheOn != 'undefined') && cacheOn) {
		cacheKey = NB.getCacheKey({'url':url,'params':params,'postData':postData});
		if (typeof window._NBAjaxCacheData[cacheKey] != 'undefined') {
			window._NBLayerAjax = false;
			var m = NB.layer.manager();
			if (m.loader.isHide()) return;
			specialFunction(window._NBAjaxCacheData[cacheKey]);
			return;
		}
	}
	if ("https:" == document.location.protocol) {
		url = url.toString().replace('http://','https://');
	}
	var obj = new Ajax.Request(url,{
		method: methodForm,
		parameters: params,
		data: postData,
		onSuccess: function(transport) {
			window._NBLayerAjax = false;
			var m = NB.layer.manager();
			if (m.loader.isHide()) return;
			var el = this;
			window._NBAjaxCacheData[cacheKey] = transport;
			specialFunction(transport, el)
		}
	});
}
NB.layer.manager = function() {
	if (typeof NB.layer.manager.background == 'undefined') {
		NB.layer.manager.background = new NB.layer('overlay');
		NB.layer.manager.background.setClass('nb-overlay');
		NB.layer.manager.background._container.onclick = function() {
			NB.layer.manager.close();
		}
	}
	if (typeof NB.layer.manager.loader == 'undefined') {
		NB.layer.manager.loader = new NB.layer('loader');
		NB.layer.manager.loader.setClass('nb-loader');
	}
	if (typeof NB.layer.manager.content == 'undefined') {
		NB.layer.manager.content = new NB.layer('content');
		NB.layer.manager.content.setClass('nb-content');
	}
	return NB.layer.manager;
}
NB.layer.manager.close = function() {
	if (!NB.layer.manager.loader.isHide()) return;
	if (typeof NB.layer.manager.background != 'undefined') {
		NB.layer.manager.background.hide();
	}
	if (typeof NB.layer.manager.loader != 'undefined') {
		NB.layer.manager.loader.hide();
	}
	if (typeof NB.layer.manager.content != 'undefined') {
		NB.layer.manager.content.hide();
	}
}

NB.ajax = {
	call : function(url, blocks) {
		jQuery('#loading-mask').show();
		var isQuery = ((url.length>0) && (url[0]=='?') || (url.indexOf('?')>0))?true:false;
		var params = {};
		for (var key in blocks) {
			params['nb'+key] = blocks[key];
			
		}
		console.log(params);
		if ("https:" == document.location.protocol) {
			url = url.toString().replace('http://','https://');
		}
		var obj = new Ajax.Request(url,{
			method:'get',
			parameters: params,
			onSuccess: function(transport){
				var response = transport.responseText || "";
				var data = eval('('+response+')');
				var selector;
				var html;
				var destEls;
				var regs;
				var replaceElement = false;
				var destEl; var srcEl;
				var temp;
				for (var blockName in data) {
					selector = data[blockName].selector;
					regs = selector.split('|');
					replaceElement = false;
					if (regs.length>1) {
						selector = regs[0];
						replaceElement = regs[1];
					}
					destEls = $$(selector);
					if (!destEls.length) continue;
					if (replaceElement) {
						destEls = $(destEls[0]).getElementsBySelector(replaceElement);
						if (!destEls.length) continue;
						temp = document.createElement('div');
						temp.innerHTML = data[blockName].html;
						srcEl = $(temp).getElementsBySelector(replaceElement);
						if (!srcEl.length) continue;
						srcEl = srcEl[0];
						destEls[0].style.display = 'none';
						destEls[0].parentNode.insertBefore(srcEl, destEls[0]);
						destEls[0].parentNode.removeChild(destEls[0]);
						temp = null;
						destEl = destEls[0];
					} else {
						destEl = destEls[0];
						//alert(data[blockName].html);
						destEl.innerHTML = data[blockName].html;
					}
					
					NB.evalJsOnContent(destEl);
				}
				NB._onload();
				jQuery('#loading-mask').hide();
			}
		});

	}
};
/**
 * NextBits Catalog Quick View Image
 */
NB.catalogQuickViewImage = function(selector) {
	if (typeof selector == 'undefined')
		selector = 'a.product-image'
	var els = $$(selector);
	for (var i=0;i<els.length;i++) {
		els[i].onclick = function() {
			var href = this.href;
			NB.layer.ajax(href, {'product.info.media':'media'}, undefined, undefined, function(transport) {
				var response = transport.responseText || "";
				var data = eval('('+response+')');
				var key = 'product.info.media';
				
				if (typeof data[key] == 'undefined') {
					NB.layer.manager.close();
					return;
				}
				var Re = new RegExp('id\=\"image\" src\=\"([^\"]+)\"');
				var res = Re.exec(data[key].html);
				if (!res) {
					NB.layer.manager.close();
					return;
				}
				var srcImg = res[1];
				
				var imgPreload = new Image();
				imgPreload.__onload = function() {
					this.style.visibility = 'hidden';
					this.style.width = 'auto';
					this.style.height = 'auto';
					var m = NB.layer.manager();
					
					var el = this;
					this._width = (this.width)?this.width:$(el).getWidth();
					this._height = (this.height)?this.height:$(el).getHeight();
					
					if (!this._width || !this._height) {
						NB.removeCurrentElement(this);
						NB.layer.manager.close();
						return;
					}
					m.loader.hide();
					
					var borderContainer = document.createElement('div')
					borderContainer.className = 'nb-content-layerimage';
					borderContainer.appendChild(el);
					borderContainer.onclick = function() {
						NB.layer.manager.close();
					};
					m.content._html.appendChild(borderContainer);
					var winWidth = NB.window.getWidth();
					var winHeight = NB.window.getHeight();
					winWidth-=40;
					winHeight-=40;
					var scale = 1;
					var scaleWidth;
					var scaleHeight;
					scaleWidth = this._width;
					scaleHeight = this._height;
					if (scaleWidth > winWidth) {
						scale = scaleWidth / winWidth;
						scaleHeight = Math.round(scaleHeight/scale);
						scaleWidth = winWidth;
					}
					if (scaleHeight > winHeight) {
						scale = scaleHeight / winHeight;
						scaleWidth = Math.round(scaleWidth/scale);
						scaleHeight = winHeight;
					}
					this.style.width = scaleWidth.toString()+'px';
					this.style.height = scaleHeight.toString()+'px';
					this.style.visibility = 'visible';
					this.style.position = 'static';
					this.style.top = 'auto';
					this.style.left = 'auto';
					m.content.center();
				}
				imgPreload.src = srcImg;
				imgPreload.style.width='1px';
				imgPreload.style.height='1px';
				imgPreload.style.top='0px';
				imgPreload.style.left='0px';
				imgPreload.style.position='absolute';
				imgPreload.onload = setTimeout(function(){
					if ((typeof imgPreload._postAction != 'undefined') && imgPreload._postAction) return;
					imgPreload.__onload();
				},250);
				document.body.appendChild(imgPreload);
				if ($(imgPreload).getWidth() && $(imgPreload).getHeight()) {
					imgPreload._postAction = true;
					setTimeout(function(){
						imgPreload.__onload();
					},250);
				}
			}, true);
			return false;
		}
	}
}
NB.catalogQuickViewImage.init = function(selector) {
	if (typeof window._catalogQuickViewImageLoaded != 'undefined') {
		NB.catalogQuickViewImage(selector);
		return;
	}
	document.observe("dom:loaded", function() {
		NB.catalogQuickViewImage(selector);
		window._catalogQuickViewImageLoaded = true;
	});
}
NB.catalogajax = {
	init : function() {
		NB.catalogajax._initElementsByType('paginator', 'content');
		NB.catalogajax._initElementsByType('mode', 'content');
		NB.catalogajax._initElementsByType('limiter', 'content');
		NB.catalogajax._initElementsByType('sortby', 'content');
		NB.catalogajax._initElementsByType('sortdir', 'content');
		NB.catalogajax._initElementsLayerNav(NB.catalogajax.config.navfilter);
		NB.catalogajax._initElementsLayerNav(NB.catalogajax.config.navclear);
		NB.catalogajax._initElementsLayerNav(NB.catalogajax.config.navremove);
	},
	loadData : function() {
		var url = this.getAttribute('href');
		NB.catalogajax.beforeLoad(NB.catalogajax.config.blocks.content.selector+((typeof NB.catalogajax.config.blocks.content.replace == 'undefined')?'':' '+NB.catalogajax.config.blocks.content.replace));
		NB.ajax.call(url,this._catalogajax);
		return false;
	},
	loadDataBySelect : function(selectElement, url) {
		if (!url) return false;
		NB.catalogajax.beforeLoad(NB.catalogajax.config.blocks.content.selector+((typeof NB.catalogajax.config.blocks.content.replace == 'undefined')?'':' '+NB.catalogajax.config.blocks.content.replace));
		NB.ajax.call(url,selectElement._catalogajax);
		return false;
	},
	loadLayerData : function() {
		var url = this.getAttribute('href');
		NB.catalogajax.beforeLoad(NB.catalogajax.config.blocks.content.selector+((typeof NB.catalogajax.config.blocks.content.replace == 'undefined')?'':' '+NB.catalogajax.config.blocks.content.replace));
		NB.catalogajax.beforeLoad(NB.catalogajax.config.blocks.layer.selector+((typeof NB.catalogajax.config.blocks.layer.replace == 'undefined')?'':' '+NB.catalogajax.config.blocks.layer.replace));
		NB.ajax.call(url,this._catalogajax);
		return false;
	},
	beforeLoad : function(selector) {
		NB.eachFunc(selector, NB.overlayElement);
	},
	_initElementsLayerNav : function(selector) {
		if (typeof selector != 'string') {
			if (typeof selector.length == 'undefined') {
				throw 'bad selector';
			}
			for (var j=0;j<selector.length;j++) {
				NB.catalogajax._initElementsLayerNav(selector[j]);
			}
			return;
		}
		var els = $$(selector);
		for (var i=0;i<els.length;i++) {
			if (typeof els[i]._catalogajax != 'undefined')
				continue;
			els[i]._catalogajax = {};
			els[i]._catalogajax[NB.catalogajax.config.blocks.content.name] = NB.catalogajax.config.blocks.content.selector+((typeof NB.catalogajax.config.blocks.content.replace == 'undefined')?'':'|'+NB.catalogajax.config.blocks.content.replace);
			els[i]._catalogajax[NB.catalogajax.config.blocks.layer.name] = NB.catalogajax.config.blocks.layer.selector+((typeof NB.catalogajax.config.blocks.layer.replace == 'undefined')?'':'|'+NB.catalogajax.config.blocks.layer.replace);
			els[i].onclick = NB.catalogajax.loadLayerData;
		}
	},
	_initElementsByType : function(type, block) {
		var selector = NB.catalogajax.config[type];
		if (typeof selector != 'string') {
			if (typeof selector.length == 'undefined') {
				throw 'bad param config for: `'+type+'`';
			}
			for (var j=0;j<selector.length;j++) {
				NB.catalogajax._initElementsBySelector(selector[j], block);
			}
			return;
		}
		NB.catalogajax._initElementsBySelector(selector, block);
	},
	_initElementsBySelector : function(selector, block) {
		var isSelect = (NB.substr(selector,selector.length-6,6)=='select')?true:false;
		var els = $$(selector);
		var attVal;
		for (var i=0;i<els.length;i++) {
			if (typeof els[i]._catalogajax != 'undefined')
				continue;
			els[i]._catalogajax = {};
			els[i]._catalogajax[NB.catalogajax.config.blocks.content.name] = NB.catalogajax.config.blocks.content.selector+((typeof NB.catalogajax.config.blocks.content.replace == 'undefined')?'':'|'+NB.catalogajax.config.blocks.content.replace);
			if (isSelect) {
				els[i].onchange = function(){
					NB.catalogajax.loadDataBySelect(this, this.value);
				}
			} else {
				els[i].onclick = NB.catalogajax.loadData;
			}
		}
	}
};
NB.onload(function(){NB.catalogajax.init()});

NB.catalogajax.config = {
	'paginator' : '.toolbar .pager .pages a',
	'limiter'	: '.toolbar .pager .limiter select',
	'mode'		: '.toolbar .sorter .view-mode a',
	'sortby'	: '.toolbar .sorter .sort-by select',
	'sortdir'	: '.toolbar .sorter .sort-by a',
	'navfilter'	: '.block-layered-nav .block-content ol li a',
	'navclear'	: '.block-layered-nav .block-content .actions a',
	'navremove'	: '.block-layered-nav .block-content .currently ol li a',
	'blocks'	: {
		'content'	: {
			'name'		: 'content',
			'selector'	: '.page .main .col-main'
		},
		'layer'		: {
			'name'		: 'catalog.leftnav',
			'selector'	: '.page .main .col-left',
			'replace'	: '.block-layered-nav'
		}
	}
};