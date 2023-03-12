!function (e) {
    var o, t, n;
    "function" == typeof define && define.amd && (define(e), o = !0), "object" == typeof exports && (module.exports = e(), o = !0), o || (t = window.Cookies, (n = window.Cookies = e()).noConflict = function () {
        return window.Cookies = t, n
    })
}(function () {
    function a() {
        for (var e = 0, o = {}; e < arguments.length; e++) {
            var t = arguments[e];
            for (var n in t) o[n] = t[n]
        }
        return o
    }

    function d(e) {
        return e.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent)
    }

    return function e(s) {
        function r() {
        }

        function t(e, o, t) {
            if ("undefined" != typeof document) {
                "number" == typeof (t = a({path: "/"}, r.defaults, t)).expires && (t.expires = new Date(+new Date + 864e5 * t.expires)), t.expires = t.expires ? t.expires.toUTCString() : "";
                try {
                    var n = JSON.stringify(o);
                    /^[\{\[]/.test(n) && (o = n)
                } catch (e) {
                }
                o = s.write ? s.write(o, e) : encodeURIComponent(String(o)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent), e = encodeURIComponent(String(e)).replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent).replace(/[\(\)]/g, escape);
                var i = "";
                for (var c in t) t[c] && (i += "; " + c, !0 !== t[c] && (i += "=" + t[c].split(";")[0]));
                return document.cookie = e + "=" + o + i
            }
        }

        function o(e, o) {
            if ("undefined" != typeof document) {
                for (var t = {}, n = document.cookie ? document.cookie.split("; ") : [], i = 0; i < n.length; i++) {
                    var c = n[i].split("="), r = c.slice(1).join("=");
                    o || '"' !== r.charAt(0) || (r = r.slice(1, -1));
                    try {
                        var a = d(c[0]), r = (s.read || s)(r, a) || d(r);
                        if (o) try {
                            r = JSON.parse(r)
                        } catch (e) {
                        }
                        if (t[a] = r, e === a) break
                    } catch (e) {
                    }
                }
                return e ? t[e] : t
            }
        }

        return r.set = t, r.get = function (e) {
            return o(e, !1)
        }, r.getJSON = function (e) {
            return o(e, !0)
        }, r.remove = function (e, o) {
            t(e, "", a(o, {expires: -1}))
        }, r.defaults = {}, r.withConverter = e, r
    }(function () {
    })
}), window["gdpr-cookie-notice-templates"] = {}, window["gdpr-cookie-notice-templates"]["bar.html"] = '<div class="gdpr-cookie-notice">\n  <button type="button" class="gdpr-cookie-close close" aria-label="Close">\n    <span aria-hidden="true">×</span>\n  </button>\n  <p class="gdpr-cookie-notice-description">{description} <a href="#" class="gdpr-cookie-notice-nav-item-settings">{settings}</a>\n  </p>\n  <nav class="gdpr-cookie-notice-nav">\n    <a href="#" class="gdpr-cookie-notice-nav-item gdpr-cookie-notice-nav-item-btn gdpr-cookie-notice-nav-item-acceptneccessary">{accept_necessary}</a>\n    <a href="#" class="gdpr-cookie-notice-nav-item gdpr-cookie-notice-nav-item-btn gdpr-cookie-notice-nav-item-accept">{accept}</a>\n  </nav>\n</div>\n', window["gdpr-cookie-notice-templates"]["category.html"] = '<li class="gdpr-cookie-notice-modal-cookie">\n  <div class="gdpr-cookie-notice-modal-cookie-row">\n    <h3 class="gdpr-cookie-notice-modal-cookie-title">{title}</h3>\n    <input type="checkbox" name="gdpr-cookie-notice-{prefix}"  id="gdpr-cookie-notice-{prefix}" class="gdpr-cookie-notice-modal-cookie-input">\n    <label class="gdpr-cookie-notice-modal-cookie-input-switch" for="gdpr-cookie-notice-{prefix}"></label>\n  </div>\n  <p class="gdpr-cookie-notice-modal-cookie-info">{desc}</p>\n</li>\n', window["gdpr-cookie-notice-templates"]["modal.html"] = '<div class="gdpr-cookie-notice-modal">\n  <div class="gdpr-cookie-notice-modal-content">\n    <div class="gdpr-cookie-notice-modal-header">\n      <h2 class="gdpr-cookie-notice-modal-title">{settings}</h2>\n      <button type="button" class="gdpr-cookie-notice-modal-close"></button>\n    </div>\n    <ul class="gdpr-cookie-notice-modal-cookies"></ul>\n    <div class="gdpr-cookie-notice-modal-footer">\n      <a href="#" class="gdpr-cookie-notice-modal-footer-item gdpr-cookie-notice-modal-footer-item-statement">{statement}</a>\n      <a href="#" class="gdpr-cookie-notice-modal-footer-item gdpr-cookie-notice-modal-footer-item-save gdpr-cookie-notice-modal-footer-item-btn"><span>{save}</span></a>\n    </div>\n  </div>\n</div>\n';
var gdprCookieNoticeLocales = {};

