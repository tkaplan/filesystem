$(function(){
	$('#jstree').jstree({
	  "core" : {
	  	"check_callback" : true,
	  	"data" : [
	  		{
	  			'id' : 'hello',
	  			'text' : 'Fuck you',
	  			'state' : {
	  				'opened' : false,
	  				'selected' : false
	  			},
	  			'children' : [
	  				{
	  					'id' : 'hello5',
	  					'text' : 'fuck you 3',
	  					'state' : {
	  						'opened': false,
	  						'selected' :false
	  					}
	  					,
	  					'children' : [
	  						'hi'
	  					]
	  				}
	  			]
	  		},
	  		{
	  			'text' : 'Root node 2',
	  			'state' : {
	  				'opened' : true,
	  				'selected' : true
	  			},
	  			'children' : [
	  				{ 'text' : 'Child 1'},
	  				'Child 2'
	  			]
	  		}
	  	]
	},
	  "contextmenu" : {
			"items" : {
			  	"suck": {
			  		"label" : "suck"
			  	},
			  	"my": {
			  		"label" : "my"
			  	},
			  	"dick": {
			  		"label" : "dick"
			  	}
			  }
	  },
	  "plugins" : [
	    "contextmenu", "dnd", "search",
	    "state", "types", "wholerow"
	  ]
	});
});