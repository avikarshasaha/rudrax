var ODO = utils.odo = function() {
    this.data = {};
    var _data, _onKey, changeKey;
    this.onchange_map = {};
    this.refineKey = function(_key) {
        return _key.replace(/\[/gi, '#').replace(/\]/gi, '');
    }
    // updates/extends the data map from src map
    this.update = function(src) {
        return this._update(this.data, src, '');
    }
    // sets the value of node at given path
    this.setValue = function(_key, finalValue) {
        var key = this.refineKey(_key);
        this.getRef(key);
        //var changeKey = key.replace(/#[0-9]+/gi, '#');
        _data[_onKey] = finalValue;
    };
    // sets the value of node at given path, also triggers onChange on UI-side
    this.changeValue = function(_key, finalValue) {
        var key = this.refineKey(_key);
        this.getRef(key);
        _data[_onKey] = finalValue;
        this.callFun(key, finalValue);
    };
    // the value of node at given path
    this.getValue = function(_key) {
        var key = this.refineKey(_key);
        this.getRef(key);
        return _data[_onKey];
    };
    // converts the data map to json string
    this.toJson = function() {
        return utils.stringify(this.data);
    };
    // returns the reference to a node
    this.getRef = function(key) {
        _data = this.data;
        changeKey = '';
        var keys = key.replace(/#/gi, '#.').split('.');
        changeKey = _onKey = keys[0];
        // this.callFun(changeKey);
        for (var i = 0; i < keys.length - 1; i++) {
            var nextKey = keys[i + 1];
            var isArray = (_onKey.indexOf('#') !== -1)
            _onKey = _onKey.replace('#', '');
            if (!_data[_onKey]) {
                if (!isArray)
                    _data[_onKey] = {};
                else
                    _data[_onKey] = [];
            }
            _data = _data[_onKey];
            _onKey = nextKey;
            changeKey = changeKey + (isArray ? '#' : ('.' + nextKey));
        }
    };

    this._update = function(_target, src, path) {
        // copy reference to target object
        var target = _target || {}, options;
        // Handle case when target is a string or something
        if (typeof target !== "object" && !jQuery.isFunction(target))
            target = {};
        // Only deal with non-null/undefined values
        if ((options = src) != null) {
            // Extend the base object
            for (var name in options) {
                var src = target[name], copy = options[name];
                // Prevent never-ending loop
                if (target === copy)
                    continue;
                // Recurse if we're merging object values
                if (copy && typeof copy === "object" && !copy.nodeType) {
                    target[name] = this._update(src || (copy.length != null ? [] : {}), copy, path + name
                            + (copy.length != null ? '#' : '.'));
                } else if (copy !== undefined) {
                    // Don't bring in undefined values
                    target[name] = copy;
                    this.callFun(path + name, copy);
                }
            }
        }
        // Return the modified object
        return target;
    }

    this.sub = function(_key, fun) {
        var key = this.refineKey(_key);
        var keys = key.split(/[#\.]+/gi);
        var ref = this.onchange_fun;
        var _nextKey = keys[0];
        var _key = keys[0];
        for (var i = 0; i < keys.length - 1; i++) {
            _key = keys[i];
            _nextKey = keys[i + 1];
            _atKey = '@' + _key;
            if (!ref[_atKey])
                ref[_atKey] = {
                    key: _key, next: this.next, nextKey: _nextKey
                };
            ref = ref[_atKey];
        }
        ref['@' + _nextKey] = {
            key: _nextKey, next: fun, nextKey: null
        };
    };

    // execute event handler
    this._callFun = function(key, val, THIS) {
        var keys = key.split(/[#\.]+/gi);
        if (this.onchange_fun.next) {
            this.onchange_fun.next([], 0, keys, {key: key, value: val}, this);
        }
        // if (this.onchange_fun['@' + keys[0]]) {
        // this.onchange_fun['@' + keys[0]].next([], 0, keys);
        // }
    };

    this.next = function(list, index, keys, e, THIS) {
        if (this['@' + keys[index]]) {
            return  this['@' + keys[index]].next(list, index + 1, keys, e, THIS)
        } else if (this['@*']) {
            list.push(keys[index]);
            return this['@*'].next(list, index + 1, keys, e, THIS);
        } else if (this['@#']) {
            list.push(keys[index]);
            return this['@#'].next(list, index + 1, keys, e, THIS);
        }
    };
    this.onchange_fun = {
        next: this.next,
    };
    // Registers an event to be triggered
    this.callFun = function(key, val) {
        this.onchange_map[key] = val;
        utils.execute.once.call(this, this.trigger.bind(this));
    };

    // process event queue
    this.trigger = function() {
        for (var key in this.onchange_map) {
            this._callFun(key, this.onchange_map[key]);
            delete this.onchange_map[key]
        }
    };

};