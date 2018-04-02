angular.module('newsApp',
    ['ui.router', 'ngResource', 'newsApp.controllers', 'newsApp.services', 'newsApp.filters']
);

angular.module('newsApp').config(function($stateProvider, $httpProvider){
    $stateProvider.state('news', {
        url:'/news',
        templateUrl:'templates/news.html',
        controller:'NewsListController'
    }).state('viewNews', {
        url:'/news/view/:id',
        templateUrl:'templates/view_news.html',
        controller:'ViewNewsController'
    }).state('createNews', {
        url:'/news/add',
        templateUrl:'templates/add_news.html',
        controller:'CreateNewsController'
    });
}).run(function($state){
    $state.go('news');
});
