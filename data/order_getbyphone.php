<?php
//用途：为myOrder页面返回指定电话号的所有订单数据
header('Content-Type:application/json; charset=UTF-8;');
$output = [];

@$phone = $_REQUEST['phone'];//$_REQUEST是一个数组，@用于压制当前行代码可能出现的错误
if($phone===NULL){//若客户端没有提交，则返回空数组
    echo '[]';
    return;
}

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
//跨表查询语句为：SELECT 表1.字段1, 表1.字段2……, 表2.字段1, 表2.字段2…… FROM 表1, 表2 WHERE 字段1=值1 AND 表1.共有字段=表2.共有字段;
$sql = "SELECT oid,user_name,order_time, doris_dish.did,img_sm,name FROM doris_order,doris_dish WHERE phone=$phone AND doris_order.did=doris_dish.did";
//执行sql语句，获取结果集
$result = mysqli_query($conn,$sql);
//从结果集中依次获取每行，并以关联数组返回每行
//$row = mysqli_fetch_assoc($result);
//如果$row不为空，则循环将每行压入$output中
while( ($row = mysqli_fetch_assoc($result)) != NULL ){
    $output[] = $row;
}

echo json_encode($output);
?>