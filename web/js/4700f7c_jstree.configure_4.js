jQuery.noConflict();
var jsconfig;

// update tree
function updateTree(){
 	jQuery('div#jstree').jstree("destroy");
 	jQuery.getJSON(_path_get_tree,function(data){
			jsconfig(data);
	});
}

jQuery(function(){

	function getNodeFromE(e){
		return jQuery('#jstree').jstree(true).get_node(e.reference.context.id);
	};

	function getNodeFromId(id){
		return jQuery('#jstree').jstree(true).get_node(id);
	};

	function deleteRest(url,data,success,error){
		jQuery.ajax({
			url: url,
			type: 'POST',
			data: JSON.stringify(data),
			contentTYpe: 'application/json; charset=utf-8',
			dataType: 'json',
			success: success,
			error: error
		})
	}

	jsconfig = function (jsData) {
		jQuery('#jstree').jstree({
		  	"core": {
			  	"check_callback": true,
			  	"data": jsData
			},
		  	"contextmenu": {
				"items": {
				  	"new_factory": {
				  		"label": "New Factory",
				  		"action": function(e) {
				  			var node = getNodeFromE(e);
				  			if(node.original.type !== 'ROOT')
				  			{
				  				alert('You can only create factories for roots');
				  			}
				  			else
				  			{
				  				var scope = angular.element('div#newFactory').scope();
				  				scope.factory.rootId = node.id;
				  				scope.$apply();
				  				jQuery('div#newFactory').modal('show');
				  			}
				  		}
				  	},
				  	"generate_output" : {
				  		"label": "Generate Output",
				  		"action": function(e) {
				  			var node = getNodeFromE(e);
				  			if(node.original.type !== 'FACTORY')
				  			{
				  				alert('You can only generate outputs for factories.');
				  			}
				  			else
				  			{
				  				var scope = angular.element('div#generateOutput').scope();
				  				var range = node.original.upperBound - node.original.lowerBound;

				  				scope.output.factoryId = node.id;
				  				scope.output.limit = range < 16 ? range : 15;

				  				scope.$apply();
				  				jQuery('div#generateOutput').modal('show');
				  			}
				  		}
				  	},
				  	"delete": {
				  		"label": "Delete",
				  		"action": function(e){
				  			var node = getNodeFromE(e), data, success, error;
				  			switch(node.original.type)
				  			{

				  				case 'OUTPUT':
				  					// Need to send factory id and text value
				  					data = {
				  								"factoryId": node.parent, 
				  								"outputText": node.text
				  							};

				  					// Set correct url for delete factory output
				  					url = _path_delete_factory_output;
				  					break;
				  				default:
				  					// Need to get id
				  					data = {
				  						"id": node.id,
				  						"type": node.original.type
				  					};

				  					// Set correct url
				  					url = _path_delete_tree_node;
				  			}

				  			// Ajax delete node
		  					deleteRest(url,data,
		  						function(){
		  							jQuery('div#jstree').jstree(true).delete_node(node);
		  							socket.emit('tree update');
		  						},
		  						function(){
		  							alert('Could not delete node.');
		  						}
		  					);
				  		}
				  	}
				  }
		  },
		  "plugins" : [
		    "contextmenu", "dnd", "search",
		    "state", "types", "wholerow"
		  ]
		});
	}

	jQuery.getJSON(_path_get_tree,function(data){
	  			jsconfig(data);
	  		});
});