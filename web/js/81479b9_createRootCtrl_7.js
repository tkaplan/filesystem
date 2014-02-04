var createRootCtrl = function($scope, $http){

	$scope.root = {
		id: null,
		text: null
	};

	$scope.create = function(){
		$http.post(_path_create_root, $scope.root).
			success(function(data, status, headers, config){
				var root = {
					"id": data.id,
					"type": "ROOT",
					"text": $scope.root.text
				};

				jQuery('div#jstree').jstree(true).create_node('#',root,"last",function(){
					jQuery('div#createRoot').modal('hide');

					// Tell everyone else to update
					socket.emit('tree update');
				});
			}).error(function(data, status, headers, config){
				alert("Couldn't create root node");
				console.log(data);
			});
	};
};

app.controller('createRootCtrl',
	['$scope','$http',createRootCtrl]);