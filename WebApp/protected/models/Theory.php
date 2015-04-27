<?php

/**
 * This is the model class for table "theory".
 *
 * The followings are the available columns in table 'theory':
 * @property integer $theory_id
 * @property integer $teacher_id
 * @property integer $group_id
 * @property string $room
 * @property string $start_time
 * @property string $end_time
 *
 * The followings are the available model relations:
 * @property Group $group
 * @property User $teacher
 */
class Theory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'theory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('teacher_id, group_id, room, start_time, end_time', 'required'),
			array('teacher_id, group_id', 'numerical', 'integerOnly'=>true),
			array('room', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('theory_id, teacher_id, group_id, room, start_time, end_time', 'safe', 'on'=>'search'),
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
			'group' => array(self::BELONGS_TO, 'Group', 'group_id'),
			'teacher' => array(self::BELONGS_TO, 'User', 'teacher_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'theory_id' => 'Теория',
			'teacher_id' => 'Преподаватель',
			'group_id' => 'Группа',
			'room' => 'Комната',
			'start_time' => 'Начало',
			'end_time' => 'Конец',
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

		$criteria->compare('theory_id',$this->theory_id);
		$criteria->compare('teacher_id',$this->teacher_id);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('room',$this->room,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Theory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
