<?php 
	/* 
	*PHP常用算法总结
	*作者：楚尚明
	*时间：2015.05.05
	*/

	/* 	
	    按数量级递增排列，常见的时间复杂度有：
			常数阶O(1),对数阶O(log2n),线性阶O(n),线性对数阶O(nlog2n),平方阶O(n2)，立方阶O(n3)
	 */
	 
//*******************算法1：加密算法****************

    /**
     * 生成密码密文
     */
	public function password_encrypt($password,$cryptograph=null) {
		$str="";
		if($cryptograph==null){

			for($i=1;$i<=5;$i++){
				$str.=substr("abcdefghijklmnopqrstuvwxyz1234567890",rand(0,35),1);
			}
		}
		else{
			$str=substr($cryptograph,0,5);
		}		
		$str.= md5($str.$password."YIOISDIFLLSLJIOU987998902-LLK>JJKLKLJSDF=LK23LKKL2349@&KJLJKSLKLKLJSFDKLJSKLJF<DKLJSFDKJKJLSD|FKJ.LJ");
		$str = substr($str,0,32);
		return $str;
	}
	
//*******************算法2：二分查找O(log2n)****************

	function erfen($a,$l,$h,$f){
		if($l >$h){ return false;}
		$m = intval(($l+$h)/2);
		if ($a[$m] == $f){
			return $m;
		}elseif ($f < $a[$m]){
			return erfen($a, $l, $m-1, $f);
		}else{
			return erfen($a, $m+1, $h, $f);
		}

	}
	// 二分查找 非递归算法 
	function div_search($ary,$key) { 
	    $low = 0; 
	    $high = count($ary) - 1; 
	    $i = 0; 
	    while($low <= $high) { 
	        $mid = floor(($high+$low)/2); 
	        if($key == $ary[$mid]) return $key; 
	        elseif($key < $ary[$mid]) $high = $mid -1;// 唯有这样，范围才会不断缩小啊 
	        else $low = $mid + 1; 
	    } 
	} 
	$a = array(1,12,23,67,88,100);
	var_dump(erfen($a,0,5,1));
	
//*******************算法3：顺序查找（数组里查找某个元素）****************

	function seq_sch($array, $n, $k){
		$array[$n] = $k;
		for($i=0; $i<$n; $i++){
			if($array[$i]==$k){
				break;
			}
		}
		if ($i<$n){
			return $i;
		}else{
			return -1;
		}
	}
	//顺序查找
	function search(&$arr,$findVal){
			$flag=false;
			for($i=0;$i<count($arr);$i++){
                if($findVal==$arr[$i]){
                    echo '找到了，下标为：'.$i.'<br />';
					$flag=true;
					//break;
				}
			}
			if(!$flag){
			    echo '查不到该数！'; 
			}
	}
	
//*******************算法4：遍历树O(log2n)****************

	function bianli($p){
		$a = array();
		foreach (glob($p.'/*') as $f){
			if(is_dir($f)){
				$a = array_merge($a,bianli($f));
			}else{
				$a[] = $f;
			}
		}
		return $a;
	}
	
//*******************算法5：阶乘O(log2n)****************

	function jc($n){
		if($n<=1){
			return 1;
		}else{
			return $n*jc($n-1);
		}    
	}
	
//*******************算法6：快速排序  O(n *log2(n))****************

	function kuaisu($a){
		$c = count($a);
		if($c <= 1){return $a;}
		$l = $r = array();    
		for ($i=1;$i<$c;$i++){
			if($a[$i] < $a[0]){
				$l[] = $a[$i];
			}else{
				$r[] = $a[$i];
			}
		}
		$l = kuaisu($l);
		$r = kuaisu($r);
		return array_merge($l,array($a[0]),$r);
	}
	//方式二：对数组进行quickSort快速排序的方法
	function quickSort($left,$right,$arr){
        $l=$left;
		$r=$right;
		$pivot=$arr[($left+$right)/2];
		$temp=0;
        while($l<$r){
            while($arr[$l]<$pivot) $l++;
			while($arr[$r]>$pivot) $r--;

			if($l>=$r) break;

			$temp=$arr[$l];
			$arr[$l]=$arr[$r];
			$arr[$r]=$temp;

			if($arr[$l]==$pivot) --$r;
			if($arr[$r]==$pivot) ++$l;
		}
		if($l==$r){
           $l++;
		   $r--;
		}
		 if($left<$r)  quickSort($left,$r,$arr);
		 if($right>$l) quickSort($l,$right,$arr);
		//如果不需要地址符&改变原始的数组，可以用return返回一个排序后的新数组
		return $arr;
	} 
	
	//方式三：快速排序第三个版本。
	function partition(&$ary,$low,$high) { 
	    $tmp = $ary[$low]; 
	    while($low < $high) { 
	        while($low < $high && $ary[$high] >= $tmp) {  
	            $high--; 
	        } 
	        $ary[$low] = $ary[$high];// 巧妙之处: 这里只要一发生交换，下面的low就必须往前走一步 
	        while($low < $high && $ary[$low] <= $tmp) { 
	            $low++; 
	        } 
	        $ary[$high] = $ary[$low];// 这里只要一发生交换，上面的high就必须往前走一步,这样一来，其实左右 
	        // 调换过来的元素并没有相互覆盖掉。 
	    } 
	    $ary[$low] = $tmp;// 最后，需要把基数赋值给low下标，此时的low下面就是调整后次序列的分水岭，右边值大，左边值小 
	    return $low; 
	} 
	function quick_sort2(&$ary,$low,$high) { 
	    if($low < $high) { 
	        $p = partition($ary,$low,$high); 
	        quick_sort($ary,$low,$p - 1); 
	        quick_sort($ary,$p+1,$high); 
	    } 
	    return $ary; 
	} 
	$arr = array(35,66,2,15,6,81,6,9); 
	$sort_ary = quick_sort2($ary,0,count($ary)); 
	print_r($sort_ary); 
	
