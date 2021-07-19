(function () {
	setInterval(function(){
		checkconnectivity();		
	},3000);
})();

function checkconnectivity(){
	var ifConnected = window.navigator.onLine;
if (ifConnected) {
  console.log('connected');
} else {
  console.log('not connected');
}

}