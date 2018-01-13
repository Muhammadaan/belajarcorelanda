app.controller('laporansiswaCtrl', function($scope, Data, toaster) {
    var tableStateRef;
    var control_link = "l_nilai";
    $scope.formTitle = '';
    $scope.displayed = [];

    $scope.is_view = false;
    $scope.is_tampilkan = false;
  

    /*step 12.A*/
   
   
    $scope.view = function(form) {
      
        $scope.is_view = false;
        $scope.is_tampilkan = true;

        // API View
        
        Data.get(control_link + '/data').then(function(response) {
            $scope.nilai = response.data.nilai;
            $scope.mapel = response.data.mapel;
        });
    };

    /*step 12.A*/
   
   
    
  
})