//*******************算法7：插入排序  O(N*N)****************

	/* 
	【基本思想】：	每次将一个待排序的数据元素，插入到前面已经排好序的数列中的适当位置，
					使数列依然有序；直到待排序数据元素全部插入完为止。
	【示	例】：
					[初始关键字] [49] 38 65 97 76 13 27 49
					J=2(38) [38 49] 65 97 76 13 27 49
					J=3(65) [38 49 65] 97 76 13 27 49
					J=4(97) [38 49 65 97] 76 13 27 49
					J=5(76) [38 49 65 76 97] 13 27 49
					J=6(13) [13 38 49 65 76 97] 27 49
					J=7(27) [13 27 38 49 65 76 97] 49
					J=8(49) [13 27 38 49 49 65 76 97] 
	*/
	function insert_sort($arr){
		$count = count($arr);
		for($i=1; $i<$count; $i++){
			$tmp = $arr[$i];
			$j = $i - 1;
			while($arr[$j] > $tmp){
				$arr[$j+1] = $arr[$j];
				$arr[$j] = $tmp;
				$j–-;
			}
		}
		return $arr;
	}
	//方式二：
	function charu($a){
	  $c = count($a);
	  for($i=1;$i<$c;$i++){
		  $t = $a[$i];
		  for($j=$i;$j>0 && $a[$j-1]>$t;$j--){
			  $a[$j] = $a[$j-1];          
		  }
		  $a[$j] = $t;
	  }
	  return $a;
	}
	
//*******************算法8：选择排序  O(N*N)****************

	/* 
	【基本思想】：	每一趟从待排序的数据元素中选出最小（或最大）的一个元素，
					顺序放在已排好序的数列的最后，直到全部待排序的数据元素排完。
	【示	例】：
					[初始关键字] [49 38 65 97 76 13 27 49]
					第一趟排序后 13 ［38 65 97 76 49 27 49]
					第二趟排序后 13 27 ［65 97 76 49 38 49]
					第三趟排序后 13 27 38 [97 76 49 65 49]
					第四趟排序后 13 27 38 49 [49 97 65 76]
					第五趟排序后 13 27 38 49 49 [97 97 76]
					第六趟排序后 13 27 38 49 49 76 [76 97]
					第七趟排序后 13 27 38 49 49 76 76 [ 97]
					最后排序结果 13 27 38 49 49 76 76 97 
	*/
	function select_sort($arr){
		$count = count($arr);
		for($i=0; $i<$count; $i++){
			$k = $i;
			for($j=$i+1; $j<$count; $j++){
				if ($arr[$k] > $arr[$j])
				$k = $j;
			}
			//最小元素k和i调换
			if($k != $i){
				$tmp = $arr[$i];
				$arr[$i] = $arr[$k];
				$arr[$k] = $tmp;
			}
		}
		return $arr;
	}
	//方式二：
	function xuanze($a){
		$c = count($a);
		for($i=0;$i<$c;$i++){
			for ($j=$i+1;$j<$c;$j++){
				if($a[$i]>$a[$j]){
					$t = $a[$j];
					$a[$j] = $a[$i];
					$a[$i] = $t;
				 }
			}
		}
		return $a;
	}
	
//*******************算法9：折半插入*******************
	
	/*
		折半插入:由于普通的插入算法是依次将待排序的元素与已经完成排序的序列每一个元素做比较, 
		然后插入到合适位置。二分插入的出现是为了减少元素的比较次数，本质是对插入排序的优化。 
		具体思想是：利用二分查找，直接将待排序的那个元素定位到有序序列中需要插入的位置。这是优化的关键点。 
	*/ 
	function cr_sort3($ary) { 
	    for($i = 1;$i < count($ary);$i++) { 
	        $tmp = $ary[$i]; 
	        $j = $i - 1; 
	        $low = 0; 
	        $high = $i - 1; 
	        while($low <= $high) { 
	            $mid = floor(($low + $high) / 2); 
	            if($tmp > $ary[$mid]) $low = $mid + 1; 
	            else  $high = $mid - 1; 
	        } 
	         
	        while($j >= $low) { 
	            $ary[$j + 1] = $ary[$j]; 
	            $j--; 
	        } 
	        $ary[$low] = $tmp; 
	    } 
	    return $ary; 
	} 
	
//*******************算法10：希尔排序*******************

	/* 
		希尔排序：对插入排序的改进版。 基本算法是建立在直接插入排序算法之上的。 
		基本思想是：按照某递增量，“间隔”的将待排序列调整为有序的序列。跳跃性的插入排序。 

	*/ 
	function shell_sort($ary) { 
	    $d = count($ary); 
	    while($d  > 1) { 
	        $d  = intval($d / 2); //递增 
	        for($i = $d;$i < count($ary);$i+=$d) { 
	            $tmp = $ary[$i]; 
	            $j = $i - $d;     
	            while($j >= 0 && $ary[$j] > $tmp) { 
	                $ary[$j + $d] = $ary[$j]; 
	                $j -= $d; 
	            } 
	            $ary[$j+$d] = $tmp; 
	        } 
	    } 
	    return $ary; 
	} 

//*******************算法11： 回溯法找子串******************* 

	function find_str($str,$substr) { 
	    $i = 0; 
	    $j =0 ; 
	    while($i<strlen($str) && $j<strlen($substr)) { 
	        if($str[$i]==$substr[$j]) { 
	            $i++; 
	            $j++; 
	        } else { 
	            $i = $i - $j +1; // 不相等的情况下，i是要向前走的哦！ 
	            $j = 0; 
	        } 
	    } 
	    if($j == strlen($substr)) return true; 
	    return false; 
	} 

	$str = 'XXXhello world'; 
	$substr = 'ld'; 
	echo find_str($str,$substr); 
	exit; 

