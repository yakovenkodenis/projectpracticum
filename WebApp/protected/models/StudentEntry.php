<?php

/**
 * This is the model class for table "student_entry".
 *
 * The followings are the available columns in table 'student_entry':
 * @property integer $entry_id
 * @property integer $student_id
 * @property integer $school_id
 * @property string $entry_time
 * @property string $additional_info
 *
 * The followings are the available model relations:
 * @property User $student
 */
class StudentEntry extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'student_entry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id, school_id', 'required'),
			array('student_id, school_id', 'numerical', 'integerOnly'=>true),
			array('additional_info', 'length', 'max'=>255),
			array('entry_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('entry_id, student_id, school_id, entry_time, additional_info', 'safe', 'on'=>'search'),
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
			'student' => array(self::BELONGS_TO, 'User', 'student_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'entry_id' => 'Entry',
			'student_id' => 'Student',
			'school_id' => 'School',
			'entry_time' => 'Entry Time',
			'additional_info' => 'Additional Info',
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

		$criteria->compare('entry_id',$this->entry_id);
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('school_id',$this->school_id);
		$criteria->compare('entry_time',$this->entry_time,true);
		$criteria->compare('additional_info',$this->additional_info,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentEntry the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
