<?php
class Model_SxScoreLog extends Model_Subtable{
	//写积分日志
	public function addLog($fromuser,$score,$desc){
		$data['info'][fromuser]=$fromuser;
		$data['info'][score]=$score;
		$data['info'][desc]=$desc;
		$this->add($data);
	}
}