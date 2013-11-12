<?php
/**
 * PHP函数扩展
 * 
 * @author hegw
 *
 */
class phpfun {
	/**
	 * 数组转化成HASH
	 *
	 * @param array $array        	
	 * @param string $key_name
	 *        	//默认第一列作为键值
	 */
	static function array_to_hash($array, $key_name = 0) {
		if (empty ( $key_name ))
			$key_name = 0;
		if (empty ( $array ))
			return array ();
		$hash = array ();
		foreach ( $array as $key => $val ) {
			if (isset ( $val [$key_name] )) {
				$hash [$val [$key_name]] = $val;
			}
		}
		return $hash;
	}
	
	/**
	 * 从二维数组中取某一列值
	 *
	 * @param string $col        	
	 * @param array $stack        	
	 * @return array
	 */
	static function array_colum($col, $stack) {
		$arr = array ();
		foreach ( $stack as $key => $val ) {
			if (isset ( $val [$col] ))
				$arr [] = $val [$col];
		}
		return $arr;
	}
	/**
	 * HTML编码数组，数组键值为 url,*_url,*_link时不编码
	 *
	 * @param unknown_type $data        	
	 * @return string
	 */
	static function array_htmlspecialchars($data, $ex = ENT_QUOTES) {
		if (is_array ( $data )) {
			foreach ( $data as $k => $v ) {
				if ($k == 'url' || strpos ( $k, '_url' ) !== false || strpos ( $k, '_link' ) !== false)
					continue;
				$data [$k] = self::array_htmlspecialchars ( $v, $ex );
			}
		} else
			$data = htmlspecialchars ( $data, $ex );
		return $data;
	}
	/**
	 * 判断是否为数值ID组成的字符串
	 * @param unknown $ids
	 * @param string $sp
	 * @return boolean
	 */
	static function is_id_list($ids, $sp = null) {
		if (empty ( $ids ) && ! is_string ( $ids ))
			return false;
		if (empty ( $sp ))
			$sp = ',';
		$array = explode ( $sp, $ids );
		foreach ( $array as $key => $val ) {
			if (! is_numeric ( $val )) {
				return false;
			}
		}
		return true;
	}
	/**
	 * 字符串的长度
	 * @param string $str
	 * @return number	1个汉字长度为2
	 */
	static function str_len($str) {
		$length = strlen ( preg_replace ( '/[\x00-\x7F]/', '', $str ) );
		
		if ($length) {
			return strlen ( $str ) - $length + intval ( $length / 3 ) * 2;
		} else {
			return strlen ( $str );
		}
	}
	
	/**
	 * 截取UTF-8编码下字符串的函数
	 *
	 * @param string $str
	 *        	被截取的字符串
	 * @param int $length
	 *        	截取的长度
	 * @param bool $append
	 *        	是否附加省略号
	 * @return string
	 */
	static function str_sub($str, $length = 10, $append = true, $etc = '...') {
		$result = '';
		$str = html_entity_decode ( trim ( strip_tags ( $str ) ), ENT_QUOTES, 'UTF-8' );
		$strlen = strlen ( $str );
		
		for($i = 0; (($i < $strlen) && ($length > 0)); $i ++) {
			if ($number = strpos ( str_pad ( decbin ( ord ( substr ( $str, $i, 1 ) ) ), 8, '0', STR_PAD_LEFT ), '0' )) {
				if ($length < 1.0)
					break;
				
				$result .= substr ( $str, $i, $number );
				$length -= 1.0;
				
				$i += $number - 1;
			} else {
				$result .= substr ( $str, $i, 1 );
				$length -= 0.5;
			}
		}
		
		$result = htmlspecialchars ( $result, ENT_QUOTES, 'UTF-8' );
		if ($i < $strlen && $append) {
			$result .= $etc;
		}
		
		return $result;
	}
	
	static public function dump_table($var)
	{		 
		if( is_array($var) )
		{
			echo "<table width=\"100%\" border=\"1\">\n";
			$depth = self::array_depth($var)  ;
			if( $depth == 1 )
			{
				echo "	<tr>\n";
				foreach ($var as $k => $v)
				{
					echo "	<th>$k</th>";
				}
				echo "	</tr>\n";
			}
			else 
			{
				$keys = $var[0];
				echo "	<tr>\n";
				foreach ($keys as $k => $v)
				{
					echo "	<th>$k</th>";
				}
				echo "	</tr>\n";
			}
			
			if( self::array_depth($var) == 1)
			{
				echo "	<tr>\n";
				foreach ($var as $k => $v)
				{ 
					echo "	<td>$v</td>";
				}
				echo "	</tr>\n";
			}
			else 
			{
				foreach ($var as $k => $v)
				{
					echo "	<tr>\n";
					if( is_array($v) )
					{
						foreach ($v as $vk => $vv)
						{
							echo "	<td>$vv</td>";
						}
					}
					else{
						echo "	<td>$v</td>";
					}
					echo "</tr>\n";
				}
			}
			
			echo "</table>\n";
		}
	}
	
	 
	static function array_depth($array) 
	{
		$max_depth = 1;
		if( is_array($array) )
		{
			foreach ($array as $value) {
				if (is_array($value)) {
					$depth = self::array_depth($value) + 1;
		
					if ($depth > $max_depth) {
						$max_depth = $depth;
					}
				}
			}
		}
		
		return $max_depth;
	}
}