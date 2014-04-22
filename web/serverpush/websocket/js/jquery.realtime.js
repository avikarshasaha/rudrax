
    if (!window.console) {
            window.console = {log: $.noop, error: $.noop};
    }
    if (!window.WebSocket) {
            window.WebSocket = undefined;
    }

    var _rt;
    var _rt_queue = [];

    
    var realtimeComm = function(handlerUrl) {
            _rt = this;

            _rt.socketConn = undefined;
            _rt.urlSocket  = 'ws://' + handlerUrl;
            _rt.urlHttp    = parent.location.protocol + '//' + handlerUrl;
            _rt.cursor     = '';
            _rt.callbacks  = {};

            if (typeof window.WebSocket != 'undefined') {
                    _rt.__listenWebSocket('/core/handshake');
            }

            // Cleanup open websocket connections when the document is unloaded.
            $(window).unload(function() {
                    _rt.cleanup();
            });
    };

    realtimeComm.prototype.addListener = function(uri, callback) {
            _rt.__setCallback(uri, callback);

            // Determine if the broswer supports websockets.
            if (typeof window.WebSocket != 'undefined') {
                    // Tell the server the listen at this URI.
                    _rt.send(uri, {});
            } else {
                    // Fail back to long polling.
                    _rt.__listenPolling(uri);
            }
    };

    realtimeComm.prototype.send = function(uri, data, callback) {
            _rt.__setCallback(uri, callback);

            // Inject the URI into the submission.
            data.__URI__ = uri;

            if (typeof _rt.socketConn != 'undefined') {
                    if (_rt.socketConn.readyState != 1) {
                            _rt_queue.push(data);
                    } else {
                            _rt.socketConn.send($.toJSON(data));
                    }
            } else {
                    $.ajax({
                            'url': _rt.urlHttp + uri,
                            'type': 'POST',
                            'dataType': 'jsonp',
                            'jsonpCallback': '_rt.JSONPsend',
                            'data': data,
                            'error': _rt.__handleError
                    });
            }
    };

    realtimeComm.prototype.JSONPsend = function(response) {
            _rt.__onmessage(response, false);
    };

    realtimeComm.prototype.JSONPlisten = function(response) {
            _rt.__onmessage(response, true);
    };

    realtimeComm.prototype.__setCallback = function(uri, callback) {
            if (typeof callback === 'function') {
                    _rt.callbacks[uri] = callback;
            }
    };

    realtimeComm.prototype.__handleError = function(xhr, status, error) {
            console.log('textStatus:', status, 'errorThrown', error);
    };

    realtimeComm.prototype.__listenPolling = function(uri) {
            $.ajax({
                    'url': _rt.urlHttp + uri,
                    'type': 'POST',
                    'dataType': 'jsonp',
                    'jsonpCallback': '_rt.JSONPlisten',
                    'data': {'waterspout_cursor': _rt.cursor},
                    'error': _rt.__handleError
            });
    };

    realtimeComm.prototype.__listenWebSocket = function(uri) {
            if (typeof window.WebSocket != 'undefined') {
                    _rt.socketConn = new WebSocket(_rt.urlSocket + uri);
                    _rt.socketConn.onmessage = function(response) {
                            // Process message received via websocket.
                            _rt.__onmessage(response, true);
                    };
                    _rt.socketConn.onclose = function() {
                            // Re-open the websocket connection.
                            _rt.__listenWebSocket(uri);
                    };
                    _rt.socketConn.onopen = function() {
                            // Process command queue.
                            for (var i = 0, size = _rt_queue.length; i < size; i++) {
                                    _rt.socketConn.send($.toJSON(_rt_queue[i]));
                            }

                            // Clear the command queue.
                            _rt_queue = [];
                    };
            }
    };

    realtimeComm.prototype.__onmessage = function(response, pollingRequest) {
            if (response) {
                    var data;
                    if (typeof _rt.socketConn != 'undefined' && response.data) {
                            data = $.evalJSON(response.data);
                    } else if (response.responseText) {
                            data = $.evalJSON(response.responseText);
                    } else {
                            data = response;
                    }

                    if (data) {
                            if (data.cursor) {
                                    _rt.cursor = data.cursor;
                            }
                            var callbackFunc = _rt.callbacks[data.__URI__];
                            if (typeof callbackFunc != 'undefined') {
                                    callbackFunc(data);

                                    if (pollingRequest === true && typeof _rt.socketConn == 'undefined') {
                                            _rt.__listenPolling(data.__URI__);
                                    }
                            }
                    }
            }
    };

    realtimeComm.prototype.cleanup = function() {
            if (typeof _rt.socketConn != 'undefined') {
                    // Don't try to reopen the socket connection when cleaning up.
                    _rt.socketConn.onclose = function () {};
                    _rt.socketConn.close();
            }
    };
