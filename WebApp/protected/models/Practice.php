<?php

/**
 * This is the model class for table "practice".
 *
 * The followings are the available columns in table 'practice':
 * @property integer $practice_id
 * @property integer $student_id
 * @property integer $lesson
 * @property integer $day
 *
 * The followings are the available model relations:
 * @property User $student
 */
class Practice extends CActiveRecord
{
    public $start_time;
    public $end_time;
    public $group;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'practice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id, lesson, day', 'required'),
			array('student_id, lesson, day', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('practice_id, student_id, lesson, day', 'safe', 'on'=>'search'),
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
			'practice_id' => 'Practice',
			'student_id' => 'Student',
			'lesson' => 'Lesson',
			'day' => 'Day',
            'start_time' => 'Время начала',
            'end_time' => 'Время окончания',
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

		$criteria->compare('practice_id',$this->practice_id);
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('lesson',$this->lesson);
		$criteria->compare('day',$this->day);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Practice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
