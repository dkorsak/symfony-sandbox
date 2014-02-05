# This is a basic VCL configuration file for Symfony 2 Sandbox varnish.

# Default backend definition.  Set this to point to your content
# server.

acl purge {
    "127.0.0.1"; #localhost for dev purposes
}
 
backend default {
    .host = "localhost";
    .port = "80";
}
sub vcl_recv {
    set req.backend = default;
    set req.http.Surrogate-Capability = "abc=ESI/1.0";

    if (req.http.X-Forwarded-Proto == "https" ) {
        set req.http.X-Forwarded-Port = "443";
    } else {
        set req.http.X-Forwarded-Port = "80";
    }

    /*
    Varnish default behaviour doesn't support PURGE.
    Match the PURGE request and immediately do a cache lookup,
    otherwise Varnish will directly pipe the request to the backend
    and bypass the cache
    */
    if (req.request == "PURGE") {
        if (!client.ip ~ purge) {
            error 405 "Not allowed.";
        }
        ban("obj.http.X-Purge-Host ~ " + req.http.X-Purge-Host + " && obj.http.X-Purge-URL ~ " + req.http.X-Purge-Regex + " && obj.http.Content-Type ~ " + req.http.X-Purge-Content-Type);
        error 200 "Content purged from cache";
    }

    if (req.request != "GET" &&
      req.request != "HEAD" &&
      req.request != "PUT" &&
      req.request != "POST" &&
      req.request != "TRACE" &&
      req.request != "OPTIONS" &&
      req.request != "DELETE") {
        /* Non-RFC2616 or CONNECT which is weird. */
        return (pipe);
    }
    if (req.request != "GET" && req.request != "HEAD") {
        /* We only deal with GET and HEAD by default */
        return (pass);
    }
    
    if (req.url ~ "^/app_dev.php" ) {
        return (pass);
    }
    if (req.url ~ "^/uploads/cache" ) {
        return (pass);
    }

    if (req.url ~ "\.(js|css|png|gif|jpg)$") {
       unset req.http.cookie;
       return (lookup);
    }
    
    if (req.http.Cookie) {
        # removes all cookies named __utm? (utma, utmb...) - tracking thing
        set req.http.Cookie = regsuball(req.http.Cookie, "(^|; ) *__utm.=[^;]+;? *", "\1");

        if (req.http.Cookie == "") {
            remove req.http.Cookie;
        }
    }

    # Don't cache admin area
    if (req.url ~ "(^/app.php|^/app_dev.php|^)/admin" || req.url ~ "(^/app.php|^/app_dev.php|^)/(([a-z]{2})/admin)") {
        return (pass);
    }

    # Don't cache security area
    if (req.url ~ "(^/app.php|^/app_dev.php|^)/(([a-z]{2}/|)(login|logout|login_check).*)") {
        return (pass);
    }

    return (lookup);
}

sub vcl_fetch {

    set beresp.http.X-Purge-URL = req.url;
    set beresp.http.X-Purge-Host = req.http.host;

    if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
       unset beresp.http.Surrogate-Control;
       set beresp.do_esi = true;
    }
    if (req.url ~ "\.(js|css|png|gif|jpg)$") {
       unset beresp.http.set-cookie;
       set beresp.ttl = 24h;
    }
    if (beresp.http.Cache-Control ~ "(private|no-cache|no-store)") {
        set beresp.ttl = 0s;
    }
}

sub vcl_deliver {
    if (resp.http.X-Cache-Debug || client.ip ~ purge) {
        if (obj.hits > 0) {
            set resp.http.X-Cache = "HIT";
            set resp.http.X-Cache-Hits = obj.hits;
        } else {
            set resp.http.X-Cache = "MISS";
        }
        set resp.http.X-Cache-Expires = resp.http.Expires;
    } else {
        # remove Varnish/proxy header
        remove resp.http.X-Varnish;
        remove resp.http.Via;
        remove resp.http.X-Purge-URL;
        remove resp.http.X-Purge-Host;
    }
}
 
# sub vcl_pipe {
#     # Note that only the first request to the backend will have
#     # X-Forwarded-For set.  If you use X-Forwarded-For and want to
#     # have it set for all requests, make sure to have:
#     # set bereq.http.connection = "close";
#     # here.  It is not set by default as it might break some broken web
#     # applications, like IIS with NTLM authentication.
#     return (pipe);
# }
# 
# sub vcl_pass {
#     return (pass);
# }
# 
# sub vcl_hash {
#     hash_data(req.url);
#     if (req.http.host) {
#         hash_data(req.http.host);
#     } else {
#         hash_data(server.ip);
#     }
#     return (hash);
# }
# 
# sub vcl_hit {
#     return (deliver);
# }
# 
# sub vcl_miss {
#     return (fetch);
# }
# 
# sub vcl_error {
#     set obj.http.Content-Type = "text/html; charset=utf-8";
#     set obj.http.Retry-After = "5";
#     synthetic {"
# <?xml version="1.0" encoding="utf-8"?>
# <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
#  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
# <html>
#   <head>
#     <title>"} + obj.status + " " + obj.response + {"</title>
#   </head>
#   <body>
#     <h1>Error "} + obj.status + " " + obj.response + {"</h1>
#     <p>"} + obj.response + {"</p>
#     <h3>Guru Meditation:</h3>
#     <p>XID: "} + req.xid + {"</p>
#     <hr>
#     <p>Varnish cache server</p>
#   </body>
# </html>
# "};
#     return (deliver);
# }
# 
# sub vcl_init {
#   return (ok);
# }
# 
# sub vcl_fini {
#   return (ok);
# }