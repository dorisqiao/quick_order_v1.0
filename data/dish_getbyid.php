<?php
    //用途：根据客户端提交的菜品编号，为detail页面返回一道菜品的所有数据
    header('Content-Type:application/json; charset=UTF-8;');
    $output = [];

    @$did = $_REQUEST['did'];
    if($did === NULL){//若客户端没有提交菜品编号did，直接返回json格式的空数组
        echo '[]';
        return;//退出函数
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
    //定义sql语句:SELECT 字段1, 字段2…… FROM 表名称 WHERE 字段1='值1'; 利用本语句可实现在数据库精确查询
    $sql = "SELECT did,name,img_lg,price,material,detail FROM doris_dish WHERE did=$did";
    //执行sql语句，获取结果集
    $result = mysqli_query($conn,$sql);
    //从结果集中依次获取每行，并以关联数组返回每行
    $row = mysqli_fetch_assoc($result);
    //根据主键，精确查询结果只有一条记录，直接压入结果数组即可
    $output[] = $row;

    echo json_encode($output);
?>