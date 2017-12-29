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
        'isi'           => 'required',
        'listmateri_id' => 'required',
    );

    $cek = validate($data, $validasi, $custom);
    return $cek;
}

$app->get('/m_materi/index', function ($request, $response) {
    $params = $request->getParams();

    $sort   = "id DESC";
    $offset = isset($params['offset']) ? $params['offset'] : 0;
    $limit  = isset($params['limit']) ? $params['limit'] : 10;

    $db = $this->db;

    $db->select("m_materi.*,m_hari.nama as namahari,m_listmateri.nama as namalist")
        ->from('m_materi')
       ->join('left join', 'm_hari', 'm_hari.id = m_materi.hari_id')
       ->join('left join', 'm_listmateri', 'm_listmateri.id = m_materi.listmateri_id');

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
         $val->listmateri_id = (string) $val->listmateri_id;
    }

    return successResponse($response, ['list' => $models, 'totalItems' => $totalItem]);
});

$app->post('/m_materi/create', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);
    if ($validasi === true) {

        $db->select("*")
            ->from("m_materi")
            ->where("hari_id", "=", $data['hari_id'])
            ->Andwhere("listmateri_id", "=", $data['listmateri_id'])
            ->find();
        $cek = $db->count();

        if ($cek == 0) {

            try {
                $model = $db->insert("m_materi", $data);
                return successResponse($response, $model);
            } catch (Exception $e) {
                return unprocessResponse($response, ['data gagal disimpan']);
            }

        } else {
            return unprocessResponse($response, ['Data Sudah Ada']);
        }

    }
    return unprocessResponse($response, $validasi);
});

$app->post('/m_materi/updateprofil', function ($request, $response) {
    $data = $request->getParams();
    $id   = $_SESSION['user']['id'];

    $db = $this->db;

    $validasi = validasi($data);
    if ($validasi === true) {

        try {
            $model = $db->update("m_materi", $data, array('id' => $id));
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});

$app->post('/m_materi/update', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $validasi = validasi($data);
    if ($validasi === true) {
        try {
            $model = $db->update("m_materi", $data, array('id' => $data['id']));
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});

$app->delete('/m_materi/delete/{id}', function ($request, $response) {
    $db = $this->db;
    try {
        $delete = $db->delete('m_materi', array('id' => $request->getAttribute('id')));
        return successResponse($response, ['data berhasil dihapus']);
    } catch (Exception $e) {
        return unprocessResponse($response, ['data gagal dihapus']);
    }
});

$app->get('/m_materi/listmateri/{id}', function ($request, $response) {
    $params = $request->getParams();
    $db     = $this->db;
    $id     = $request->getAttribute('id');

    $data = $db->select("*")
        ->from("m_listmateri")
        ->where("hari_id", "=", $id)
        ->findAll();


    foreach ($data as $key => $val) {
        $val->listmateri_id = (string) $val->listmateri_id;
    }

    return successResponse($response, ['data' => $data]);

});
