/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


window.utils = function(utils) {
    utils.stringify = function(obj) {
        //if(!errorList) var errorList = [];
        try {
            return JSON.stringify(obj);
        } catch (err) {
            //errorList.push(err)
            return utils.stringify({
                msg: "cannot convert JSON to string",
                error: [err]
            });
        }
    };
    utils.parse = function(str, throwExcep) {
        if (typeof (str) == 'object')
            return str;
        try {
            return JSON.parse(str);
        } catch (err) {
            try {
                return $.parseJSON(str);
            } catch (err2) {
                var errorMSG = {msg: "cannot convert to JSON object", error: [err, err2], str: str};
                if (throwExcep)
                    throw err2;
                else
                    LOG.error(errorMSG);
                return errorMSG;
            }
        }
    };
    utils.selectObject = function(win, namespace, cb, map) {
        if (!win)
            var win = window;
        var nspace = namespace.split('.');
        var retspace = nspace[0];
        for (var i = 0; i < nspace.length; i++) {
            if (!win[nspace[i]])
                win[nspace[i]] = {};
            win[nspace[i]].name = nspace[i];
            retspace = nspace[i];
            win = win[retspace];
        }
        win.namespace = namespace;
        win.namespace_ = win.namespace_ + ".";
        if (map) {
            for (var key in map) {
                win[key] = map[key];
            }
        }
        if (cb)
            cb(win, retspace);
        return win;
    };
    utils.selectNamespace = function(namespace, cb, map) {
        return this.selectObject(window, namespace, cb, map);
    };
    utils.queue = function(cb, lotSize, delay) {
        //var dq = [];
        //var nq = [];
        var queue = {front: 0, rear: 0, map: {}, cb: cb};
        queue.empty = true;
        if (queue.cb) {
            queue.free = true;
            queue.lotSize = lotSize || 1;
            queue.delay = delay || 0;
        }
        queue.size = function() {
            return this.front - this.rear;
        }
        queue.put = function(obj) {
            //nq.push(obj);
            this.map[this.front++] = obj;
            queue.empty = false;
            if (this.free)
                this.executeStart();
        };
        queue.get = function() {
            var retObj = this.map[this.rear];
            delete this.map[this.rear];
            (this.front > this.rear) ? (this.rear++) : (this.empty = true);
            return retObj;
        };
        queue.executeStart = function() {
            this.free = false;
            this.to = setTimeout(this.execute, this.delay)
        };
        queue.execute = function() {
            for (var i = 0; i < queue.lotSize; i++) {
                var rep = queue.get();
                if (rep) {
                    queue.cb(rep);
                } else
                    break;
            }
            if (queue.size() > 0)
                queue.executeStart();
            else
                queue.free = true;
        };
        return queue;
    };
    utils.execute = {
        delta: 500,
        once: function(cb, delta, obj) {
            this.__ = this.__ || {};
            this.__.rtime = new Date();
            if (!this.__.timeout) {
                this.__.timeout = true;
                setTimeout(utils.execute.now.bind(this), delta || 200);
                this.__.cb = cb;
                this.__.obj = obj;
            }
        }, now: function() {
            if (new Date() - this.__.rtime < this.__.delta) {
                setTimeout(utils.execute.now.bind(this), this.__.delta);
            } else {
                this.__.timeout = false;
                if (this.__.cb)
                    this.__.cb.call(this, this.__, this.obj);
            }
        }
    };
    window.onresize = function(e) {
        return utils.execute.once(function() {
            if (window.onresizeend)
                window.onresizeend(this.__);
            if (utils.onResize)
                utils.onResize(this.__);
        }, utils.execute.delta);
    };
    return utils;
}({});