<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User;
use App\Models\Contact;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\IncomingRequest;

class UsersController extends Controller{
    use ResponseTrait;

    /*Users Methods*/
    public function getUsers() {
        $model = new User();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    public function getUser( $id ){
        $model = new User();
        $data = $model->getWhere(['id' => $id])->getResult();
        if($data){
            return $this->respond($data, 200);
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

    /*Contact Methods*/
    public function createContact (){
        $model = new Contact();
        $request = json_decode($this->request->getBody());
        
        $model->insert($request);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'contact created succesfully'
            ]
        ];
        return $this->respondCreated(['message' => 'contact created succesfully'], 201);
    }

    public function putContact (){
        $model = new Contact();
        $request = json_decode($this->request->getBody());
        $data = $model->update($request->id, $request);

        if( $data ) return $this->respond(['message' => 'contact modified succesfully'], 200);
        else return $this->respond(['message' => 'contact cannot be modify'], 400);
    }

    public function deleteContact( $id, $users_key ){
        $model = new Contact();

        $data = $model->getWhere([ 'id' => $id, 'users_key' => $users_key ])->getResult();
        if( $data ){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Contact deleted succesfully'
                ]
            ];
            return $this->respondDeleted($response);
        }
        else return $this->respond(['message' => 'contact not found'], 404);
    }

    public function getContactById ( $id ){
        $model = new Contact();
        $data = $model->getWhere(['users_key' => $id])->getResult();
        if($data){
            return $this->respond($data, 200);
        }else{
            return $this->failNotFound('Users have not contacts');
        }
    }
}