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
        'nama'   => 'required',
        'target' => 'required',
    );

    $cek = validate($data, $validasi, $custom);
    return $cek;
}

$app->get('/belajar/index', function ($request, $response) {
    $params = $request->getParams();

    $sort   = "id DESC";
    $offset = isset($params['offset']) ? $params['offset'] : 0;
    $limit  = isset($params['limit']) ? $params['limit'] : 10;

    $db      = $this->db;
    $id_user = $_SESSION['user']['id'];
    $db->select("m_hari.*,paham.hari_id as paham")
        ->from('m_hari')
        ->where("paham.created_by", "=", $id_user)
        ->join('left join', 'paham', 'm_hari.id = paham.hari_id');

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

$app->get('/belajar/view/{id}', function ($request, $response) {
    $params = $request->getParams();
    $db     = $this->db;
    $id     = $request->getAttribute('id');

    $data = $db->select("*")
        ->from("m_listmateri")
        ->where("hari_id", "=", $id)
        ->orderBy('no ASC')
        ->findAll();

    return successResponse($response, ['form' => $data]);

});

$app->get('/belajar/viewdetail/{id}', function ($request, $response) {
    $params = $request->getParams();
    $db     = $this->db;
    $id     = $request->getAttribute('id');

    $data = $db->select("isi")
        ->from("m_materi")
        ->where("listmateri_id", "=", $id)
        ->find();

    return successResponse($response, ['form' => $data]);

});

$app->post('/belajar/paham', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    $isi['hari_id'] = $data['hari_id'] + 1;
    $id_user        = $_SESSION['user']['id'];
    $cek            = $db->select("*")
        ->from("paham")
        ->where("hari_id", "=", $isi['hari_id'])
        ->Andwhere("created_by", "=", $id_user)
        ->find();

    if ($cek) {

        return unprocessResponse($response, ['Iya Iya Kamu Sudah Paham']);

    } else {

        try {

            $isi['hari_id'] = $data['hari_id'] + 1;
            $model          = $db->insert("paham", $isi);
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }

    }

});

$app->post('/belajar/mulai', function ($request, $response) {
    $data = $request->getParams();
    $db   = $this->db;

    try {
        $model = $db->insert("paham", $data);
        return successResponse($response, $model);
    } catch (Exception $e) {
        return unprocessResponse($response, ['data gagal disimpan']);
    }

});
