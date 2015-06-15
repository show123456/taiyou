<?php
class Helper_Page
{
    /**
     * @param $page 当前页
     * @param $uri 需要组合的uri
     * 
     */
    public static function Page($page=1, $uri=null){
        return self::rule('page', $page);
    }
    
    /**
     * @param $order 排序规则
     * @param $uri 需要组合的uri
     */
    public static function status($status = 0, $uri=null){
        $uri = self::rule('page', 1);
        return self::rule('status', $status, $uri);
    }

    /**
     * @param $order 排序规则
     * @param $uri 需要组合的uri
     */
    public static function order($order = 1, $uri=null){
        $uri = self::rule('page', 1);
        return self::rule('order', $order, $uri);
    }
    
    public static function haveCent($have_cent = 1, $uri=null){
        $uri = self::rule('page', 1);
        return self::rule('have_cent', $have_cent, $uri);
    }
    
    public static function rule($key, $val, $uri=null){
        $uriKeys = array('have_cent','status','order','page');
        if(!$uri){
		    $uri = rtrim($_SERVER['REQUEST_URI'],'#');
		}
		
		//if(!in_array($key,$uriKeys)){
		//    return $uri;
		//}
		
		$newUri = $newStr = '';
		
		$output = parse_url($uri);
		$newUri = isset($output['path']) ? $output['path'] : '';
		
		parse_str($output['query'], $params);
		$params[$key] = $val;
		foreach($uriKeys as $variable){
		    $newStr .= isset($params[$variable]) ? $variable.'='.$params[$variable].'&' : '';
		}
		if($newStr) $newUri .= '?'.rtrim($newStr,'&');
		
        return $newUri;
    }
}