<?php

/**
 * This is the model class for table "practice_settings".
 *
 * The followings are the available columns in table 'practice_settings':
 * @property integer $autoschool_id
 * @property integer $monday
 * @property integer $tuesday
 * @property integer $wednesday
 * @property integer $thursday
 * @property integer $friday
 * @property integer $saturday
 * @property integer $sunday
 * @property string $practice1
 * @property string $practice2
 * @property string $practice3
 * @property string $practice4
 * @property string $practice5
 * @property string $practice6
 * @property string $practice7
 * @property string $duration
 *
 * The followings are the available model relations:
 * @property Autoschool $autoschool
 */
class PracticeSettings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'practice_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('autoschool_id', 'required'),
			array('autoschool_id, monday, tuesday, wednesday, thursday, friday, saturday, sunday', 'numerical', 'integerOnly'=>true),
			array('practice1, practice2, practice3, practice4, practice5, practice6, practice7, duration', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('autoschool_id, monday, tuesday, wednesday, thursday, friday, saturday, sunday, practice1, practice2, practice3, practice4, practice5, practice6, practice7, duration', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'autoschool_id' => 'Автошкола',
			'monday' => 'Понедельник',
			'tuesday' => 'Вторник',
			'wednesday' => 'Среда',
			'thursday' => 'Четверг',
			'friday' => 'Пятница',
			'saturday' => 'Суббота',
			'sunday' => 'Воскресенье',
			'practice1' => 'Зянатие 1',
			'practice2' => 'Зянатие 2',
			'practice3' => 'Зянатие 3',
			'practice4' => 'Зянатие 4',
			'practice5' => 'Зянатие 5',
			'practice6' => 'Зянатие 6',
			'practice7' => 'Зянатие 7',
			'duration' => 'Длительность занятия',
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

		$criteria->compare('autoschool_id',$this->autoschool_id);
		$criteria->compare('monday',$this->monday);
		$criteria->compare('tuesday',$this->tuesday);
		$criteria->compare('wednesday',$this->wednesday);
		$criteria->compare('thursday',$this->thursday);
		$criteria->compare('friday',$this->friday);
		$criteria->compare('saturday',$this->saturday);
		$criteria->compare('sunday',$this->sunday);
		$criteria->compare('practice1',$this->practice1,true);
		$criteria->compare('practice2',$this->practice2,true);
		$criteria->compare('practice3',$this->practice3,true);
		$criteria->compare('practice4',$this->practice4,true);
		$criteria->compare('practice5',$this->practice5,true);
		$criteria->compare('practice6',$this->practice6,true);
		$criteria->compare('practice7',$this->practice7,true);
		$criteria->compare('duration',$this->duration,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function  beforeSave()
    {
        if($this->practice1=="") $this->practice1 = $this->FixList(1);
        if($this->practice2=="") $this->practice2 = $this->FixList(2);
        if($this->practice3=="") $this->practice3 = $this->FixList(3);
        if($this->practice4=="") $this->practice4 = $this->FixList(4);
        if($this->practice5=="") $this->practice5 = $this->FixList(5);
        if($this->practice6=="") $this->practice6 = $this->FixList(6);

        return parent::beforeSave();
    }

    private function FixList($from){
        $last = "";
        switch($from){
            case 1:
                $last = $this->practice2;
                if($last!="") {
                    $this->practice2 = "";
                    return $last;
                }
            case 2:
                $last = $this->practice3;
                if($last!="") {
                    $this->practice3 = "";
                    return $last;
                }
            case 3:
                $last = $this->practice4;
                if($last!="") {
                    $this->practice4 = "";
                    return $last;
                }
            case 4:
                $last = $this->practice5;
                if($last!="") {
                    $this->practice5 = "";
                    return $last;
                }
            case 5:
                $last = $this->practice6;
                if($last!="") {
                    $this->practice6 = "";
                    return $last;
                }
            case 6:
                $last = $this->practice7;
                if($last!="") {
                    $this->practice7 = "";
                    return $last;
                }
        }
        return $last;
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PracticeSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