function gdprCookieNotice(c) {
    var n = "gdprcookienotice", r = "gdpr-cookie-notice", a = window[r + "-templates"], i = Cookies.noConflict(),
        s = !1, o = !1, d = !1, l = ["performance", "analytics", "marketing"];
    c.locale || (c.locale = "de"), c.timeout || (c.timeout = 20), c.domain || (c.domain = null), c.expiration || (c.expiration = 30), void 0 === gdprCookieNoticeLocales[c.locale] && (c.locale = "en");
    var u = i.getJSON(n), m = new CustomEvent("gdprCookiesEnabled", {detail: u});

    function p(e) {
        for (var o = 0; o < l.length; o++) if (c[l[o]] && !e[l[o]]) for (var t = 0; t < c[l[o]].length; t++) i.remove(c[l[o]][t]), 0;
        document.documentElement.classList.remove(r + "-loaded")
    }

    function k(e) {
        var o = {date: new Date, necessary: !0, performance: !1, analytics: !1, marketing: !1};
        if (e) for (var t = 0; t < l.length; t++) o[l[t]] = document.getElementById(r + "-cookie_" + l[t]).checked;
        i.set(n, o, {
            expires: c.expiration,
            domain: c.domain
        }), p(o), m = new CustomEvent("gdprCookiesEnabled", {detail: o}), document.dispatchEvent(m), h()
    }

    function f(e, t) {
        var o = a[e], n = gdprCookieNoticeLocales[c.locale];
        if (t ? t += "_" : t = "", !("string" == typeof o && n instanceof Object)) return !1;
        for (var i in n) return o.replace(/({([^}]+)})/g, function (e) {
            var o = e.replace(/{/, "").replace(/}/, "");
            return "prefix" == o ? t.slice(0, -1) : n[o] ? n[o] : n[t + o] ? n[t + o] : e
        })
    }

    function e() {
        var e, o, t, n;
        s || (e = f("modal.html"), document.body.insertAdjacentHTML("beforeend", e), (o = document.querySelector("." + r + "-modal-cookies")).innerHTML += f("category.html", "cookie_essential"), t = document.querySelector("." + r + "-modal-cookie-input"), (n = document.querySelector("." + r + "-modal-cookie-input-switch")).innerHTML = gdprCookieNoticeLocales[c.locale].always_on, n.classList.add(r + "-modal-cookie-state"), n.classList.remove(r + "-modal-cookie-input-switch"), t.remove(), c.performance && (o.innerHTML += f("category.html", "cookie_performance")), c.analytics && (o.innerHTML += f("category.html", "cookie_analytics")), c.marketing && (o.innerHTML += f("category.html", "cookie_marketing")), function () {
            var e = document.querySelectorAll("." + r + "-modal-close")[0],
                o = document.querySelectorAll("." + r + "-modal-footer-item-statement")[0],
                t = document.querySelectorAll("." + r + "-modal-cookie-title"),
                n = document.querySelectorAll("." + r + "-modal-footer-item-save")[0];
            e.addEventListener("click", function () {
                return h(), !1
            }), o.addEventListener("click", function (e) {
                e.preventDefault(), window.location.href = c.statement
            });
            for (var i = 0; i < t.length; i++) t[i].addEventListener("click", function () {
                return this.parentNode.parentNode.classList.toggle("open"), !1
            });
            n.addEventListener("click", function (e) {
                e.preventDefault(), n.classList.add("saved"), setTimeout(function () {
                    n.classList.remove("saved")
                }, 1e3), k(!0)
            })
        }(), u && (document.getElementById(r + "-cookie_performance").checked = u.performance, document.getElementById(r + "-cookie_analytics").checked = u.analytics, document.getElementById(r + "-cookie_marketing").checked = u.marketing), s = !0)
    }

    function g() {
        e(), document.documentElement.classList.add(r + "-show-modal")
    }

    function h() {
        document.documentElement.classList.remove(r + "-show-modal")
    }

    u ? (p(u), document.dispatchEvent(m)) : (function () {
        if (o) return;
        var e = f("bar.html");
        document.body.insertAdjacentHTML("beforeend", e), function () {
            var e = document.querySelectorAll("." + r + "-nav-item-settings")[0],
                o = document.querySelectorAll("." + r + "-nav-item-accept")[0],
                t = document.querySelectorAll("." + r + "-nav-item-acceptneccessary")[0],
                n = document.querySelectorAll(".gdpr-cookie-close")[0];
            e.addEventListener("click", function (e) {
                e.preventDefault(), g()
            }), o.addEventListener("click", function (e) {
                e.preventDefault(), k()
            }), t.addEventListener("click", function (e) {
                e.preventDefault(), k()
            }), n.addEventListener("click", function (e) {
                e.preventDefault(), k()
            })
        }(), o = !0
    }(), setTimeout(function () {
        document.documentElement.classList.add(r + "-loaded")
    }, c.timeout), c.implicit && window.addEventListener("scroll", function e() {
        var o, t, n, i;
        o = window.innerHeight || (document.documentElement || document.body).clientHeight, t = function () {
            var e = document;
            return Math.max(e.body.scrollHeight, e.documentElement.scrollHeight, e.body.offsetHeight, e.documentElement.offsetHeight, e.body.clientHeight, e.documentElement.clientHeight)
        }(), n = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop, i = t - o, 25 < Math.floor(n / i * 100) && !d && (d = !0) && (k(), window.removeEventListener("click", e))
    }));
    var t = document.querySelectorAll("." + r + "-settings-button");
    if (t) for (var v = 0; v < t.length; v++) t[v].addEventListener("click", function (e) {
        e.preventDefault(), g()
    })
}

