app.controller('laporansiswaCtrl', function($scope, Data, toaster) {
    var tableStateRef;
    var control_link = "m_siswa";
    $scope.formTitle = '';
    $scope.displayed = [];

    $scope.is_view = false;
    $scope.is_tampilkan = false;
  

    /*step 10.A*/
   
   
    $scope.view = function(form) {
      
        $scope.is_view = false;
        $scope.is_tampilkan = true;

        // API View
        
        Data.get(control_link + '/viewPrint/').then(function(response) {
            $scope.form = response.data.form;
        });
    };

      /*step 10.A*/

      /*Step 11*/

    $scope.export = function() {
      
        window.location = 'api/m_siswa/export';
    
    };
    
      /*Step 11*/
   
   
    
  
})