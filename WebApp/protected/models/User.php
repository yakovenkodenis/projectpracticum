<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $role
 * @property integer $autoschool_id
 * @property string $email
 * @property string $name
 * @property string $telephone
 * @property string $address
 *
 * The followings are the available model relations:
 * @property Practice[] $practices
 * @property Practice[] $practices1
 * @property Rating[] $ratings
 * @property StudentEntry[] $studentEntries
 * @property StudentToGroup[] $studentToGroups
 * @property Theory[] $theories
 * @property Autoschool $autoschool
 */
class User extends CActiveRecord
{

    public $verifyCode;
    public $autoschoolCode;
    public $password_repeat; // для проверки пароля
    public $group_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, password, email, name', 'required'),
			array('autoschool_id, group_id', 'numerical', 'integerOnly'=>true),
			array('login', 'length', 'max'=>50),
			array('password', 'length', 'max'=>40),
			array('role, autoschoolCode', 'length', 'max'=>20),
			array('email, name, telephone, address', 'length', 'max'=>255),
            array('email', 'email'),
            array('login','unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('login, role, autoschool_id, email, name, telephone, address', 'safe', 'on'=>'search'),
            array('password_repeat', 'required', 'on'=>'registration'),
            array('password', 'compare', 'on'=>'registration'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(),'on'=>'registration'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'practices' => array(self::HAS_MANY, 'Practice', 'teacher_id'),
			'practices1' => array(self::HAS_MANY, 'Practice', 'student_id'),
			'ratings' => array(self::HAS_MANY, 'Rating', 'user_id'),
			'studentEntries' => array(self::HAS_MANY, 'StudentEntry', 'student_id'),
			'studentToGroups' => array(self::HAS_MANY, 'StudentToGroup', 'student_id'),
			'theories' => array(self::HAS_MANY, 'Theory', 'teacher_id'),
			'autoschool' => array(self::BELONGS_TO, 'Autoschool', 'autoschool_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => 'ID',
            'login' => 'Логин',
            'password' => 'Пароль',
            'role' => 'Привелегии',
            'autoschool_id' => 'Автошкола',
            'email' => 'Email',
            'name' => 'ФИО',
            'telephone' => 'Телефон',
            'address' => 'Адрес',
            'password_repeat' => 'Повторите пароль',
            'verifyCode'=>'Проверочный код',
            'autoschoolCode'=>'Код автошколы',
            'group_id'=>'Группа',
		);
	}

    public function beforeSave(){
        $old = User::model()->findByPk($this->id);
        $stgoldroup = StudentToGroup::model()->findByAttributes(array('student_id'=>$this->id));
        if($old == null || $this->password != $old->password){
            $this->password = md5($this->password);
        }
        if($this->role == "administrator" || $this->role == "user"){ // если админ или пользователь то им группы не нужны
            $this->autoschool_id = "";
            $this->group_id = "";
        }
        if(parent::beforeSave())
        {
            if($this->group_id!=""){ // если добавленна группа до добовляем её в бд
                $stgroup = StudentToGroup::model()->findByAttributes(array('student_id'=>$this->id));
                if(isset($stgroup)) {
                    $stgroup->delete();
                }
                if($this->group_id!=$stgoldroup->group_id) { // если была изменена группа то удаляем практику
                    $practicereserv = Practice::model()->findAllByAttributes(array('student_id' => $this->id)); // если студент был записан на практики то удаляем его
                    if (isset($practicereserv)) {
                        foreach ($practicereserv as $item) {
                            $item->delete();
                        }
                    }
                }
                $stgroup = new StudentToGroup;
                $stgroup->group_id = $this->group_id;
                $stgroup->student_id = $this->id;
                $stgroup->save();
            }
            else {
                $stgroup = StudentToGroup::model()->findByAttributes(array('student_id'=>$this->id));
                if(isset($stgroup)) {
                    $stgroup->delete();
                }
                $practicereserv = Practice::model()->findAllByAttributes(array('student_id'=>$this->id)); // если студент был записан на практики то удаляем его
                if(isset($practicereserv)){
                    foreach($practicereserv as $item){
                        $item->delete();
                    }
                }
            }
            return true;
        }
        else
            return false;
    }

    public static function GetSchoolTeachers($school_id)
    {
        return User::model()->findAllByAttributes(array('role'=>'teacher','autoschool_id'=>$school_id));
    }



	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('autoschool_id',$this->autoschool_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('address',$this->address,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
