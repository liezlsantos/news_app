angular.module('newsApp.services', [])

.factory('News', function($resource) {
    return $resource('api/news.php/:id', {id: '@_id'});
})

.factory('Comment', function($resource) {
    return $resource('api/comments.php/:id', {id: '@_id'})
})

.service('popupService', function($window) {
    this.showPopup = function(message){
        return $window.confirm(message);
    };
})

.service('alertService', function($timeout, $rootScope) {
    this.showAlert = function(message, isError) {
        $rootScope.alert = {
            show: true,
            message: message,
            isError: isError
        };
        $timeout(function () {
            $rootScope.alert.show = false;
        }, 5000);
    };
});
