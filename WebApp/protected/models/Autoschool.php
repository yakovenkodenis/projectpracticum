<?php

/**
 * This is the model class for table "autoschool".
 *
 * The followings are the available columns in table 'autoschool':
 * @property integer $id
 * @property string $name
 * @property string $contacts
 * @property string $info
 * @property string $price
 * @property string $studentCode
 * @property string $teacherCode
 *
 * The followings are the available model relations:
 * @property Group[] $groups
 * @property User[] $users
 */
class Autoschool extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'autoschool';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, contacts', 'required'),
			array('name', 'length', 'max'=>50),
			array('info, price, studentCode, teacherCode', 'safe'),
            array('studentCode, teacherCode','unique'),

            array('studentCode, teacherCode', 'length','min'=>5, 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, contacts, info, price, studentCode, teacherCode', 'safe', 'on'=>'search'),
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
			'groups' => array(self::HAS_MANY, 'Group', 'autoschool_id'),
			'users' => array(self::HAS_MANY, 'User', 'autoschool_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => 'ID',
            'name' => 'Название',
            'contacts' => 'Контакты',
            'info' => 'Описание',
            'price' => 'Цены',
			'studentCode' => 'Код для студентов',
			'teacherCode' => 'Код для преподователей',
		);
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('contacts',$this->contacts,true);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('studentCode',$this->studentCode,true);
		$criteria->compare('teacherCode',$this->teacherCode,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Autoschool the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
