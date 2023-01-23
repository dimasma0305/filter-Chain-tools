<?php
include 'iconv_list.php';

error_reporting(E_ALL & ~E_WARNING);
ini_set("memory_limit", "-1");
set_time_limit(0);
// init.初始化目录和变量
if(!file_exists("./init")){
    file_put_contents('./init','abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM');
 }
 if(!is_dir('./res')){
    mkdir('./res');
}

if(!file_exists("./res/C")){
   file_put_contents('./res/C','convert.iconv.UTF8.CSISO2022KR'); #是所有链子的开始，是变异的基础，也是忘不掉的那个人
}

$input = './init';
$filter_str = 'convert.iconv.*';
$prev_str = ""; # 存储上一个成功的字符链
$op_all = ""; #一般是res中的链子当作种子
$op_all_max = 2000; #链的最大长度
$last_op = "";# 上一个拼接的链子
$init_value = file_get_contents($input);
$max_c_len = strlen($init_value) * 5;


function getseeds($dir){ //获取文件夹中的所有文件名
    $handler = opendir($dir);  
    while (($filename = readdir($handler)) !== false) 
    {
        if ($filename !== "." && $filename !== "..") 
        {  
            $files[] = $filename ;  
        } 
    }  
    closedir($handler);  
    return $files;
}
/*
    获取res文件夹中存在的字典作为基础种子进行再拼接
*/ 
function getRandomSeedFromDir($dir){ 
    $files = getseeds($dir);
    $r_t = rand(1,999999) % sizeof($files);
    $seed = file_get_contents($dir.'/'.$files[$r_t]);
    echo "[mutating from exist dic] ".$files[$r_t].": ".$seed."\n";
    return $seed;
}

while(1){ //这个死循环是fuzz的核心，通过不断的和陌生人(随机数对应的编码串)相识，孜孜不倦的寻找着属于她自己的爱情......啧，多么枯燥且无味（x。
    $tmp_str = "";
    $op = '';
    $rand_2 = rand(1,999999);
    $rand_3 = rand(1,999999);
    $icon1 = $iconv_list[$rand_2 % count($iconv_list)];
    $icon2 = $iconv_list[$rand_3 % count($iconv_list)];
    $op = str_replace('*',$icon1.'.'.$icon2,$filter_str); //拼接，相识，is code,is life(x
    
    $tmp_str = file_get_contents('php://filter/'.$op_all.(($op_all == "")?'':'|').$op.'|convert.base64-decode|convert.base64-encode|convert.iconv.UTF8.UTF7/resource='.$input); //将随机拼接好的字符规则进行利用读取并存储在$tmp_str中

    # print("Try fuzz "."php://filter/".$op_all.(($op_all == "")?'':'|').$op.'|convert.base64-decode|convert.base64-encode|convert.iconv.UTF8.UTF7/resource='.$input."\n"); 

    if(!$tmp_str){ //如果$tmp_str不存在（拼接之后不能生成）就跳过
        continue;
    }
    if($tmp_str === $prev_str){
        continue; //如果和上一次结果一样就跳过x
    }
    if(strlen($op_all)>$op_all_max){ //如果长度超过最大设定长度就置空
        $last_op = "";
        if(rand(1,999999)% 5 > 2){
            $op_all = "";
            continue;
        }
        $op_all = $op_all = getRandomSeedFromDir('./res/');
        continue;
    }
    if(strlen($tmp_str) > $max_c_len){
        $last_op = "";
        if(rand(1,999999)% 5 > 2){
            $op_all = "";
            continue;
        }
        $op_all = $op_all = getRandomSeedFromDir('./res/');
        
        continue;
    }
    $r = strstr($tmp_str,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",true);
    if($r === false){
        # print("Oh $r is non-compliance ! skip now! \n");
        continue;
    }
    preg_match_all("/([a-zA-Z0-9])/",$r, $res);
    if(sizeof($res[0])===strlen($r) && sizeof($res[0])==1 ){

            if(!file_exists("./res/".$r)){//空虚的内心似乎得到了眷顾，这是第一次她遇见的爱情，她欣然接受(指如果不存在则会直接创建)
                print("Got $r ".$op_all.(($op_all == "")?'':'|').$op."\n");
                file_put_contents("./res/" . $r, $op_all.(($op_all == "")?'':'|').$op);
            }
            else{//即使爱情已经存在，但她依然想最求更好的未来
                $size = strlen(file_get_contents("./res/".$r));
                if($size>strlen($op_all.(($op_all == "")?'':'|').$op)){//所以当她遇上更好的，会毅然的离开(指匹配到更优更短的串)
                    file_put_contents("./res/" . $r,  $op_all.(($op_all == "")?'':'|').$op);
                    print("Got Superior (of shorter length):$r ".$op_all.(($op_all == "")?'':'|').$op."\n");
                }
            }
            //否则她还是会一如既往的，向爱情献上忠诚。
            $last_op = "";
            if(rand(1,999999)% 5 > 2){
                $op_all = "";
                continue;
            }
            $op_all = $op_all = getRandomSeedFromDir('./res/');
            continue;
    }
    if($tmp_str === $init_value){
        $last_op = "";
        if(rand(1,999999)% 5 > 2){
            $op_all = "";
            continue;
        }
        $op_all = $op_all = getRandomSeedFromDir('./res/');
        continue;
    }
    else{
        $last_op = $op;
        $prev_str = $tmp_str;
        $op_all .= (($op_all == "")?'':'|').$op;
    }
}
?>
