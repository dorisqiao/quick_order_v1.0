<?php
//用途：根据用户输入查询关键字，返回所有相关的菜品数据
header('Content-Type:application/json; charset=UTF-8;');
$output = [];

@$kw = $_REQUEST['kw'];
if($kw === NULL){
    echo '[]';//如果用户没有提交查询关键字，直接返回json格式的空数组
    return;//直接退出
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
//定义sql语句:SELECT 字段1, 字段2…… FROM 表名称 WHERE 字段1 LIKE '%关键字%' OR 字段2 LIKE '%关键字%'; 利用本语句可实现在数据库模糊查询
$sql = "SELECT did,name,img_sm,price,material FROM doris_dish WHERE name LIKE '%$kw%' OR material LIKE '%$kw%'";
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