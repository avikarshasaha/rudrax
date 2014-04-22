 utils.selectNamespace('utils.template', function(template) {
    template.RCLASS = /[\t\r\n\f]/g;
    template.TAG_CLASS = 'tag';
    template.TEMPLATES = {};
    var Template = function($HTML, cb) {
        var TEMP = new ODO();
        TEMP.$HTML = $HTML;
        TEMP.content = {};
        TEMP.tags = {};
        //console.log('==',TEMP,TEMP.content)
        TEMP.tracker = cb || function(e, elem) {
        };
        TEMP.sub('*', function(a, b, c, e, THIS) {
            var elem;
            if (THIS.content[e.key]) {
                elem = THIS.content[e.key];
                elem.innerHTML = e.value
            }
            else if (THIS.tags[e.key]) {
                elem = THIS.tags[e.key];
                $(elem).setValue(e.value)
            }
            ;
            THIS.tracker(e, elem);
        });
        return TEMP;
    };
    template.create = function($HTML, cb) {
        var TEMP = new Template($HTML, cb);
        template.bind(TEMP);
        return TEMP;
    };
    template.bind = function(TEMP) {
        var attribute = 'data-content';
        $("[" + attribute + "]", TEMP.$HTML).each(function() {
            var $this = this, param = $this.getAttribute(attribute);
            if ((" " + $this.className + " ").replace(template.rClass, " ").indexOf(template.TAG_CLASS) >= 0) {
                TEMP.tags[param] = this;
            } else
                TEMP.content[param] = $this;
        });
    };
    template.define = function(temp,initCB){
        return this.TEMPLATES[temp] = initCB;
    };
});

 utils.selectNamespace("utils.template", function(template) {
    template.HTML_CACHE = {};
    template.PENDING_TEMP = {};
    template.Q = utils.queue();

    template.$div_onready = function(OBJ) {
        if (OBJ.replace && OBJ._parent)
            OBJ._parent.html(OBJ.$div);
        else if (OBJ._parent)
            OBJ._parent.append(OBJ.$div);
        OBJ.ODO = utils.template.create(OBJ.$div, OBJ.tracker);
        OBJ.updateData = function(obj) {
            return this.ODO.update(obj);
        };
        OBJ.setValue = function(key, value) {
            return this.ODO.changeValue(key, value);
        };
        //console.log(OBJ.i,OBJ,OBJ.$div)
        if (OBJ.onload)
            OBJ.onload();
        delete template.PENDING_TEMP[OBJ.template];
        var nextOBJ = template.Q.get();
        if (nextOBJ) {
            template.load(nextOBJ);
        }
    };

    template.load = function(OBJ) {
        OBJ.template = OBJ.template || OBJ.name;
        if (template.PENDING_TEMP[OBJ.template]) {
            template.Q.put(OBJ);
        } else {
            utils.require('/js/template/'+OBJ.template+".js");
            if(template.TEMPLATES[OBJ.template]) template.TEMPLATES[OBJ.template](OBJ);
            OBJ._parent = OBJ.parent;
            delete OBJ.parent;
            if (OBJ.$div) {
                template.$div_onready(OBJ);
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

    template.loadHTML = function(obj) {
        //if(!obj.parent) obj.parent = $("body");
        if (!obj.HTMLType)
            obj.HTMLType = "html";
        if (!obj.method)
            obj.method = "GET";
        if (!obj[obj.HTMLType])
            obj[obj.HTMLType] = "index";

        var postParams = {
            data: utils.stringify(obj.data) || "",
            uitoken: "", //window.uitoken
            mod: 'debug'
        };
        postParams[obj.HTMLType] = obj[obj.HTMLType];

        if (obj.params)
            postParams.params = utils.stringify(obj.params);
        $.ajax({
            datatype: "html",
            url: "?&t=" + obj.name,
            type: obj.method,
            data: postParams,
            success: function(html) {
                var $html = $(html)
                if (obj.replace && obj.parent)
                    obj.parent.html($html);
                else if (obj.parent)
                    obj.parent.append($html);
                if (obj.success)
                    obj.success(html, $html);
            },
            error: function(msg) {
                console.log('error', msg)
                //namespace.check401(msg);
                return null//bigpipe.throwError(msg.statusText);
            }
        });
        return obj;
    };
});
