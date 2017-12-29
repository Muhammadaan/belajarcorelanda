app.controller('belajarCtrl', function($scope, Data, toaster) {
    var tableStateRef;
    var control_link = "belajar";
    $scope.formTitle = '';
    $scope.displayed = [];
    $scope.is_viewdetail = false;
    $scope.is_edit = false;
    $scope.is_view = false;
    /** get list data */
    $scope.callServer = function callServer(tableState) {
        tableStateRef = tableState;
        $scope.isLoading = true;
        /** set offset and limit */
        var offset = tableState.pagination.start || 0;
        var limit = tableState.pagination.number || 10;
        var param = {
            offset: offset,
            limit: limit
        };
        /** set sort and order */
        if (tableState.sort.predicate) {
            param['sort'] = tableState.sort.predicate;
            param['order'] = tableState.sort.reverse;
        }
        /** set filter */
        if (tableState.search.predicateObject) {
            param['filter'] = tableState.search.predicateObject;
        }
        Data.get(control_link + '/index', param).then(function(response) {
            $scope.displayed = response.data.list;
            $scope.total = response.data.totalItems;
            tableState.pagination.numberOfPages = Math.ceil(response.data.totalItems / limit);
        });
    };
    /** get roles list */
    /** create */
    $scope.create = function(form) {
        $scope.is_edit = true;
        $scope.is_view = false;
        $scope.is_create = true;
        $scope.formtitle = "Form Tambah Data";
        $scope.form = {};
    };
    /** update */
    $scope.update = function(form) {
        $scope.is_edit = true;
        $scope.is_view = false;
        $scope.formtitle = "Edit Data : ";
       
        
    };
    /** view */
    $scope.view = function(id) {
        // console.log(id);
        $scope.is_viewdetail = false;
        $scope.is_edit = true;
        $scope.is_view = true;
        Data.get(control_link + '/view/' + id).then(function(data) {
            $scope.list = data.data.form;
        })
         $scope.hari_id = id;
        
    };
    $scope.viewdetail = function(id) {
        console.log(id);
          $scope.is_viewdetail = true;
        $scope.is_edit = false;
        $scope.is_view = false;
        Data.get(control_link + '/viewdetail/' + id).then(function(data) {
            $scope.isi = data.data.form.isi;
        })
    };
    /** save action */
    $scope.save = function(form) {
        var url = (form.id > 0) ? '/update' : '/create';
        Data.post(control_link + url, form).then(function(result) {
            if (result.status_code == 200) {
                $scope.is_edit = false;
                $scope.callServer(tableStateRef);
                toaster.pop('success', "Berhasil", "Data berhasil tersimpan");
            } else {
                toaster.pop('error', "Terjadi Kesalahan", setErrorMessage(result.errors));
            }
        });
    };
    /** cancel action */
    $scope.cancel = function() {
        if (!$scope.is_view) {
            $scope.callServer(tableStateRef);
        }
        $scope.is_edit = false;
        $scope.is_view = false;
         $scope.is_viewdetail = false;
    };


    $scope.paham = function(id) {

          var data = {
            hari_id: id,
           
        };
      
       var url = '/paham' ;
        Data.post(control_link + url, data).then(function(result) {
            if (result.status_code == 200) {
                $scope.is_edit = false;
                $scope.callServer(tableStateRef);
                toaster.pop('success', "Mantab", "Kamu Keren");
            } else {
                toaster.pop('error', "Terjadi Kesalahan", setErrorMessage(result.errors));
            }
        });
      
    };

      $scope.mulai = function(id) {

          var data = {
            hari_id: 1,
           
        };
      
       var url = '/mulai' ;
        Data.post(control_link + url, data).then(function(result) {
            if (result.status_code == 200) {
                $scope.is_edit = false;
                $scope.callServer(tableStateRef);
                toaster.pop('success', "Semangat", "Semangat Belajar");
            } else {
                toaster.pop('error', "Terjadi Kesalahan", setErrorMessage(result.errors));
            }
        });
      
    };

     $scope.canceldetail = function() {
       
        $scope.is_edit = true;
        $scope.is_view = false;
        $scope.is_viewdetail = false;
    };
    $scope.delete = function(row) {
        swal({
            title: "Peringatan",
            text: "Anda Akan Menghapus Permanent I",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya,di hapus",
            cancelButtonText: "Tidak",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                Data.delete(control_link + '/delete/' + row.id).then(function(result) {
                    $scope.displayed.splice($scope.displayed.indexOf(row), 1);
                });
                swal("Terhapus", "Data terhapus.", "success");
            } else {
                swal("Membatalkan", "Membatakan menghapus data", "error");
            }
        });
    };
})