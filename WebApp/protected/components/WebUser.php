<?php
class WebUser extends CWebUser {
    private $_model = null;
    public $allowAutoLogin = true;

    function getPremissionList(){
        return array(
            'user' => 'Пользователь',
            'student' => 'Студент',
            'teacher' => 'Преподователь',
            'moderator' => 'Менеджер',
            'administrator' => 'Администратор',
        );
    }
    function getServicePremissionList(){
        if(Yii::app()->user->role == "administrator") {
            return array(
                'user' => 'Пользователь',
                'student' => 'Студент',
                'teacher' => 'Преподователь',
                'moderator' => 'Менеджер',
                'administrator' => 'Администратор',
            );
        }
        else {
            return array(
                'student' => 'Студент',
                'teacher' => 'Преподователь',
                'moderator' => 'Менеджер',
            );
        }
    }
    function getRole() {
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->role;
        }
    }

    public function getUserModel(){
        if (!$this->isGuest){
            return User::model()->findByPk($this->id);
        }
    }

    private function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id, array('select' => 'role'));
        }
        return $this->_model;
    }
}
?>