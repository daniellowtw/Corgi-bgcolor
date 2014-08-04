angular.module('corgiApp', [])
	.controller('mainCtrl', function($scope, $http, $location) {
		console.log("Hello, if you like what I did here, consider buying me drinks :)?");
		if ($location.hash()) {
			$scope.name = $location.hash();

		} else {
			$scope.name = '';
		};
		$scope.$watch('name', function(e) {
			$('body').attr('bgcolor', e);
			$scope.colour = $('body').css('background-color');
		})

		$http.get('corgi.php?entries').success(function(data) {
			$scope.entries = data;
		})

		$scope.setName = function(x) {
			$scope.name = x;
		}
		$scope.save = function() {
			var params = {
				name: $scope.name,
				colour: $scope.colour
			}
			var config = {
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				}
			}
			$http.post('corgi.php', params, config).success(function(data) {
				$scope.entries = data;
				console.log(data);
			})
		}
	})