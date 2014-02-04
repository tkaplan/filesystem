var socket = io.connect('http://localhost:3000');

socket.on('tree update', function (data) {
	console.log(data);
	updateTree();
});