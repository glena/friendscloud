angular.module('monitor', ['restangular'])
    .config(function(RestangularProvider) {
        RestangularProvider.setBaseUrl('/twitter');
    })
    .controller('followersList', function($scope, Restangular) {
        $scope.followers = [];
        $scope.page = [];
        $scope.currentPage = 0;
        $scope.pagesCount = 0;
        $scope.limit = 24;

        var followers = Restangular.all('followers');

        Restangular.all('followers').getList()
        .then(function(followers) {
            $scope.followers = followers;
            $scope.pagesCount = Math.ceil($scope.followers.length / $scope.limit);
            loadPage();
            loadPageStatus();
        });

        function loadPage()
        {
            $scope.page = $scope.followers.slice($scope.limit * $scope.currentPage, $scope.limit * ($scope.currentPage+1));
        }

        function loadPageStatus()
        {
            $scope.hasNextPage = ($scope.currentPage + 1 < $scope.pagesCount);
            $scope.hasPrevPage = ($scope.currentPage - 1 > 0);

        }

        $scope.nextPage = function() {
            if ($scope.currentPage + 1 < $scope.pagesCount)
            {
                $scope.currentPage++;
                loadPage();
                loadPageStatus();
            }
        };

        $scope.prevPage = function() {
            if ($scope.currentPage - 1 > 0)
            {
                $scope.currentPage--;
                loadPage();
                loadPageStatus();
            }
        };

        $scope.fixImageSize = function(url) {
            return url.replace('_normal','_bigger');
        };

    });