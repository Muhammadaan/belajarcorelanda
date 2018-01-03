app.controller('siswaCtrl', function($scope, Data, toaster) {
    var tableStateRef;
    var control_link = "m_siswa";
    $scope.formTitle = '';
    $scope.displayed = [];
    $scope.is_edit = false;
    $scope.is_view = false;
    /*Step 5.B*/
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
            tableState.pagination.numberOfPages = Math.ceil(response.data.totalItems / limit);
        });
    };
    /*Step 5.B*/
    /** create */
    /*Step 5.D*/
    $scope.create = function(form) {
        $scope.is_edit = true;
        $scope.is_view = false;
        $scope.is_create = true;
        $scope.formtitle = "Form Tambah Data";
        $scope.form = {};
        $scope.riwayat_sekolah = [{
            riwayat_sekolah: ''
        }];
    };
    /*Step 5.D*/
    /** update */
    /*Step 6.A*/
    $scope.update = function(form) {
        $scope.is_edit = true;
        $scope.is_view = false;
        $scope.formtitle = "Edit Data : " + form.nama;
        $scope.form = form;
    };
    /*Step 6.A*/
    /** view */
    // $scope.view = function(form) {
    //     $scope.is_edit = true;
    //     $scope.is_view = true;
    //     $scope.formtitle = "Lihat Data : " + form.username;
    //     $scope.form = form;
    // };
    /** save action */
    /*Step 5.G*/
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
    /*Step 5.G*/
    /*Step 5.F*/
    /** cancel action */
    $scope.cancel = function() {
        if (!$scope.is_view) {
            $scope.callServer(tableStateRef);
        }
        $scope.is_edit = false;
        $scope.is_view = false;
    };
    /*Step 5.F*/
    /*Hapus*/
    /*Step 7.A*/
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
    /*Step 7.A*/
    /*Add Detail*/
    /*Step 8.2*/
    $scope.addDetail = function() {
        var newDet = {
            riwayat_sekolah: '',
        };
        $scope.riwayat_sekolah.push(newDet);
    };
    /*Step 8.2*/
    /*Hapus Detai;*/
        /*Step 8.3*/
        $scope.removeRow = function(paramindex, id) {
            if (id > 0) {
                if (confirm("Apa anda yakin akan MENGHAPUS PERMANENT item ini ?")) {
                    Data.delete(Control_link + '/deleteDetail/' + id).then(function(result) {
                        toaster.pop('success', "Berhasil", "Data berhasil dihapus");
                        $scope.riwayat_sekolah.splice(paramindex, 1);
                    });
                }
            } else {
                var comArr = eval($scope.riwayat_sekolah);
                if (comArr.length > 1) {
                    $scope.riwayat_sekolah.splice(paramindex, 1);
                } else {
                    alert("Something gone wrong");
                }
            }
        };
        /*Step 8.3*/
})