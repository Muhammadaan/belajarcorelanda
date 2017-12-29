<?php
/**
 * Validasi
 * @param  array $data
 * @param  array $custom
 * @return array
 */
function validasi($data, $custom = array())
{
    $validasi = array(
        'hari_id'       => 'required',
        'nama'       => 'required',
        'tipe'       => 'required',
        'no'       => 'required',
    );

    $cek = validate($data, $validasi, $custom);
    return $cek;
}


$app->get('/m_listmateri/index', function ($request, $response) {
    $params = $request->getParams();

    $sort   = "id DESC";
    $offset = isset($params['offset']) ? $params['offset'] : 0;
    $limit  = isset($params['limit']) ? $params['limit'] : 10;

    $db = $this->db;

    $db->select("m_listmateri.* , m_hari.nama as namahari")
        ->from('m_listmateri')
         ->join('left join', 'm_hari', 'm_hari.id = m_listmateri.hari_id');

       

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

  foreach ($models as $key => $val) {
        $val->hari_id = (string) $val->hari_id;
    }
   

    return successResponse($response, ['list' => $models, 'totalItems' => $totalItem]);
});


$app->post('/m_listmateri/create', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);
    if ($validasi === true) {
       
        try {
            $model = $db->insert("m_listmateri", $data);
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});


$app->post('/m_listmateri/updateprofil', function ($request, $response) {
    $data = $request->getParams();
    $id   = $_SESSION['user']['id'];

    $db = $this->db;


    $validasi = validasi($data);
    if ($validasi === true) {
        try {
            $model = $db->update("m_listmateri", $data, array('id' => $id));
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});

$app->post('/m_listmateri/update', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);
    if ($validasi === true) {
        try {
            $model = $db->update("m_listmateri", $data, array('id' => $data['id']));
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});


$app->delete('/m_listmateri/delete/{id}', function ($request, $response) {
    $db = $this->db;
    try {
        $delete = $db->delete('m_listmateri', array('id' => $request->getAttribute('id')));
        return successResponse($response, ['data berhasil dihapus']);
    } catch (Exception $e) {
        return unprocessResponse($response, ['data gagal dihapus']);
    }
});

