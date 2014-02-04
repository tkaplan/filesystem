var socket = io.connect('http://filesystem.tks-universe.com:3000');

socket.on('tree update', function (data) {
	console.log(data);
	updateTree();
});