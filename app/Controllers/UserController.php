<?php


namespace Controllers;


use Core\Controller;
//use Classes\AuthError;
use Models\UserModel;
final class UserController extends Controller {

    /**
     * get profile
     * @return mixed
     */
    public function profile() {
        $user = \App::getInstance()->getComponent('auth')->getCurrentUser();
        return $this->app->getView()->render('user_profile', ['user' => $user]);
    }

    public function deleteProfile() {
        $auth_component = \App::getInstance()->getComponent('auth');
        $user = $auth_component->getCurrentUser();
        $status = UserModel::getModel()->delete($user->id);
        if (!$status) {
            throw new AuthError();
        }
        $auth_component->logout();
        header('Cache-Control: no-cache');
        header('Location: /', true, 301);
    }

    public function index() {
        // TODO: Implement index() method.
    }


}