//*******************算法12：冒泡排序   O(N*N)****************

	/* 	
		【基本思想】：	两两比较待排序数据元素的大小，发现两个数据元素的次序相反时即进行交换，直到没有反序的数据元素为止。
		【排序过程】：	设想被排序的数组R［1..N］垂直竖立，将每个数据元素看作有重量的气泡，
						根据轻气泡不能在重气泡之下的原则，从下往上扫描数组R，凡扫描到违反本原则的轻气泡，就使其向上”漂浮” ，
						如此反复进行，直至最后任何两个气泡都是轻者在上，重者在下为止。
	 */
	function bubble_sort($array){
		$count = count($array);
		if ($count <= 0) return false;
		for($i=0; $i<$count; $i++){
			for($j=$count-1; $j>$i; $j–-){
				if ($array[$j] < $array[$j-1]){
					$tmp = $array[$j];
					$array[$j] = $array[$j-1];
					$array[$j-1] = $tmp;
				}
			}
		}
		return $array;
	}
		
//*******************算法13：二维数组排序， $arr是数据，$keys是排序的健值，$order是排序规则，1是升序，0是降序****************

	function array_sort($arr, $keys, $order=0) {
		if (!is_array($arr)) {
			return false;
		}
		$keysvalue = array();
		foreach($arr as $key => $val) {
			$keysvalue[$key] = $val[$keys];
		}
		if($order == 0){
			asort($keysvalue);
		}else {
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach($keysvalue as $key => $vals) {
			$keysort[$key] = $key;
		}
		$new_array = array();
		foreach($keysort as $key => $val) {
			$new_array[$key] = $arr[$val];
		}
		return $new_array;
	}

//*******************算法14：计数排序****************
	/* 
		计数排序：依次计算出待排序列中每个元素比他大（小）的元素个数。然后根据这个个数依次输出即可 
		得出有序的序列。缺点是：需要的空间巨大，特别是待排序列元素个数小，但是最大值却巨大的情况下， 
		性能极差。 
	*/ 
	function count_sort($ary) { 
	    $tmp = array(); 
	    for($i = 0;$i< count($ary);$i++) { //第一步，需要找出最大值 
	        if($max < $ary[$i]) { 
	            $max = $ary[$i]; 
	        } 
	    } 
	    for($i = 0;$i < $max;$i++) { 
	        $tmp[$i] = 0; 
	    } 
	    for($i = 0;$i < count($ary);$i++) { 
	        $tmp[$ary[$i]]++; 
	    } 
	     
	    for ($i = 1; $i < count($tmp); $i++) { 
	        $tmp[$i] += $tmp[$i-1]; 
	    } 
	    //print_r($tmp); 
	    for ($i = 0; $i < count($ary); $i++) { 
	        $tmp_ary[$tmp[$ary[$i]]] = $ary[$i]; 
	        $tmp[$ary[$i]]--; 
	    } 
	     
	    for ($i = 0; $i < count($tmp_ary); $i++) { 
	        $ret[] = $tmp_ary[$i]; 
	    } 
	    return $ret; 
	} 
	$arr = array(35,66,2,15,6,81,6,44); 
	print_r(count_sort($arr)); 

//*******************算法15：堆排序****************

	 $arr = array(35,66,2,15,6,81,6,9,0,-2,9); 
    /* 
		堆排序: 利用大（小）顶堆的特性，不断调整堆，依次选出待排序列中最大、次大值。 
    	代码参考自：http://www.cnblogs.com/zemliu/archive/2012/08/18/2645941.html 
    */ 
     function heapSort(&$arr) { 
         //初始化大顶堆 为什么要初始化，其实是为了找出待排序列中最大的值 
         initHeap($arr, 0, count($arr) - 1); 
         //print_r($arr);exit; 
         //开始交换首尾节点,并每次减少一个末尾节点再调整堆,直到剩下一个元素 
         for($end = count($arr) - 1; $end > 0; $end--) {  
			// 依次取出大顶堆中第一个根节点即最大值，并重新调整，即 
         	//依次选出次大的元素 
             $temp = $arr[0]; 
             $arr[0] = $arr[$end]; 
             $arr[$end] = $temp; 
             ajustNodes($arr, 0, $end - 1); 
         } 
     } 
     //初始化最大堆,从最后一个非叶子节点开始,最后一个非叶子节点编号为 数组长度/2 向下取整 
     function initHeap(&$arr) { 
         $len = count($arr); 
         for($start = floor($len / 2) - 1; $start >= 0; $start--) { 
             ajustNodes($arr, $start, $len - 1); 
         } 
     } 
     #调整节点 
     #@param $arr    待调整数组 
     #@param $start    调整的父节点坐标 
     #@param $end    待调整数组结束节点坐标 
     function ajustNodes(&$arr, $start, $end) { 
         $maxInx = $start; // 根节点 
         $len = $end + 1;    #待调整部分长度 
         $leftChildInx = ($start + 1) * 2 - 1;    #左孩子坐标 
         $rightChildInx = ($start + 1) * 2;    #右孩子坐标 
         #如果待调整部分有左孩子，调换左孩子与根节点，看哪个作为根节点 
         if($leftChildInx + 1 <= $len) { 
             #获取最小节点坐标 
             if($arr[$maxInx] < $arr[$leftChildInx]) { 
                 $maxInx = $leftChildInx; 
             } 
         } 
         #如果待调整部分有右子节点 ， 接上面的调整， 继续调换右孩子与根节点，看哪个作为根节点 
         if($rightChildInx + 1 <= $len) { 
             if($arr[$maxInx] < $arr[$rightChildInx]) { 
                 $maxInx = $rightChildInx; 
             } 
         } 
         // 上面调整结束后，根、左、右三个节点中，根节点一定是最大值 即maxInx是最大值的索引。 
         #交换父节点和最大节点 
         if($start != $maxInx) { 
             // 将最大值的节点调整为根节点 
             $temp = $arr[$start]; 
             $arr[$start] = $arr[$maxInx]; 
             $arr[$maxInx] = $temp; 
              
             #如果交换后的子节点还有子节点,继续调整 
             if(($maxInx + 1) * 2 <= $len) {// 依次反复 
                 ajustNodes($arr, $maxInx, $end); 
             } 
         } 
     } 
     $arr = array(35,66,2,15,6,81,6,9); 
     heapSort($arr); 
     print_r($arr); 

//*******************算法16：排列组合*******************

	/**
	 * 排列组合
	 * 采用二进制方法进行组合的选择，如表示5选3时，只需有3位为1就可以了，
	 *所以可得到的组合是 01101 11100 00111 10011 01110等10种组合
	 * @param 需要排列的数组 $arr
	 * @param 最小个数 $min_size
	 * @return 满足条件的新数组组合
	 */
	function plzh($arr,$size=5) {
	  $len = count($arr);
	  $max = pow(2,$len);
	  $min = pow(2,$size)-1;
	  $r_arr = array();
	  for ($i=$min; $i<$max; $i++){
	   $count = 0;
	   $t_arr = array();
	   for ($j=0; $j<$len; $j++){
		$a = pow(2, $j);
		$t = $i&$a;
		if($t == $a){
		 $t_arr[] = $arr[$j];
		 $count++;
		}
	   }   
	   if($count == $size){
		$r_arr[] = $t_arr;    
	   }   
	  }
	  return $r_arr;
	 }

	$pl = pl(array(1,2,3,4,5,6,7),5);
	var_dump($pl);
	
//*******************算法17： 斐波那契数列******************* 

	// 斐波那契数列 
	function findN($n) { 
	    if($n <= 2) return 1; 
	    return findN($n-2)+findN($n-1); 
	} 
	// 斐波那契数列的非递归形式 
	function findN1($n) { 
	    $arr = array(1,1); 
	    if($arr <= 2) return $arr; 
	    for($i = 2;$i < $n;$i++) { 
	        $arr[] = $arr[$i-1] + $arr[$i-2] ; 
	    } 
	    return implode(',',$arr); 
	} 
	print_r(findN1(7));exit; 
	for($i = 1;$i<=20;$i++) { 
	    echo findN($i); 
	    echo '<br>'; 
	} 
	exit;
	
//*******************算法18：已知字符串 $string = “2dsjfh87HHfytasjdfldiuuidhfcjh”;
//*******************找出 $string 中出现次数最多的所有字符。*******************

	$string = '2dsjfh87HHfytasjdfldiuuidhfcjh';
	$a = str_split($string);
	$b = array_count_values ($a);
	arsort($b);
	print_r($b);
	print_r(array_intersect($b, array(current($b))));
	———————–
	$count = 5;
	echo $count++;
	echo ++$count;
	结果是:57
	
//*******************算法19：遍历文件夹下面的所有目录和PHP文件;*******************

	define('DS', DIRECTORY_SEPARATOR);
	function breadth_first_files($from = '.') {
		$queue = array(rtrim($from, DS).DS);// normalize all paths
		$files = array();
		while($base = array_shift($queue )) {
			//var_dump($queue);
			if (($handle = opendir($base))) {
			while (($child = readdir($handle)) !== false) {
				if( $child == '.' || $child == '..') {
					continue;
				}
				if (is_dir($base.$child)) {
					$combined_path = $base.$child.DS;
					array_push($queue, $combined_path);
				} else {
					if("php"==getextension($child))
					{
					$files[] = $base.$child;
					}
				}
			}
			closedir($handle);
			// else unable to open directory => NEXT CHILD
		}
		return $files; // end of tree, file not found
	}
	
//*******************算法20：获得文件扩展名;*******************
	
	function getextension($filename) {
		return substr(strrchr($filename, '.'), 1);
	}
	print_r(breadth_first_files(“E:/workspace/0zf-blog”));
	
//*******************算法21：写个function,把数组的个位,十位分别拆分成如下新数组*******************

	$data=array(23,22,45,28);
	$data=array(2,3,2,2,4,5,2,8);
	print_r(str_split(join(null, $data)));

//*******************算法22：多选分类修改，数据入库增、删、留数组。说明:$k 和$v 是一一对应*******************

	//方式一：
		//旧分类
		$a1=array(
			’1′=>’杂文’,
			’2′=>’小说’,
			’3′=>’散文’
		);
		//新分类
		$a2=array(
			’3′=>’散文’,
			’4′=>’诗歌’,
			’6′=>’文学’
		);
		foreach($a1 as $k => $v)
		{
		$have=”;
		foreach($a2 as $ks => $vs)
		{
		echo “$v==$vs..”;
		if($v==$vs)
		{
		$have=1;
		}
		if(!array_key_exists($ks,$a1))
		{
		$add[$ks]=$vs;
		}
		}
		if($have)
		{
		$bao[$k]=$v;
		}else
		{
		$del[$k]=$v;
		}
		}
		var_dump($add);//新增的记录
		var_dump($del);//删除的记录
		var_dump($bao);//保留的记录
	//方式二：无key
		//旧分类
		$a1=array(‘杂文’,'小说’,'散文’);
		//新分类
		$a2=array(‘散文’,'诗歌’,'文学’);
		foreach($a1 as $k => $v)
		{
		$have=”;
		foreach($a2 as $ks => $vs)
		{
		echo “$v==$vs..”;
		if($v==$vs)
		{
		$have=1;
		}
		if(!in_array($vs, $a1))
		{
		$add[$vs]=$vs;
		}
		}
		if($have)
		{
		$bao[]=$v;
		}else
		{
		$del[]=$v;
		}
		}
		var_dump($add);//新增的记录
		var_dump($del);//删除的记录
		var_dump($bao);//保留的记录
		
//*******************算法23：比较全的随机数（字）*******************

	class getRandstrClass{
		function getCode ($length = 32, $mode = 0) {
			switch ($mode) {
				case ’1′:
				$str = ’1234567890′;
				break;
				case ’2′:
				$str = ‘abcdefghijklmnopqrstuvwxyz’;
				break;
				case ’3′:
				$str = ‘ABCDEFGHIJKLMNOPQRSTUVWXYZ’;
				break;
				case ’4′:
				$str = ‘ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz’;
				break;
				case ’5′:
				$str = ‘ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890′;
				break;
				case ’6′:
				$str = ‘abcdefghijklmnopqrstuvwxyz1234567890′;
				break;
				default:
				$str = ‘ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890′;
				break;
			}
			$randString = ”;
			$len = strlen($str)-1;
			for($i = 0;$i < $length;$i ++){
				$num = mt_rand(0, $len);
				$randString .= $str[$num];
			}
			return $randString ;
		}
	}
	$code = new getRandstrClass();
	$length = 16;
	$mode = 1;
	$str = $code->getCode($length, $mode);
	echo $str;
	$code = NULL;
	
//*******************算法24：有两个数值型变量 $a ,$b ，请在不使用第二个变量的情况下交换它们的值*******************

	$a=3;
	$b=4;
	$a=$a+$b;
	$b=$a-$b;
	$a=$a-$b;
	
//*******************算法25：汉诺塔的PHP算法*******************

　　/* 
		汉诺塔（又称河内塔）问题是印度的一个古老的传说。开天辟地的神勃拉玛在一个庙里留下了三根金刚石的棒，第一根上面套着64个圆的金片，最大的一个在底下，其余一个比一个小，依次叠上去，庙里的众僧不倦地把它们一个个地从这根棒搬到另一根棒上，规定可利用中间的一根棒作为帮助，但每次只能搬一个，而且大的不能放在小的上面。解答结果请自己运行计算，程序见尾部。面对庞大的数字(移动圆片的次数)18446744073709551615，看来，众僧们耗尽毕生精力也不可能完成金片的移动。
		后来，这个传说就演变为汉诺塔游戏:
		1.有三根杆子A,B,C。A杆上有若干碟子
		2.每次移动一块碟子,小的只能叠在大的上面
		3.把所有碟子从A杆全部移到C杆上
		经过研究发现，汉诺塔的破解很简单，就是按照移动规则向一个方向移动金片：
		如3阶汉诺塔的移动：A→C,A→B,C→B,A→C,B→A,B→C,A→C
		此外，汉诺塔问题也是程序设计中的经典递归问题。
	*/
	$objMover = new Mover();
	$fromPlace = 'A';
	$toPlace = 'C';
	$assistancePlace = 'B';
	$objMover->move(3,$fromPlace,$toPlace,$assistancePlace);
	print_r($objMover->getMovedMessage());
	class Mover
	{
		protected $_tabMessage = array();
		public function __construct()
		{
		}
		/**
		* Enter description here…
		* @param unknown_type $N, the larger the number is, the heavier it is
		* @param unknown_type $fromPlace
		* @param unknown_type $toPlace
		* @param unknown_type $assistancePlace
		*/
		public function move($N,$fromPlace,$toPlace,$assistancePlace){
			if($N == 1)
			{
				$this->_tabMessage[] = “Move $N from $fromPlace to $toPlace”;
			}elseif($N > 1){
				$this->move(($N-1),$fromPlace,$assistancePlace,$toPlace);
				$this->_tabMessage[] = “Move $N from $fromPlace to $toPlace”;
				$this->move(($N-1),$assistancePlace,$toPlace,$fromPlace);
			}
		}
		public function getMovedMessage()
		{
			return $this->_tabMessage;
		}
	}
	function hanoi($n,$x,$y,$z){
		if($n==1){
			move($x,1,$z);
		}else{
			hanoi($n-1,$x,$z,$y);
			move($x,$n,$z);
			hanoi($n-1,$y,$x,$z);
		}
	}
	function move($x,$n,$z){
		echo 'move disk '.$n.' from '.$x.' to '.$z.'';
	}
	hanoi(10,'x','y','z');
	
//*******************算法26：猴子大王游戏*******************

	/*  一群猴子排成一圈，按1，2，…，n依次编号。
		然后从第1只开始数，数到第m只,把它踢出圈，从它后面再开始数，
		再数到第m只，在把它踢出去…，如此不停的进行下去，
		直到最后只剩下一只猴子为止，那只猴子就叫做大王。
		要求编程模拟此过程，输入m、n, 输出最后那个大王的编号。
		结果视图：
		z=0/i=0
		z=1/i=1
		z=2/i=2
		z=3/i=3
		Array ( [0] => 1 [1] => 2 [2] => 3 [3] => 5 [4] => 6 )
		z=0/i=3
		z=1/i=4
		z=2/i=0
		z=3/i=1
		Array ( [0] => 1 [1] => 3 [2] => 5 [3] => 6 )
		z=0/i=1
		z=1/i=2
		z=2/i=3
		z=3/i=0
		Array ( [0] => 3 [1] => 5 [2] => 6 )
		z=0/i=0
		z=1/i=1
		z=2/i=2
		z=3/i=0
		Array ( [0] => 5 [1] => 6 )
		z=0/i=0
		z=1/i=1
		z=2/i=0
		z=3/i=1
		Array ( [0] => 5 )
		King is:5
	*/
	function monkeyKing($n,$m){
		$monkeys=range(1,$n);
		$i=0;//取出时候的坐标
		$z=0;//数到M的时候停
		while(($mnum=count($monkeys))>1){
			if($i==$mnum){
				$i=0;
			}
			echo 'z='.$z.'/i='.$i.'';
			$z++;
			$i++;
			if($z==$m){
				array_splice($monkeys,–$i,1);
				$z=0;
				print_r($monkeys);
				echo "";
			}
		}
		return($monkeys[0]);
	}
	echo 'King is:'.monkeyKing(20,4);
	
//*******************算法27：翻牌游戏*******************

	/* 	1-52张扑克牌，初始都翻开朝上
		从2开始，以倍数为基础，将2翻一次，4翻一次，6翻一次。。。52翻一次
		从3开始，以倍数为基础，将3翻一次，6翻一次，9翻一次。。。48翻一次
		从4开始，以倍数为基础，将4翻一次，8翻一次，13翻一次。。。48翻一次
		….
		求最后朝上的牌有哪些？
	 */
	 /*
	  * Created on 2009-9-30
	  *
	  * To change the template for this generated file go to
	  * Window - Preferences - PHPeclipse - PHP - Code Templates
	*/
	class up{
		protected $max = 52;
		protected $min = 2;
		protected $rs = array(1);//结果集，第一张牌是朝上的
		function up(){}
		/* 循环得到2-52的整除数组
		*
		* Array
		(
		[2] => Array
		(
		[0] => 2
		)
		[3] => Array
		(
		[0] => 3
		)
		[4] => Array
		(
		[0] => 2
		[1] => 4
		)
		[5] => Array
		(
		[0] => 5
		)
		[6] => Array
		(
		[0] => 2
		[1] => 3
		[2] => 6
		)
		[7] => Array
		(
		[0] => 7
		)
		[8] => Array
		(
		[0] => 2
		[1] => 4
		[2] => 8
		)
		[9] => Array
		(
		[0] => 3
		[1] => 9
		)
		[10] => Array
		(
		[0] => 2
		[1] => 5
		[2] => 10
		)
		[11] => Array
		(
		[0] => 11
		)
		[12] => Array
		(
		[0] => 2
		[1] => 3
		[2] => 4
		[3] => 6
		[4] => 12
		)
		[13] => Array
		(
		[0] => 13
		)
		[14] => Array
		(
		[0] => 2
		[1] => 7
		[2] => 14
		)
		[15] => Array
		(
		[0] => 3
		[1] => 5
		[2] => 15
		)
		[16] => Array
		(
		[0] => 2
		[1] => 4
		[2] => 8
		[3] => 16
		)
		[17] => Array
		(
		[0] => 17
		)
		[18] => Array
		(
		[0] => 2
		[1] => 3
		[2] => 6
		[3] => 9
		[4] => 18
		)
		[19] => Array
		(
		[0] => 19
		)
		[20] => Array
		(
		[0] => 2
		[1] => 4
		[2] => 5
		[3] => 10
		[4] => 20
		)
		[21] => Array
		(
		[0] => 3
		[1] => 7
		[2] => 21
		)
		[22] => Array
		(
		[0] => 2
		[1] => 11
		[2] => 22
		)
		[23] => Array
		(
		[0] => 23
		)
		[24] => Array
		(
		[0] => 2
		[1] => 3
		[2] => 4
		[3] => 6
		[4] => 8
		[5] => 12
		[6] => 24
		)
		[25] => Array
		(
		[0] => 5
		[1] => 25
		)
		[26] => Array
		(
		[0] => 2
		[1] => 13
		[2] => 26
		)
		[27] => Array
		(
		[0] => 3
		[1] => 9
		[2] => 27
		)
		[28] => Array
		(
		[0] => 2
		[1] => 4
		[2] => 7
		[3] => 14
		[4] => 28
		)
		[29] => Array
		(
		[0] => 29
		)
		[30] => Array
		(
		[0] => 2
		[1] => 3
		[2] => 5
		[3] => 6
		[4] => 10
		[5] => 15
		[6] => 30
		)
		[31] => Array
		(
		[0] => 31
		)
		[32] => Array
		(
		[0] => 2
		[1] => 4
		[2] => 8
		[3] => 16
		[4] => 32
		)
		[33] => Array
		(
		[0] => 3
		[1] => 11
		[2] => 33
		)
		[34] => Array
		(
		[0] => 2
		[1] => 17
		[2] => 34
		)
		[35] => Array
		(
		[0] => 5
		[1] => 7
		[2] => 35
		)
		[36] => Array
		(
		[0] => 2
		[1] => 3
		[2] => 4
		[3] => 6
		[4] => 9
		[5] => 12
		[6] => 18
		[7] => 36
		)
		[37] => Array
		(
		[0] => 37
		)
		[38] => Array
		(
		[0] => 2
		[1] => 19
		[2] => 38
		)
		[39] => Array
		(
		[0] => 3
		[1] => 13
		[2] => 39
		)
		[40] => Array
		(
		[0] => 2
		[1] => 4
		[2] => 5
		[3] => 8
		[4] => 10
		[5] => 20
		[6] => 40
		)
		[41] => Array
		(
		[0] => 41
		)
		[42] => Array
		(
		[0] => 2
		[1] => 3
		[2] => 6
		[3] => 7
		[4] => 14
		[5] => 21
		[6] => 42
		)
		[43] => Array
		(
		[0] => 43
		)
		[44] => Array
		(
		[0] => 2
		[1] => 4
		[2] => 11
		[3] => 22
		[4] => 44
		)
		[45] => Array
		(
		[0] => 3
		[1] => 5
		[2] => 9
		[3] => 15
		[4] => 45
		)
		[46] => Array
		(
		[0] => 2
		[1] => 23
		[2] => 46
		)
		[47] => Array
		(
		[0] => 47
		)
		[48] => Array
		(
		[0] => 2
		[1] => 3
		[2] => 4
		[3] => 6
		[4] => 8
		[5] => 12
		[6] => 16
		[7] => 24
		[8] => 48
		)
		[49] => Array
		(
		[0] => 7
		[1] => 49
		)
		[50] => Array
		(
		[0] => 2
		[1] => 5
		[2] => 10
		[3] => 25
		[4] => 50
		)
		[51] => Array
		(
		[0] => 3
		[1] => 17
		[2] => 51
		)
		[52] => Array
		(
		[0] => 2
		[1] => 4
		[2] => 13
		[3] => 26
		[4] => 52
		)
		)
		*
		* */
		public function setp1()
		{
			for($i=$this->min;$i<=$this->max;$i++)
			{
				for($j=$this->min;$j<=$this->max;$j++)
				{
					if(0==$i%$j)
					{
					$arr[$i][]=$j;
					}
				}
			}
			return $arr;
		}
		/* 获得整除组合为偶数的牌
		*
		* 返回值：
		*
		Array
		(
		[0] => 1
		[4] => Array
		(
		[0] => Array
		(
		[0] => 2
		[1] => 4
		)
		)
		[9] => Array
		(
		[0] => Array
		(
		[0] => 3
		[1] => 9
		)
		)
		[16] => Array
		(
		[0] => Array
		(
		[0] => 2
		[1] => 4
		[2] => 8
		[3] => 16
		)
		)
		[25] => Array
		(
		[0] => Array
		(
		[0] => 5
		[1] => 25
		)
		)
		[36] => Array
		(
		[0] => Array
		(
		[0] => 2
		[1] => 3
		[2] => 4
		[3] => 6
		[4] => 9
		[5] => 12
		[6] => 18
		[7] => 36
		)
		)
		[49] => Array
		(
		[0] => Array
		(
		[0] => 7
		[1] => 49
		)
		)
		)
		* */
		public function execute($arr)
		{
			foreach($arr as $k =>$v)
			{
				if($this->setp3(count($v)))
				{
					$this->rs[$k][] = $v;
				}
			}
			return $this->rs;
		}
		//判断奇偶数
		public function setp3($num)
		{
			if(0==$num%2)
			{
				return true;
			}else
			{
				return false;
			}
		}
	}
	$arr = array();
	$up = new up();
	$arr = $up->setp1();
	//print_r($arr);
	print_r($up->execute($arr));

//*******************算法28：合并多个数组，不用array_merge()，思路：遍历每个数组，重新组成一个新数组。*******************

	function unionArray($a, $b) {  
		$re = array();  
		foreach ($a as $v) $re[] = $v;  
		foreach ($b as $v) $re[] = $v;  
		return $re;  
	}  
	print_r(unionArray(array(1,2,4,5,'s'), array(2,5,7,'c','d')));
	
//*******************算法29：把数字1-1亿换成汉字表述，如：123->一百二十三。*******************

	function intToCnstr($intval) {  
		$cnNum = array('零','一','二','三','四','五','六','七','八','九');  
		$cnUnit = array('','十','百','千','万','亿');  
		$reCnStr = '';  
		$intval = intval($intval);  
		if ($intval < 10 && $intval >= 0) {  
		   $reCnStr .= $cnNum[$intval];  
		} elseif ($intval == 1000000000) {  
		   $reCnStr .= $cnNum[1].$cnUnit[5];  
		} elseif ($intval < 0 || $intval > 1000000000) {  
		   $reCnStr .= '';  
		} else {  
		   $str = strval($intval);  
		   $len = strlen($str);  
		   for ($i = 0; $i < $len; $i++) {  
			if (intval($str{$i}) != 0) {  
				 $reCnStr .= $cnNum[intval($str{$i})];  
				 $j = $len - 1 - $i;  
				 if ($j < 5) {  
				  $reCnStr .= $cnUnit[$j];  
				 } elseif ($j >=5 && $j <= 8) {  
				  $reCnStr .= $cnUnit[$j - 4];  
				 }  
			} else {  
				if ($i > 0 && $str{$i} != $str{$i - 1}) $reCnStr .= $cnNum[0];  
			}  
		   }  
		}  
		return $reCnStr;  
	}  
	echo intToCnstr(9912016); 
	
//*******************算法30：php实现约瑟夫的环点名问题。*******************

		function rand_number($num)
		{
			return rand(0,$num);
		}
		 
		function outnumber($n,&$arr,$max)
		{
			$number=rand_number($max);
			if($number>$n) $number=$number%$n+1;
			 
			while(!empty($arr)){
			 
				if(!isset($i)) {unset($arr[$number]);var_dump($arr);}//删除元素
				 
				$i=$number;//需要索引才能找到下一个间距为新$number的元素
				 
				$number=rand_number($max);//生成下一个随机数
				if($number>$n) $number=$number%$n+1;
				 
				echo '下一个间距'.$number.'元素'.'<br>';
				 
				$count=0;
				$n=end($arr);
				while($count<$number){
					 
					if($i>=$n) $i=1;//最后一个元素则返回到第一个元素
					else $i++;
					 
					if(!isset($arr[$i])) {continue;}
						else {$count++;}//找到一个元素才++
				}
				unset($arr[$i]);
				var_dump($arr);
			}
		}
		 
		$max=100;
		$n=6;
		for($i=1;$i<=$n;$i++) $arr[$i]=$i;
		outnumber($n,$arr,$max);
		
//*******************算法31：线性表的删除（数组中实现）。*******************

	function delete_array_element($array , $i) 
	{ 
			$len =  count($array);  
			for ($j= $i; $j<$len; $j ++){ 
					$array[$j] = $array [$j+1]; 
			} 
			array_pop ($array); 
			return $array ; 
	}  

//------------------------ 
// PHP内置字符串函数实现 
//------------------------
	
//*******************算法32：字符串长度 。*******************

	function strlen ($str) 
	{ 
			if ($str == '' ) return 0; 
			$count =  0; 
			while (1){ 
					if ( $str[$count] != NULL){ 
							 $count++; 
							continue; 
					}else{ 
							break; 
					} 
			} 
			return $count; 
	}

//*******************算法33：截取子串。*******************
 
	function substr($str, $start,  $length=NULL) 
	{ 
			if ($str== '' || $start>strlen($str )) return; 
			if (($length!=NULL) && ( $start>0) && ($length> strlen($str)-$start)) return; 
			if (( $length!=NULL) && ($start< 0) && ($length>strlen($str )+$start)) return; 
			if ($length ==  NULL) $length = (strlen($str ) - $start); 
			 
			if ($start <  0){ 
					for ($i=(strlen( $str)+$start); $i<(strlen ($str)+$start+$length ); $i++) { 
							$substr .=  $str[$i]; 
					} 
			} 
			if ($length  > 0){ 
					for ($i= $start; $i<($start+$length ); $i++) { 
							$substr  .= $str[$i]; 
					} 
			} 
			if ( $length < 0){ 
					for ($i =$start; $i<(strlen( $str)+$length); $i++) { 
							$substr .= $str[$i ]; 
					} 
			} 
			return $substr; 
	} 

//*******************算法34：字符串翻转 。*******************
 
	function strrev($str) 
	{ 
			if ($str == '') return 0 ; 
			for ($i=(strlen($str)- 1); $i>=0; $i --){ 
					$rev_str .= $str[$i ]; 
			} 
			return $rev_str; 
	} 

//*******************算法35：字符串比较。*******************

	function strcmp($s1,  $s2) 
	{ 
			if (strlen($s1) <  strlen($s2)) return -1 ; 
			if (strlen($s1) > strlen( $s2)) return 1; 
			for ($i =0; $i<strlen($s1 ); $i++){ 
					if ($s1[ $i] == $s2[$i]){ 
							continue; 
					}else{ 
							return false; 
					} 
			} 
			return  0; 
	} 

//*******************算法36：查找字符串。*******************
 
	function  strstr($str, $substr) 
	{ 
			 $m = strlen($str); 
			$n = strlen($substr ); 
			if ($m < $n) return false ; 
			for ($i=0; $i <=($m-$n+1); $i ++){ 
					$sub = substr( $str, $i, $n); 
					if ( strcmp($sub, $substr) ==  0)  return $i; 
			} 
			return false ; 
	}

//*******************算法37：字符串替换。*******************

	function str_replace($substr , $newsubstr, $str) 
	{ 
			 $m = strlen($str); 
			$n = strlen($substr ); 
			$x = strlen($newsubstr ); 
			if (strchr($str, $substr ) == false) return false; 
			for ( $i=0; $i<=($m- $n+1); $i++){ 
					 $i = strchr($str,  $substr); 
					$str = str_delete ($str, $i, $n); 
					$str = str_insert($str,  $i, $newstr); 
			} 
			return $str ; 
	} 

//-------------------- 
// 自实现字符串处理函数
//-------------------- 


//*******************算法38：插入一段字符串。*******************

	function str_insert($str, $i , $substr) 
	{ 
			for($j=0 ; $j<$i; $j ++){ 
					$startstr .= $str[$j ]; 
			} 
			for ($j=$i; $j <strlen($str); $j ++){ 
					$laststr .= $str[$j ]; 
			} 
			$str = ($startstr . $substr  . $laststr); 
			return $str ; 
	} 
	
//*******************算法39：删除一段字符串。*******************

	function str_delete($str , $i, $j) 
	{ 
			for ( $c=0; $c<$i;  $c++){ 
					$startstr .= $str [$c]; 
			} 
			for ($c=( $i+$j); $c<strlen ($str); $c++){ 
					$laststr  .= $str[$c]; 
			} 
			 $str = ($startstr . $laststr ); 
			return $str; 
	} 
	
		
//*******************算法40：复制字符串。*******************

	function strcpy($s1, $s2 ) 
	{ 
			if (strlen($s1)==NULL || !isset( $s2)) return; 
			for ($i=0 ; $i<strlen($s1);  $i++){ 
					$s2[] = $s1 [$i]; 
			} 
			return $s2; 
	} 
			
//*******************算法41：连接字符串。*******************
 
	function strcat($s1 , $s2) 
	{ 
			if (!isset($s1) || !isset( $s2)) return; 
			$newstr = $s1 ; 
			for($i=0; $i <count($s); $i ++){ 
					$newstr .= $st[$i ]; 
			} 
			return $newsstr; 
	} 
				
//*******************算法42：简单编码函数（与php_decode函数对应）。*******************

	function php_encode($str) 
	{ 
			if ( $str=='' && strlen( $str)>128) return false; 
			for( $i=0; $i<strlen ($str); $i++){ 
					 $c = ord($str[$i ]); 
					if ($c>31 && $c <107) $c += 20 ; 
					if ($c>106 && $c <127) $c -= 75 ; 
					$word = chr($c ); 
					$s .= $word; 
			}  
			return $s;  
	} 
					
//*******************算法43：简单解码函数（与php_encode函数对应）。*******************

	function php_decode($str) 
	{ 
			if ( $str=='' && strlen($str )>128) return false; 
			for( $i=0; $i<strlen ($str); $i++){ 
					$c  = ord($word); 
					if ( $c>106 && $c<127 ) $c = $c-20; 
					if ($c>31 && $c< 107) $c = $c+75 ; 
					$word = chr( $c); 
					$s .= $word ; 
			}  
			return $s;  
	} 
						
//*******************算法44：简单加密函数（与php_decrypt函数对应）。*******************

function php_encrypt($str) 
{ 
         $encrypt_key = 'abcdefghijklmnopqrstuvwxyz1234567890'; 
         $decrypt_key = 'ngzqtcobmuhelkpdawxfyivrsj2468021359'; 
        if ( strlen($str) == 0) return  false; 
        for ($i=0;  $i<strlen($str); $i ++){ 
                for ($j=0; $j <strlen($encrypt_key); $j ++){ 
                        if ($str[$i] == $encrypt_key [$j]){ 
                                $enstr .=  $decrypt_key[$j]; 
                                break; 
                        } 
                } 
        } 
        return $enstr; 
} 
						
//*******************算法45：简单解密函数（与php_encrypt函数对应）。*******************

function php_decrypt($str) 
{ 
         $encrypt_key = 'abcdefghijklmnopqrstuvwxyz1234567890'; 
         $decrypt_key = 'ngzqtcobmuhelkpdawxfyivrsj2468021359'; 
        if ( strlen($str) == 0) return  false; 
        for ($i=0;  $i<strlen($str); $i ++){ 
                for ($j=0; $j <strlen($decrypt_key); $j ++){ 
                        if ($str[$i] == $decrypt_key [$j]){ 
                                $enstr .=  $encrypt_key[$j]; 
                                break; 
                        } 
                } 
        } 
        return $enstr; 
}  







 	

 
 
