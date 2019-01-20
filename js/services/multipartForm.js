myApp.service('multipartForm',['$http',function($http){
	
	this.post = function(uploadUrl, data){
		var fd = new FormData();
		for(var key in data)
			fd.append(key, data[key]);
		$http.post('http://localhost:3309' , fd {
			transformRequest: angular.identity,
			header: {'Content-Type':undefined}
		})
	}
}])