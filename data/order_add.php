<?php
//用途：接收order页面提交的数据，订单入库，返回入库状态
header('Content-Type:application/json; charset=UTF-8;');
$output = ["status"=>"0","reason"=>""];

//开始获取客户端数据
@$user_name = $_REQUEST['user_name'];
@$sex = $_REQUEST['sex'];
@$phone = $_REQUEST['phone'];
@$addr = $_REQUEST['addr'];
@$did = $_REQUEST['did'];

//客户端验证不可靠，如果用户禁用了浏览器js，所有的js验证都会失效
//入库前必须在服务器端做验证
if( $user_name === NULL ){
    $output['reason'] = '姓名不能为空！';
}else if( $sex === NULL ){
    $output['reason'] = '性别不能为空！';
}else if( $phone=== NULL ){
    $output['reason'] = '电话号码不能为空！';
}else if( $addr=== NULL ){
    $output['reason'] = '地址不能为空！';
}else if( $did === NULL ){
    $output['reason'] = '菜品编号不能为空！';
}

if($output['reason']){
    $output['status'] = 400;
    echo json_encode($output);
    return;
}

$order_time = time()*1000;//PHP中的time（）函数返回长整形秒数

//页面包含函数：在当前位置包含住另外的PHP页面
include('config.php');
//连接数据库
$conn = mysqli_connect(//mysqli是mysql的增强版
    $db_host,//主机名
    $db_user,//用户名
    $db_pwd,//密码
    $db_name,//数据库名
    $db_port//端口号
);
//测试连接数据库是否成功
//var_dump($conn);

//定义sql语句
$sql = "SET NAMES UTF8";
//使用执行函数，执行sql语句
mysqli_query($conn,$sql);
//定义sql语句:
$sql = "INSERT INTO doris_order VALUES(NULL,'$phone','$user_name','$sex','$order_time','$addr','$did')";
//执行sql语句，获取结果集
$result = mysqli_query($conn,$sql);

if($result){
    $output['status'] = 200;
    //返回刚刚执行成功的insert语句的自增编号
    $output['reason'] = mysqli_insert_id($conn);
}else{
    $output['status'] = 500;
    $output['reason'] = 'ERROR! 订单提交失败，服务器端错误，请检查您的SQL语句'.$sql;
}

echo json_encode($output);
?>