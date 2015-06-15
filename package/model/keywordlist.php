<?php
class Model_KeywordList extends Model_Table {
	
	protected $_name = 'keyword_list';
	
    //分隔存
	public function saveForKeywords($data){
    	
    	$this->delete("customer_id='{$data['customer_id']}' and info_id={$data['info_id']} and info_type='{$data['info_type']}'");
    	$words=str_replace('，',',',$data['keyword']);
	    $wordList = explode(',',$words);
		foreach($wordList as $word){
			$line = array();
			$line['customer_id'] = $data['customer_id'];
			$line['info_id']     = $data['info_id'];
			$line['keyword']     = trim($word);
			$line['info_type']   = $data['info_type'];
			$this->insert($line);
		}
    }

}

