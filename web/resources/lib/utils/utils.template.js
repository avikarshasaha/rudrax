utils.define('utils.template', function(template) {
	template.RCLASS = /[\t\r\n\f]/g;
	template.TAG_CLASS = 'tag';
	template.TEMPLATES = {};
	var TemplateODO = function($HTML, cb,_data_) {
		var TEMP = new ODO(_data_);
		TEMP.$HTML = $HTML;
		TEMP.content = {};
		TEMP.tags = {};
		TEMP.display = {};
		// console.log('==',TEMP,TEMP.content)
		TEMP.tracker = cb || function(e, elem) {};
		TEMP.formatter = TEMP.formatter || function(e, elem) {};
		
		TEMP.sub('*', function(dEvent, b, c, e, THIS) {
			var elem;
			if (this.content[dEvent.path]) {
				for(var i in this.content[dEvent.path]){
					elem = this.content[dEvent.path][i];
					if(this.formatter(elem.getAttribute('formatter'),elem,dEvent.value)==undefined){
						if(elem.tagName=='INPUT' || elem.tagName=='SELECT'){
							elem.value = dEvent.value
						} else elem.innerHTML = dEvent.value;						
					}
				}			
			} if (this.tags[dEvent.path]) {
				for(var i in this.tags[dEvent.path]){
					elem = this.tags[dEvent.path][i];
					if(this.formatter(elem.getAttribute('formatter'),elem,dEvent.value)==undefined){
						$(elem).setValue(dEvent.value);
					}
				}
			}  if(this.display[dEvent.path]){
				for(var i in this.display[dEvent.path]){
					elem = this.display[dEvent.path][i];
					if(this.formatter(elem.getAttribute('formatter'),elem,dEvent.value)==undefined){
						if(value){
							$(elem).addClass('dn')
						} else removeClass('dn')
					}
				}
			}
		});
		return TEMP;
	};
	template.create = function($HTML, cb,_data_) {
		var TEMP = new TemplateODO($HTML,cb,_data_);
		template.bind(TEMP);
		TEMP.$HTML.on('click','.tag.button',function(e){
			var fieldType = this.getAttribute('fieldType');
			return TEMP.button_onclick && TEMP.button_onclick(fieldType,e,this);
		});
		TEMP.$HTML.on('click','[onClick]',function(e){
			var onClickFunction = this.getAttribute('onClick');
			return TEMP.node_onclick && TEMP.node_onclick(onClickFunction,e,this);
		});
		TEMP.$HTML.on('change',':not(.tag) input[data-content]',function(e){
			var $input = $(this);
			var fieldType = $input.attr('name') || $input.attr('fieldType');
			var path = $input.attr('data-content');
			return TEMP.input_onchange && TEMP.input_onchange(fieldType,path,e,$input);
		});
/*		TEMP.$HTML.addClass('block').onChange(function(e,uE){
			var fieldType = uE.$widget.attr('fieldType');
			e.onChangeFunction = uE.$widget.attr('onChange');
			var path = uE.$widget.attr('data-content');
			return TEMP.field_onchange && TEMP.field_onchange(fieldType,path,uE,uE.$widget);
		});*/
		return TEMP;
	};
	template.bind = function(TEMP) {
		var attribute = 'data-content';
		$("[" + attribute + "]", TEMP.$HTML).each(
				function() {
					var $this = this, param = $this.getAttribute(attribute);
					if ((" " + $this.className + " ").replace(template.rClass,
							" ").indexOf(template.TAG_CLASS) >= 0) {
						TEMP.tags[param] = TEMP.tags[param] || [];
						TEMP.tags[param].push(this);
					} else{
						TEMP.content[param] = TEMP.content[param] || [];
						TEMP.content[param].push(this);
					}
				});
		$("[data-showif]", TEMP.$HTML).each(
				function() {
					var $this = this, param = $this.getAttribute(attribute);
					TEMP.display[param] = TEMP.display[param] || [];
					TEMP.display[param].push(this);
				});
	};
	template.define = function(temp, initCB) {
		return this.TEMPLATES[temp] = initCB;
	};
});

