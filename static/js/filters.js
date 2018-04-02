(function () {
    'use strict';
    angular.module('newsApp.filters', [])
        .filter('unsafeHtml', function ($sce) {
            return function (html) {
                return $sce.trustAsHtml(html);
            };
        })
        .filter('dateFromString', function($filter) {
            return function (dateString, format) {
                return $filter('date')(new Date(dateString), format);
            }
        });
}());
