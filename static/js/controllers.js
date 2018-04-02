angular.module('newsApp.controllers', [])

.controller('NewsListController', function($scope, $state, $window, News) {
    News.query(function(response) {
        newsList = response;
        $scope.currentPage = 0;
        $scope.pagedItems = [];
        var itemsPerPage = 5;
        for (var i = 0; i < newsList.length; i++) {
            if (i % itemsPerPage === 0) {
                $scope.pagedItems[Math.floor(i/itemsPerPage)] = [newsList[i]];
            } else {
                $scope.pagedItems[Math.floor(i/itemsPerPage)].push(newsList[i]);
            }
        }
    }, function(response) {
        $scope.errorMessage = response.data.message;
    });

    $scope.range = function (start, end) {
        var ret = [];
        if (!end) {
            end = start;
            start = 0;
        }
        for (var i = start; i < end; i++) {
            ret.push(i);
        }
        return ret;
    };

    $scope.prevPage = function () {
        if ($scope.currentPage > 0) {
            $scope.currentPage--;
        }
    };

    $scope.nextPage = function () {
        if ($scope.currentPage < $scope.pagedItems.length - 1) {
            $scope.currentPage++;
        }
    };

    $scope.setPage = function () {
        $scope.currentPage = this.n;
        $window.scrollTo(0, 0);
    };
})

.controller('ViewNewsController', function(
    $rootScope, $scope, $state, $stateParams, popupService, alertService, News, Comment) {

    getNews($stateParams.id);

    $scope.addComment = function(comment) {
        $scope.comment.news_id = $scope.news.id;
        $scope.comment.$save(function() {
            $scope.news = getNews($scope.news.id);
        }, function(response) {
            alertService.showAlert(response.data.message, true);
        });
    };

    $scope.deleteComment = function(comment_id){
        if (popupService.showPopup('Do you really want to remove this comment?')) {
            comment = new Comment();
            comment.id = comment_id;
            Comment.delete({id: comment_id}, function() {
                $scope.news = getNews($scope.news.id);
            }, function(response) {
                alertService.showAlert(response.data.message, true);
            });
        }
    };

    $scope.deleteNews = function(news){
        if (popupService.showPopup('Do you really want to delete this news?')) {
            News.delete({id: news.id}, function() {
                $state.go('news');
                alertService.showAlert('News successfully deleted.', false);
            }, function(response) {
                alertService.showAlert(response.data.message, true);
            });
        }
    };

    function getNews(newsId) {
        News.get({id: newsId}, function(response) {
            $scope.news = response;
            $scope.comment = new Comment();
        }, function(response) {
            $state.go('news');
            alertService.showAlert(response.data.message, true);
        });
    }
})

.controller('CreateNewsController',function($scope, $state, alertService, News) {
    $scope.news = new News();
    $scope.addNews = function() {
        $scope.news.$save(function() {
            $state.go('news');
            alertService.showAlert('News successfully posted.', false);
        }, function(response) {
            alertService.showAlert(response.data.message, true);
        });
    }
});