utils.define("utils.template", function(template) {
	template.HTML_CACHE = {};
	template.PENDING_TEMP = {};
	template.Q = utils.queue();

	template.$div_onready = function(OBJ) {
		if (OBJ.replace && OBJ._parent)
			OBJ._parent.html(OBJ.$div);
		else if (OBJ._parent)
			OBJ._parent.append(OBJ.$div);
		OBJ.ODO = utils.template.create(OBJ.$div, OBJ.tracker,OBJ._data_);

		OBJ.sub = function(cb,a,b,c,d,e) {
			if(!this.ODO.SLAVE) OBJ.ODO.initSlave();
			return this.ODO.SLAVE.sub(cb,a,b,c,d,e);
		};
		OBJ.updateData = OBJ.update= function(obj) {
			return this.ODO.update(obj);
		};
		OBJ.change = OBJ.changeValue = OBJ.change = function(key, value) {
			this.ODO.change(key, value);
			return this.ODO.SLAVE.change(key, value);
		};
		OBJ.set = function(key, value) {
			return this.ODO.change(key, value);
		};
		OBJ.get = function(key) {
			var value = this.ODO.get(key);
			if(value==undefined){
				value = $("[data-content='" + key + "']", this.ODO.$HTML).getValue();
			}
			return value;
		};
		OBJ.$path = function(key) {
			return $(this.ODO.content[key]).add(this.ODO.tags[key]);
		}
		OBJ.$ = function(selector) {
			return $(selector, this.ODO.$HTML);
		};
		OBJ.ODO.formatter = function(formatterName,elem,value) {
			if(formatterName && OBJ[formatterName]) return OBJ[formatterName](elem,value);
		};
		OBJ.ODO.button_onclick = function(fieldType,e,elem) {
			e.fieldType = fieldType;
			if(OBJ[fieldType+"_onclick"]) return OBJ[fieldType+"_onclick"](e,elem);
		};
		OBJ.ODO.node_onclick = function(clickFunction,e,elem) {
			e.clickFunction = clickFunction;
			if(OBJ[clickFunction]) return OBJ[clickFunction](e,elem);
		};
		OBJ.ODO.field_onchange = function(fieldType,path,e,$elem) {
			if(path){
				if(this.SLAVE) this.SLAVE.change(path, $elem.getValue());
				else this.set(path, $elem.getValue());
			}
			e.fieldType = fieldType;
			if(e.onChangeFunction){
				if(OBJ[e.onChangeFunction]) return OBJ[e.onChangeFunction](e,$elem,path);
			}
			if(OBJ[fieldType+"_onchange"]) return OBJ[fieldType+"_onchange"](e,$elem,path);
			else if(OBJ._onchange) return OBJ._onchange(e,$elem,path);
		};
		OBJ.ODO.input_onchange = function(fieldType,path,e,$elem) {
			if(path){
				if(this.SLAVE) this.SLAVE.change(path, $elem.getValue());
				else this.set(path, $elem.getValue());
			}
			e.fieldType = fieldType;
			if(e.onChangeFunction){
				if(OBJ[e.onChangeFunction]) return OBJ[e.onChangeFunction](e,$elem,path);
			}
			if(OBJ[fieldType+"_onchange"]) return OBJ[fieldType+"_onchange"](e,$elem,path);
			else if(OBJ["_onchange"]) return OBJ["_onchange"](e,$elem,path);
		};
		if(OBJ.__cb__){
			for(var i in OBJ.__cb__){
				var _x_ = OBJ.__cb__[i];
				OBJ.sub(_x_.cb,_x_.a,_x_.b,_x_.c)
			}
			OBJ.__cb__ = null;
		}
		if (OBJ.onload)
			OBJ.onload();
		delete template.PENDING_TEMP[OBJ.template];
		var nextOBJ = template.Q.get();
		if (nextOBJ) {
			template.load(nextOBJ);
		}
	};
	template.Template  = function Template(OBJ){
		this.___isTemp___ = true;
		for(var i in OBJ){
			this[i]= OBJ[i]
		}
	};
	template.load = function(_OBJ,arg0,arg1,arg2,arg3,arg4) {
		var OBJ = new template.Template (_OBJ);
		OBJ.template = OBJ.template || OBJ.name;
		if (template.PENDING_TEMP[OBJ.template]) {
			template.Q.put(OBJ);
		} else {
			//utils.require('/js/template/' + OBJ.template + ".js");
			OBJ.sub = function(cb,a,b,c){
				OBJ.__cb__ = OBJ.__cb__ || [];
				OBJ.__cb__.push({
					cb : cb,a :a, b : b, c: c
				})
			}
			if (template.TEMPLATES[OBJ.template]){
				template.TEMPLATES[OBJ.template](OBJ,arg0,arg1,arg2,arg3,arg4);
			}
			OBJ._parent = OBJ.parent;
			delete OBJ.parent;
			if (OBJ.$div) {
				setTimeout(function(){
					return template.$div_onready(OBJ);					
				},0)
			} else {
				if (OBJ.cached && template.HTML_CACHE[OBJ.template]) {
					OBJ.$div = $(template.HTML_CACHE[OBJ.template]);
					template.$div_onready(OBJ);
				} else {
					OBJ.success = function(html, $html) {
						if (this.cached)
							template.HTML_CACHE[OBJ.template] = html;
						this.$div = $(html);
						template.$div_onready(this);
					}
					OBJ.method = "POST";
					OBJ.HTMLType = "template";
					template.PENDING_TEMP[OBJ.template] = true;
					template.loadHTML(OBJ);
				}
			}
		}
		return OBJ;
	};
	template.DEBUG;
	template.loadHTML = function(obj) {
		// if(!obj.parent) obj.parent = $("body");
		var loadSpinner = null;
		/*if ($C && $C.miniloader && obj.showLoader)
			$C.miniloader.show(obj.parent);*/

		if (!obj.HTMLType)
			obj.HTMLType = "html";
		if (!obj.method)
			obj.method = "GET";
		if (!obj[obj.HTMLType])
			obj[obj.HTMLType] = "index";

		var postParams = {
			//mod : 'debug',
			data : utils.stringify(obj.data) || "",
			mod : template.DEBUG,
			uitoken : window.uitoken
		};
		postParams[obj.HTMLType] = obj[obj.HTMLType];

		if (obj.params)
			postParams.params = utils.stringify(obj.params);
		$.ajax({
			datatype : "html",
			url: "template.php?&t=" + obj.name,
			type : obj.method,
			data : postParams,
			success : function(html) {
				//if($C && $C.miniloader) $C.miniloader.stop(loadSpinner);
				var $html = $(html)
				if (obj.replace && obj.parent)
					obj.parent.html($html);
				else if (obj.parent)
					obj.parent.append($html);
				if (obj.success)
					obj.success(html, $html);
			},
			error : function(msg) {
				console.log('error', msg)
				// namespace.check401(msg);
				return null// bigpipe.throwError(msg.statusText);
			}
		});
		return obj;
	};
});