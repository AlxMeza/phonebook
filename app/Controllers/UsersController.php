<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\IncomingRequest;

class UsersController extends Controller{
    use ResponseTrait;

    public function getUsers() {
        $model = new User();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    public function getUser( $id ){
        $model = new User();
        $data = $model->getWhere(['id' => $id])->getResult();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('User with id: '.$id.' Not found');
        }
    }

    public function createUser() {
        $model = new User();
        $request = json_decode($this->request->getBody());
        $request->password = password_hash($request->password, PASSWORD_DEFAULT);

        $model->insert($request);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'user created succesfully'
            ]
        ];
        return $this->respondCreated(['message' => 'user created succesfully'], 201);
    }

    public function login () {
        $model = new User();
        $request = json_decode($this->request->getBody());
        $user = $model->getWhere(['email' => $request->email])->getResult();

        if ( $user ){
            $user = $user[0];
            if( password_verify($request->password, $user->password) )
                return $this->respond(["id" => $user->id, "name" => $user->name, "lastname" => $user->lastname, "email" => $user->email, "phone" => $user->phone], 200);
            else return $this->respond(["message" => 'password is incorrect'], 401);
        }
        else return $this->respond(["message" => 'email is not registered'], 401);
    }
}