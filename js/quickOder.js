angular.module('quickOder',['ng','ngRoute','ngAnimate'])
    .controller('parentController',function($scope,$location){
        $scope.headerFileName = 'tpl/header.html';
        $scope.footerFileName = 'tpl/footer.html';
        $scope.jump = function(url){
            $location.path(url);
        }
    })
    .controller('startController',function($scope,$interval,$timeout,$location){
        $scope.countdown = 5;
        var stop=$interval(function(){
            $scope.countdown--;
            if($scope.countdown == 0){
                $location.path('/main');
                $interval.cancel(stop);
            }
        },1000);

    })
    .controller('mainController',function($scope,$http){
        //声明hasMore变量，保存是否有更多数据
        $scope.hasMore = true;

        //一面已呈现就要向服务器端请求菜品列表数据
        $http.get('data/dish_getbypage.php?start=0')
            .success(function(data){
                // console.log('读取到服务器端返回数据:');
                // console.log(data);
                $scope.dishList = data;
            });
        //为加载更多按钮绑定click事件
        $scope.loadMore = function(){
            $http.get('data/dish_getbypage.php?start='+$scope.dishList.length)
                .success(function(data){
                    //console.log(data);
                    if(data.length<5){
                        $scope.hasMore = false;
                    }
                    //concat()方法不改变原数组，返回值才是拼接后的新数组，所以要将返回值赋值给dishList
                    $scope.dishList = $scope.dishList.concat(data);
                });
        };
        //当搜索关键字发生改变，立即向服务器发起请求
        $scope.$watch('kw',function(){
            //关键字不为空
            if($scope.kw){
                //发起请求
                $http.get('data/dish_getbykw.php?kw='+$scope.kw).success(function(data){
                    $scope.dishList = data;
                });
            }
        });

    })
    .controller('detailController',function($scope,$routeParams,$http){
        //console.log($routeParams);
        $http.get('data/dish_getbyid.php?did='+$routeParams.did).success(function(data){
            //console.log(data[0]);
            $scope.dish = data[0];
        })
    })
    .controller('orderController',function($scope,$routeParams,$http){
        $scope.order = {did:$routeParams.did};//用于封装用户的输入

        //测试数据
        $scope.order.user_name = 'testUser';
        $scope.order.sex = 1;
        $scope.order.phone = '13888888888';
        $scope.order.addr = '朝阳区';
        //测试数据结束

        var result = jQuery.param($scope.order);
        //console.log(result);

        $scope.submitOrder = function(){
            console.log($scope.order);
            $http.get('data/order_add.php?'+result).success(function(data){
                console.log(data);
                $scope.status = data;

            })
        };
    })
    .controller('myOrderController',function($scope,$http){
        $http.get('data/order_getbyphone.php?phone=13501234567').success(function(data){
            //console.log(data);
            $scope.orders = data;
        })
    })
    .config(function($routeProvider){
        $routeProvider
            .when('/start',{
                templateUrl:'tpl/start.html',
                controller:'startController'
            })
            .when('/main',{
                templateUrl:'tpl/main.html',
                controller:'mainController'
            })
            .when('/detail/:did',{
                templateUrl:'tpl/detail.html',
                controller:'detailController'
            })
            .when('/order/:did',{
                templateUrl:'tpl/order.html',
                controller:'orderController'
            })
            .when('/myOrder',{
                templateUrl:'tpl/myOrder.html',
                controller:'myOrderController'
            })
    });