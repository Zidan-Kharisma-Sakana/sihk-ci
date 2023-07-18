<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;

class Register extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        // echo "Hello World";
        // echo $this->request->getBody();
        // received nama_penerima, durasi_dalam_hari and jwt_secret from body
        $rules = [
            'nama_penerima' => ['rules' => 'required|min_length[4]|max_length[255]'],
            'durasi_dalam_hari' => ['rules' => 'required|is_integer'],
            'jwt_secret_password'  => ['rules' => 'required']
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $clientName = $this->request->getVar('nama_penerima');
        $durationInDays = $this->request->getVar('durasi_dalam_hari');
        $adminPassword = $this->request->getVar('jwt_secret_password');
        $key = getenv('JWT_SECRET');
        if (!is_numeric($durationInDays)) {
            return $this->failValidationErrors("duration must be in number format");
        }
        if ($adminPassword != $key) {
            return $this->failUnauthorized("JWT Secret isnt valid");
        }
        $iat = time(); // current timestamp value
        $exp = $iat + 3600 * 24 * $durationInDays;

        $payload = array(
            "iss" => "bappebti",
            "aud" => $clientName,
            "sub" => "komoditi",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
        );

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'message' => 'Login Succesful',
            'token' => $token
        ];

        return $this->respond($response, 200);
    }
}
