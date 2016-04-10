/**
 * Created by zhan on 2016/4/9.
 */
var appModule = angular.module("ngApp",[]);
appModule.controller("ListCtrl",
    function($rootScope, $scope, $location, $http){
        $scope.nowTime = new Date();
        $scope.flag = 'shopDevel';      //页面url参数
        $scope.addUrl = 'shopdevel_code.php';   //点击添加时跳转的url地址
        $scope.addText = '添加店铺业务员';     //点击添加时跳转地址的显示文字
        if ($location.search().flag) {
            $scope.flag = $location.search().flag;
        }
        switch ($scope.flag) {
            case "shopDevel" :
                $scope.addUrl = 'shopdevel_code.php';
                $scope.addText = '添加店铺业务员';
                break;
            case "managerDevel" :
                $scope.addUrl = 'managerdevel_code.php';
                $scope.addText = '添加业务经理';
                break;
        }
        //url是相对于我们的html文件的
        $http({
            url: "data_list.php",
            method: "post",
            data: {
                "flag" : $scope.flag
            }
        }).success(function (data, header, config, status) {            //响应成功
            $scope.empty = data[0].empty;   //判断数据是否为空
            $scope.datas = data[1];         //获取到的有效用户信息列表
        }).error(function (data, header, config, status) {            //处理响应失败
            alert('参数错误！')
            window.location = "index.php";
        });
        $rootScope.title = "标题";
        $scope.goAdd = function(){
            var goUrl = $scope.addUrl;
            window.location = goUrl;
        }
        $scope.go = function(id){
            window.location = "edit.html?id=" + id;
        }
    }
)

//修改post方式 form格式传值
appModule.config(['$httpProvider', '$locationProvider', function($httpProvider, $locationProvider) {
    $locationProvider.html5Mode(true);
    // Use x-www-form-urlencoded Content-Type
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';


    var param = function(obj) {
        var query = '';
        var name, value, fullSubName, subName, subValue, innerObj, i;

        for (name in obj) {
            value = obj[name];

            if (value instanceof Array) {
                for (i = 0; i < value.length; ++i) {
                    subValue = value[i];
                    fullSubName = name + '[' + i + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            } else if (value instanceof Object) {
                for (subName in value) {
                    subValue = value[subName];
                    fullSubName = name + '[' + subName + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            } else if (value !== undefined && value !== null) {
                query += encodeURIComponent(name) + '='
                    + encodeURIComponent(value) + '&';
            }
        }

        return query.length ? query.substr(0, query.length - 1) : query;
    };
    // Override $http service's default transformRequest
    $httpProvider.defaults.transformRequest = [function(data) {
        return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
    }];
}]);

appModule.filter(
    'toTrusted', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        }
    }]
);