gdprCookieNoticeLocales.en = {
    description: "We use cookies to offer you a better browsing experienc. Read about how we use cookies and how you can control them by clicking Cookie Settings.",
    settings: "Cookie settings",
    accept: "Accept all cookies",
    statement: "Our cookie statement",
    save: "Save settings",
    always_on: "Always on",
    cookie_essential_title: "Essential website cookies",
    cookie_essential_desc: "Necessary cookies help make a website usable by enabling basic functions like page navigation and access to secure areas of the website. The website cannot function properly without these cookies.",
    cookie_performance_title: "Performance cookies",
    cookie_performance_desc: "These cookies are used to enhance the performance and functionality of our websites but are non-essential to their use. For example it stores your preferred language or the region that you are in.",
    cookie_analytics_title: "Analytics cookies",
    cookie_analytics_desc: "We use analytics cookies to help us measure how users interact with website content, which helps us customize our websites and application for you in order to enhance your experience.",
    cookie_marketing_title: "Marketing cookies",
    cookie_marketing_desc: "These cookies are used to make advertising messages more relevant to you and your interests. The intention is to display ads that are relevant and engaging for the individual user and thereby more valuable for publishers and third party advertisers."
}, gdprCookieNoticeLocales.de = {
    description: "roadbikelife verwendet Cookies, um ein besseres Surferlebnis zu bieten. Mehr Infos erhältst du in den ",
    settings: "Cookie-Einstellungen",
    accept: "alle Cookies akzeptieren",
    accept_necessary: "OK",
    statement: "Unsere Datenschutzerklärung",
    save: "Einstellungen speichern",
    always_on: "Immer an",
    cookie_essential_title: "Notwendige Website cookies",
    cookie_essential_desc: "Notwendige Cookies helfen, eine Website nutzbar zu machen, indem Grundfunktionen aktiviert werden wie Seitennavigation und Zugriff auf sichere Bereiche der Website. Die Website kann ohne diese Cookies nicht richtig funktionieren",
    cookie_performance_title: "Leistungs-Cookies",
    cookie_performance_desc: "Diese Cookies werden verwendet, um die Leistung und Funktionalität unserer Websites zu verbessern, sind jedoch für deren Verwendung nicht unbedingt erforderlich. Beispielsweise speichert es Ihre bevorzugte Sprache oder die Region, in der Sie sich befinden. ",
    cookie_analytics_title: "Analyse-Cookies",
    cookie_analytics_desc: "Mithilfe von Analyse-Cookies können wir messen, wie Benutzer mit Website-Inhalten interagieren. Auf diese Weise können wir unsere Websites und Anwendungen für Sie anpassen, um Ihre Erfahrung zu verbessern. ",
    cookie_marketing_title: "Marketing-Cookies",
    cookie_marketing_desc: "Diese Cookies werden verwendet, um Werbebotschaften für Sie und Ihre Interessen relevanter zu machen. Ziel ist es, Anzeigen zu schalten, die für den einzelnen Nutzer relevant und ansprechend sind und dadurch für Publisher und Drittanbieter einen höheren Stellenwert haben."
};
//# sourceMappingURL=script.js.map
