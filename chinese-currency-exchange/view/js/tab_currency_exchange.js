(function (r) {
    "use strict";
    var a = function (t) {
        this.element = r(t);
    };
    function e(i) {
        return this.each(function () {
            var t = r(this),
                e = t.data("bs.tab");
            e || t.data("bs.tab", (e = new a(this))), "string" == typeof i && e[i]();
        });
    }
    (a.VERSION = "3.4.1"),
        (a.TRANSITION_DURATION = 150),
        (a.prototype.show = function () {
            var t = this.element,
                e = t.closest("ul:not(.dropdown-menu)"),
                i = t.data("target");
            if ((i || (i = (i = t.attr("href")) && i.replace(/.*(?=#[^\s]*$)/, "")), !t.parent("li").hasClass("active"))) {
                var o = e.find(".active:last a"),
                    n = r.Event("hide.bs.tab", { relatedTarget: t[0] }),
                    s = r.Event("show.bs.tab", { relatedTarget: o[0] });
                if ((o.trigger(n), t.trigger(s), !s.isDefaultPrevented() && !n.isDefaultPrevented())) {
                    var a = r(document).find(i);
                    this.activate(t.closest("li"), e),
                        this.activate(a, a.parent(), function () {
                            o.trigger({ type: "hidden.bs.tab", relatedTarget: t[0] }), t.trigger({ type: "shown.bs.tab", relatedTarget: o[0] });
                        });
                }
            }
        }),
        (a.prototype.activate = function (t, e, i) {
            var o = e.find("> .active"),
                n = i && r.support.transition && ((o.length && o.hasClass("fade")) || !!e.find("> .fade").length);
            function s() {
                o.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1),
                    t.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0),
                    n ? (t[0].offsetWidth, t.addClass("in")) : t.removeClass("fade"),
                    t.parent(".dropdown-menu").length && t.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0),
                    i && i();
            }
            o.length && n ? o.one("bsTransitionEnd", s).emulateTransitionEnd(a.TRANSITION_DURATION) : s(), o.removeClass("in");
        });
    var t = r.fn.tab;
    (r.fn.tab = e),
        (r.fn.tab.Constructor = a),
        (r.fn.tab.noConflict = function () {
            return (r.fn.tab = t), this;
        });
    var i = function (t) {
        t.preventDefault(), e.call(r(this), "show");
    };
    r(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', i).on("click.bs.tab.data-api", '[data-toggle="pill"]', i);
})(jQuery)