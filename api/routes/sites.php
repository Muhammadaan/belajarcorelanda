<?php
$app->get('/', function ($request, $responsep) {

});

$app->get('/site/session', function ($request, $response) {
    if (isset($_SESSION['user']['m_roles_id'])) {
        return successResponse($response, $_SESSION);
    }
    return unprocessResponse($response, ['undefined']);
})->setName('session');

$app->post('/site/login', function ($request, $response) {
    $params = $request->getParams();
    $sql    = $this->db;

    $username = isset($params['username']) ? $params['username'] : '';
    $password = isset($params['password']) ? $params['password'] : '';

    $model = $sql->select("m_user.*, m_roles.akses")
        ->from("m_user")
        ->leftJoin("m_roles", "m_roles.id = m_user.m_roles_id")
        ->where("username", "=", $username)
        ->andWhere("password", "=", sha1($password))
        ->find();

    if (!empty($model)) {
        $_SESSION['user']['id']         = $model->id;
        $_SESSION['user']['username']   = $model->username;
        $_SESSION['user']['nama']       = $model->nama;
        $_SESSION['user']['m_roles_id'] = $model->m_roles_id;
        $_SESSION['user']['akses']      = json_decode($model->akses);

        return successResponse($response, $_SESSION);
    }
    return unprocessResponse($response, ['Authentication Systems gagal, username atau password Anda salah.']);
})->setName('session');

$app->get('/site/logout', function () {
    session_destroy();
})->setName('logout');


/** UPLOAD GAMBAR CKEDITOR */
$app->post('/site/upload', function ($request, $response) {
    $files = $request->getUploadedFiles();
    $newfile = $files['upload'];

    if (file_exists("file/" . $newfile->getClientFilename())) {
        echo $newfile->getClientFilename() . " already exists please choose another image.";
    } else {

        $path = 'img/materi/' . date("m-Y") . '/';
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        $uploadFileName = urlParsing($newfile->getClientFilename());
        $upload = $newfile->moveTo($path . $uploadFileName);

        $crtImg = createImg($path . '/', $uploadFileName, date("dYh"), true);

        $url = getenv("SITE_URL") . $path . $crtImg['big'];

        // Required: anonymous function reference number as explained above.
        $funcNum = $_GET['CKEditorFuncNum'];
        // Optional: instance name (might be used to load a specific configuration file or anything else).
        $CKEditor = $_GET['CKEditor'];
        // Optional: might be used to provide localized messages.
        $langCode = $_GET['langCode'];

        echo "<script type='text/javascript'> window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '');</script>";
    }
});

/** END UPLOAD */


