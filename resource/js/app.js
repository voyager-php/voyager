(function(global, factory) 
{

    'use strict';

    if(typeof global.voyager === 'undefined' && typeof app !== 'undefined')
    {
        factory(global, app);
    }

})(typeof window === 'undefined' ? window : this, function(global, app) 
{

    var voyager = function() {},
    
        isNull = function(val) 
        {
            return val === null;
        },

        isDefined = function(val) 
        {
            return typeof val !== 'undefined';
        },

        objectHasKey = function(obj, key) 
        {
            return obj.hasOwnProperty(key) || isDefined(obj[key]);
        },

        defineProperty = function(key, value) 
        {
            Object.defineProperty(voyager, key, {
                value: value,
                writable: false,
                enumerable: true,
                configurable: false,
            });
        },

        startWith = function(string, char) 
        {
            return string.substring(0, char.length) === char;
        },

        endWith = function(string, char) 
        {
            return string.substring(string.length - char.length, string.length) === char;
        };

    voyager.setProperty = function(key, value) 
    {
        if(!objectHasKey(voyager, key))
        {
            defineProperty(key, value);
        }
    };

    voyager.each = function(array, callback) 
    {
        for(i = 0; i <= (array.length - 1); i++) 
        {
            callback(array[i], i);
        }
    };

    voyager.extend = function(src, dest) 
    {
        voyager.each(Object.keys(src), function(key) 
        {
            dest[key] = src[key];
        });

        return dest;
    };

    voyager.isAuthenticated = function() 
    {
        return app.authenticated && app.authID.length !== 0;
    };

    defineProperty('authID', app.authID);
    defineProperty('authType', app.authType);
    defineProperty('authUserId', app.authUserId);
    defineProperty('token', app.token);

    voyager.get = function(key) 
    {
        if(objectHasKey(app.get(), key)) 
        {
            return app.get()[key];
        }

        return null;
    };

    voyager.post = function(key) 
    {
        if(objectHasKey(app.post(), key))
        {
            return app.post()[key];
        }

        return null;
    };

    voyager.resource = function(key) 
    {
        if(objectHasKey(app.resource(), key))
        {
            return app.resource()[key];
        }

        return null;
    };

    voyager.url = function(uri, param = null, csrf = false) 
    {
        var url = voyager.base_url;

        if(startWith(uri, '/') && uri !== '/')
        {
            uri = uri.substring(1, uri.length);
        }

        if(endWith(uri, '/') && uri !== '/') 
        {
            uri = uri.substring(0, uri.length - 1);
        }

        if(endWith(url, '/') && uri === '/')
        {
            url = url.substring(0, url.length - 1);
        }

        url += uri;

        if(csrf)
        {
            if(isNull(param))
            {
                param = {};
            }

            param['_token'] = voyager.token;
        }

        if(!isNull(param))
        {
            var keys = Object.keys(param);

            if(keys.length !== 0) 
            {
                url += '?';
                
                voyager.each(keys, function(key) 
                {
                    url += key + '=' + encodeURIComponent(param[key]) + '&';
                });

                url = url.substring(0, url.length - 1);
            }
        }

        return url;
    };

    voyager.lang = function(id, lang = null, replace = null) {
        var translations = voyager.translations,
            lang = isNull(lang) ? voyager.locale : lang,
            backup = voyager.backup_locale,
            name = id.split('@')[0].replace(/\./g, '_'),
            response = id,
            location = id.split('@')[1] || null,
            locales = {},
            that = this;

        that.each(Object.keys(translations), function(key) {
            that.each(Object.keys(translations[key]), function(item) {
                locales[item] = translations[key][item];
            });
        });

        if(isNull(location))
        {
            if(objectHasKey(locales, name))
            {
                var data = locales[name];

                if(objectHasKey(data, lang))
                {
                    response = data[lang];
                }
            }
        }
        else
        {
            if(objectHasKey(translations, location))
            {
                locales = translations[location];

                if(objectHasKey(locales, name))
                {
                    var data = locales[name];

                    if(objectHasKey(data, lang))
                    {
                        response = data[lang];
                    }
                }
            }
        }

        if(response === id)
        {
            if(isNull(location))
            {
                if(objectHasKey(locales, name))
                {
                    var data = locales[name];

                    if(objectHasKey(data, backup))
                    {
                        response = data[backup];
                    }
                }
            }
            else
            {
                if(objectHasKey(translations, location))
                {
                    locales = translations[location];

                    if(objectHasKey(locales, name))
                    {
                        var data = locales[name];

                        if(objectHasKey(data, backup))
                        {
                            response = data[backup];
                        }
                    }
                }
            }
        }

        if(!isNull(replace))
        {
            var string = response;

            this.each(Object.keys(replace), function(keyword) {
                string = string.replace('{' + keyword + '}', replace[keyword]);
            });

            return string;
        }
        else
        {
            return response;
        }
    };

    voyager.redirect = function(uri, param = null)
    {
        window.location.href = voyager.url(uri, param);
    };

    voyager.goHome = function()
    {
        voyager.redirect('/');
    };

    voyager.reload = function() 
    {
        window.location.reload();
    };

    if(!window.voyager)
    {
        window.voyager = voyager;
    }

    return voyager;
});