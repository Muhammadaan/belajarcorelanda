<?php
/**
 * Validasi
 * @param  array $data
 * @param  array $custom
 * @return array
 */

/*validasi*/
/*Step 5.I*/

function validasi($data, $custom = array())
{
    $validasi = array(
        'nama' => 'required',
    );

    $cek = validate($data, $validasi, $custom);
    return $cek;
}
/*Step 5.I*/

/*Index*/
/*Step 5.A*/

$app->get('/m_siswa/index', function ($request, $response) {
    $params = $request->getParams();

    $sort   = "id DESC";
    $offset = isset($params['offset']) ? $params['offset'] : 0;
    $limit  = isset($params['limit']) ? $params['limit'] : 10;

    $db = $this->db;

    $db->select("*")
        ->from('m_siswa');

    /** set parameter */
    if (isset($params['filter'])) {
        $filter = (array) json_decode($params['filter']);
        foreach ($filter as $key => $val) {

            $db->where($key, 'LIKE', $val);

        }
    }

    /** Set limit */
    if (isset($params['limit']) && !empty($params['limit'])) {
        $db->limit($limit);
    }

    /** Set offset */
    if (isset($params['offset']) && !empty($params['offset'])) {
        $db->offset($offset);
    }

    /** Set sorting */
    if (isset($params['sort']) && !empty($params['sort'])) {
        $db->sort($sort);
    }

    $models    = $db->findAll();
    $totalItem = $db->count();

    return successResponse($response, ['list' => $models, 'totalItems' => $totalItem]);
});
/*Step 5.A*/

/*Create*/
/*step 5.H*/
$app->post('/m_siswa/create', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    // print_r($data);

    $validasi = validasi($data['form']);
    if ($validasi === true) {

        try {

            // echo $data['form']['foto']['base64'] ;

           
            // print_r($file);
            // exit();

            $model = $db->insert("m_siswa", $data['form']);
             if (isset($data['form']['foto']['base64'])) {
                $file                 = base64ToFile($data['form']['foto'], "file", 'foto_siswa_' .$data['form']['nama']);
                $data['form']['foto'] = '/file/foto_dosen/' . $file['fileName'];
            }


            foreach ($data['detail'] as $value) {

                $value['siswa_id'] = $model->id;

                $modeldetail = $db->insert("m_siswa_detail", $value);

            }
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});
/*step 5.H*/

/*Update*/
/*Step 6.B*/

$app->post('/m_siswa/update', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data['form']);
    if ($validasi === true) {
        try {
            $model = $db->update("m_siswa", $data['form'], array('id' => $data['form']['id']));
            foreach ($data['detail'] as $vals) {
                $vals['siswa_id'] = $model->id;

                /* UPDATE ATAU INSERT DATA */
                if (isset($vals['id'])) {
                    $modelss = $db->update("m_siswa_detail", $vals, array("id" => $vals['id']));
                } else {
                    $modelss = $db->insert('m_siswa_detail', $vals);
                }
            }
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});
/*Step 6.B*/

/*Hapus*/
/*Step 7.B*/

$app->delete('/m_siswa/delete/{id}', function ($request, $response) {
    $db = $this->db;
    try {
        $delete = $db->delete('m_siswa', array('id' => $request->getAttribute('id')));
        return successResponse($response, ['data berhasil dihapus']);
    } catch (Exception $e) {
        return unprocessResponse($response, ['data gagal dihapus']);
    }
});
/*Step 7.B*/

/*Step 8.7*/

$app->get('/m_siswa/view/{id}', function ($request, $response) {
    $db = $this->db;
    $id = $request->getAttribute('id');
    try {

        $model = $db->select("*")
            ->from('m_siswa')
            ->where('id', '=', $id)
            ->find();

        $modeldetail = $db->select("*")
            ->from('m_siswa_detail')
            ->where('siswa_id', '=', $id)
            ->findAll();

        return successResponse($response, ['form' => $model, 'modeldetail' => $modeldetail, 'totalItems' => $totalItem]);
    } catch (Exception $e) {
        return unprocessResponse($response, ['Terjadi Kesalahan']);
    }
});
/*Step 8.7*/

/*Hapus Detail*/
/*Step 8.9*/

$app->delete('/m_siswa/deleteDetail/{id}', function ($request, $response) {
    $db = $this->db;
    try {
        $delete = $db->delete('m_siswa_detail', array('id' => $request->getAttribute('id')));
        return successResponse($response, ['data berhasil dihapus']);
    } catch (Exception $e) {
        return unprocessResponse($response, ['data gagal dihapus']);
    }
});
/*Step 8.9.B*/
