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
        return defineProperty(key, value);
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

    voyager.url = function(uri, param = null) 
    {
        var builder = voyager.https ? 'https' : 'http';
            builder += '://' + voyager.base_url + '/';

        if(startWith(uri, '/') && uri !== '/') 
        {
            uri = uri.substring(1, uri.length);
        }

        if(endWith(uri, '/') && uri !== '/') 
        {
            uri = uri.substring(0, uri.length - 1);
        }

        builder += uri;

        if(!isNull(param))
        {
            var keys = Object.keys(param);

            if(keys.length !== 0) 
            {
                builder += '?';
                
                voyager.each(keys, function(key) 
                {
                    builder += key + '=' + encodeURIComponent(param[key]) + '&';
                });

                builder = builder.substring(0, builder.length - 1);
            }
        }

        return builder;
    };

    voyager.lang = function(id, lang = null, replace = null) {
        var translations = voyager.translations,
            lang = isNull(lang) ? voyager.locale : 'en',
            name = id.split('@')[0].replace(/\./g, '_'),
            response = id,
            location = id.split('@')[1] || null,
            locales = {},
            that = this;

        if(isNull(location))
        {
            that.each(Object.keys(translations), function(key) {
                that.each(Object.keys(translations[key]), function(item) {
                    locales[item] = translations[key][item];
                });
            });
            
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