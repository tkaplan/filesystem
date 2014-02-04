var generateOutputCtrl = function($scope, $http){
	$scope.output = {
		factoryId: null,
		limit: null,
		number: null
	};

	$scope.generate = function(){
		$scope.output.number = parseInt($scope.output.number);

		if($scope.output.number < 1 || $scope.output.number > $scope.output.limit )
		{
			alert("Could not generate more outputs than the limit");
			return;
		}

		$http.post(_path_generate_output, $scope.output).
			success(function(data, status, headers, config){
				var children = jQuery('div#jstree').jstree(true).get_children_dom($scope.output.factoryId);
				for(var i = 0; i < children.length; i ++)
				{
					jQuery('div#jstree').jstree(true).delete_node(children[i]);
				}

				// Add new factory, this conveniently doesn't have any children
				jQuery('div#jstree').jstree(true).create_node(newFactory.rootId, newFactory,"last");

				for(var i = 0; i < data.output.length; i ++)
				{
					var node = {
						"id": Math.random()*100000000,
						"text": data.output[i],
						"type": "OUTPUT"
					};
					jQuery('div#jstree').jstree(true).create_node($scope.output.factoryId,node,"last");
				}
				jQuery('div#generateOutput').modal('hide');

				// Tell everyone else to update
				socket.emit('tree update');
			}).error(function(data, status, headers, config){
				alert("Couldn't generate output");
				console.log(data);
			});
	};
};

app.controller('generateOutputCtrl',
	['$scope','$http',generateOutputCtrl]);