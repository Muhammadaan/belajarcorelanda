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
        'nama'       => 'required',
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

    $validasi = validasi($data);
    if ($validasi === true) {

        try {
            $model = $db->insert("m_siswa", $data);
            return successResponse($response, $model);
        } catch (Exception $e) {
            return unprocessResponse($response, ['data gagal disimpan']);
        }
    }
    return unprocessResponse($response, $validasi);
});
/*step 5.H*/

// $app->post('/m_hari/updateprofil', function ($request, $response) {
//     $data = $request->getParams();
//     $id   = $_SESSION['user']['id'];

//     $db = $this->db;

//     $validasi = validasi($data);
//     if ($validasi === true) {
//         try {
//             $model = $db->update("m_hari", $data, array('id' => $id));
//             return successResponse($response, $model);
//         } catch (Exception $e) {
//             return unprocessResponse($response, ['data gagal disimpan']);
//         }
//     }
//     return unprocessResponse($response, $validasi);
// });

// $app->post('/m_hari/update', function ($request, $response) {
//     $data = $request->getParams();
//     $db   = $this->db;

//     $validasi = validasi($data);
//     if ($validasi === true) {
//         try {
//             $model = $db->update("m_hari", $data, array('id' => $data['id']));
//             return successResponse($response, $model);
//         } catch (Exception $e) {
//             return unprocessResponse($response, ['data gagal disimpan']);
//         }
//     }
//     return unprocessResponse($response, $validasi);
// });

// $app->delete('/m_hari/delete/{id}', function ($request, $response) {
//     $db = $this->db;
//     try {
//         $delete = $db->delete('m_hari', array('id' => $request->getAttribute('id')));
//         return successResponse($response, ['data berhasil dihapus']);
//     } catch (Exception $e) {
//         return unprocessResponse($response, ['data gagal dihapus']);
//     }
// });
