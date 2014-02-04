var newFactoryCtrl = function($scope, $http){
	$scope.factory = {
		id: null,
		rootId: null,
		text: null,
		lowerBound: null,
		upperBound: null
	};

	$scope.create = function(){

		var lb = $scope.factory.lowerBound;
		var ub = $scope.factory.upperBound;

		if($scope.factory.text == null)
		{
			alert("Please fill in a factory name.");
			return;
		}

		if(!isNaN(ub) && !isNaN(lb))
		{
			ub = parseInt(ub);
			lb = parseInt(lb);

			$scope.factory.lowerBound = lb;
			$scope.factory.upperBound = ub;

			if(ub >= lb)
			{
				$http.post(_path_create_factory, $scope.factory).
							success(function(data, status, headers, config){
								var factory = {
									"id": data.id,
									"text": $scope.factory.text + $scope.factory.lowerBound + " - " + $scope.factory.upperBound,
									"type": "FACTORY",
									"lowerBound": $scope.factory.lowerBound,
									"upperBound": $scope.factory.upperBound
								};

								jQuery('div#jstree').jstree(true).create_node($scope.factory.rootId, factory,"last",function(){
									jQuery('div#newFactory').modal('hide');

									// Tell everyone else to update
									socket.emit('tree update');
								});
							}).error(function(data, status, headers, config){
								alert("Could not create factory.");
								console.log(data);
							});
			}
			else
			{
				alert("Could not create factory. Your upper bound must be greater than your lower bound.");
			}
		}
		else
		{
			alert("Could not create factory. Your bounds need to be numbers.");
		}
	};
};

app.controller('newFactoryCtrl',
	['$scope','$http',newFactoryCtrl]);