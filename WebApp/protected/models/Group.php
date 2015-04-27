<?php

/**
 * This is the model class for table "group".
 *
 * The followings are the available columns in table 'group':
 * @property integer $group_id
 * @property integer $autoschool_id
 * @property string $name
 * @property string $practice_start
 * @property integer $practice_days
 * @property integer $practice_teacher
 * @property string $practice_meetpoint
 * @property integer $practice_reserv_count
 *
 * The followings are the available model relations:
 * @property Autoschool $autoschool
 * @property User $practiceTeacher
 * @property Theory[] $theories
 */
class Group extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('autoschool_id, name', 'required'),
			array('autoschool_id, practice_days, practice_teacher, practice_reserv_count', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('practice_meetpoint', 'length', 'max'=>50),
			array('practice_start', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('group_id, autoschool_id, name, practice_start, practice_days, practice_teacher, practice_meetpoint, practice_reserv_count', 'safe', 'on'=>'search'),
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
			'autoschool' => array(self::BELONGS_TO, 'Autoschool', 'autoschool_id'),
			'practiceTeacher' => array(self::BELONGS_TO, 'User', 'practice_teacher'),
			'theories' => array(self::HAS_MANY, 'Theory', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'group_id' => 'group_id',
            'autoschool_id' => 'Автошкола',
            'name' => 'Название группы',
            'practice_start' => 'Начало практики',
            'practice_days' => 'Кол-во дней практики',
            'practice_teacher' => 'Преподаватель практики',
			'practice_meetpoint' => 'Точка сбора',
			'practice_reserv_count' => 'Кол-во практики для студента',
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

		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('autoschool_id',$this->autoschool_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('practice_start',$this->practice_start,true);
		$criteria->compare('practice_days',$this->practice_days);
		$criteria->compare('practice_teacher',$this->practice_teacher);
		$criteria->compare('practice_meetpoint',$this->practice_meetpoint,true);
		$criteria->compare('practice_reserv_count',$this->practice_reserv_count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Group the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
