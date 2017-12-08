# 接入说明

## 四种授权模式
客户端必须得到用户的授权(authorization grant),才能获得令牌(access token),OAuth2.0定义了四种授权方式.
+ 授权码模式(authorization code)
+ 简化模式(implicit)
+ 密码模式(resource owner password credentials)
+ 客户端模式(client credentials)

## 代办事项(TODO)
+ 简化模式
+ 密码模式
+ 客户端模式

## 样例
([完整例子](https://github.com/gansutianqi/auth-tianqi-sample))

#### 授权码模式(authorization code)
 授权码模式(authorization code)是功能最完整,流程最严密的授权模式.特定是通过客户端的后台服务器,与"服务提供者"的认证服务器进行互动.
 具体步骤:

 1. 用户访问客户端,后者将前者导向认证服务器
 2. 用户选择是否给予客户端授权
 3. 假设用户给予授权,认证服务器将用户导向客户端事前指定的重定向URI(redirection URI),同时附上一个授权码
 4. 客户端受到授权码,附上早先的重定向URI,向认证服务器申请令牌.这一步通常是在客户端的后台服务器上完成的,对用户不可见.
 5. 认证服务器核对授权码和重定向URI,确认无误后,向客户端发送访问令牌(access token)和更新令牌(refresh token)

 该模式下请求认证URI包含的参数有:
 + response_type: 表示授权类型,必须填写,此处的值固定为`code`
 + client_id: 表示客户端的ID,必填项
 + redirect_uri: 表示重定向URI,可选项
 + scope: 表示申请的权限范围,可选项
 + state: 表示客户端当前状态,可指定任意值,认证服务器会原封不动的返回该值

```php
// 配置
$client_id = 2;
$client_secret='fXH23LuWi244x4vgdaUaXAoAEfjZZFSAoCdfg7bF';
$auth_uri = 'http://tianqi-user-center.app/oauth/authorize?';
$redirect_uri = 'http://localhost/auth-tianqi-sample/authorization-code/callback.php';
$auth_get_token_uri='http://tianqi-user-center.app/oauth/token';

$query = http_build_query([
    'response_type' => 'code',
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'scope' => ''
]);
// 将用户导向认证服务器,附带必要参数
header('Location:' . $auth_uri . $query);
```

上面的第三步服务器回应客户端的URI中包含参数: 
+ code : 表示授权码,必选项,该码的有效期比较短10分钟左右
+ state : 可选项,如果客户端请求中包含这个参数,认证服务器的回应也必须一模一样的包含该参数

```
// 用户选择认证后
// 服务器会返回如下url
// http://localhost/client01/callback.php?code=f1Tl3mBjQ71XK%2BFFHw40Z9r%2FfeCWWC6nIkUMQi864mjiiAMDIwOfnXZkkTCIs2lfBGav6WcSVhtSE22MrGKz0sOups%3D
```

上面的第四步中,客户端向认证服务器申请令牌的请求,包含以下参数:
+ grant_type : 表示授权的授权码模式,必选项,此处的固定值为"authorization_code"
+ code : 上一步获取的授权码,必选项
+ redirect_uri : 表示重定向URI,必选项,与第一步中的redirect_uri参数一致
+ client_Id : 表示客户端ID,必选项

```php
require ('vendor/autoload.php');
// 配置
$client_id = 2;
$client_secret='fXH23LuWi244x4vgdaUaXAoAEfjZZFSAoCdfg7bF';
$auth_uri = 'http://tianqi-user-center.app/oauth/authorize?';
$redirect_uri = 'http://localhost/auth-tianqi-sample/authorization-code/callback.php';
$auth_get_token_uri='http://tianqi-user-center.app/oauth/token';

$code=trim($_GET['code']);
// 使用guzzle扩展来发起一个POST来获取access_token
$http=new \GuzzleHttp\Client();
$response=$http->post($auth_get_token_uri,[
    'form_params' => [
        'grant_type' => 'authorization_code',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'code' => $code,
    ],
]);
echo $response->getBody();
```
第五步,认证服务器发送http回复,包含如下参数:
+ access_token : 表示访问令牌,必选项
+ token_type : 表示令牌类型,大小写不敏感,必选项
+ expires_in : 表示过期时间,单位为秒
+ refresh_token : 表示更新令牌,用来获取下次的访问令牌,可选项
+ scope : 表示权限范围

```
// 返回的数据如下
{"token_type":"Bearer","expires_in":31536000,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjRhYjI2YTUxNjRhZDc0ZjhmNWQ1YTkxZWEzNTZiMjM1NThkYzRjYmE0NTc3MmZmZDY4YWM0ZmMxNmQ5NDcwNWY4ZTk3MGMyNjVkNjgyOTBhIn0.eyJhdWQiOiIyIiwianRpIjoiNGFiMjZhNTE2NGFkNzRmOGY1ZDVhOTFlYTM1NmIyMzU1OGRjNGNiYTQ1NzcyZmZkNjhhYzRmYzE2ZDk0NzA1ZjhlOTcwYzI2NWQ2ODI5MGEiLCJpYXQiOjE0ODk5MjQyNjcsIm5iZiI6MTQ4OTkyNDI2NywiZXhwIjoxNTIxNDYwMjY3LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.bqMo8nzOxnEWVZ4zo2JS1opbUdvw0W6-XZdP48ntsoLD6qjUpsqkSUo-4pfnVBHkqPSEEQeVx4YWsGJCebOjd9y3YOScqfecwOTidTnaRgHYBtVNq9SNYvOQbnpWeEhiZtKLJPe1KexTRtblrE_9eNkpww6GF7KCP3auKi3gbHDqVE4liPAhGLne3S3cwBkrjSB0FC2ERo-l_98qV8-Zk5cOZ9JvhVLBryrD80BbTTdVyCrhqMzSkvAqA7okU99RegU0H70uzJa585oWC8DMHnGP1YhnMzeLQWPY4A0Hdn4ax0FIDAUySfsVb0e63HnoA7MXB5aBPdjqYSNsO9Xp5w8jT-AW5DunbDYvitpi7ciJEXTNwB4gMrC1oivSz0z3lWsc8KD_EP4Py6fd9v9eCZVwpAnAp79SPXzbUf6ZeAxWEAIYCc3VJlRwLAXvVmtCLPcfkJ7oMB9gcN5Lw1cpNc7JUSeyouxbFutY-qhpIXVSOepldVakuP50Mv5vkmuItsWAtYMPE7b4tOH6sFXNoTUsVKcKD_IR7xUHOvAbYPEOUvdY26pANwELzBKwRIV6abGPZ6iwes9L4_VsliqUIlznes4CAoPQpJnqI8xQ3tNcRjAvn-OZrZeF1Ms-Pgyouh3WZ1DtaFVGgeEDM6IDBsOngVSYfnsptNc5BNWGRQw","refresh_token":"J+txjxaIAuedp7acmB0NDJaykxFVp88tkkdLWmIlqRE8Cn1g+j4sydneRPoHXDxn3lBJwbrkA6rEWLlR40EpcXESrr3Vl6TUQKLSM0BoLl9HberWZZS4Ay57aEMgLmcAz39Stgb9SYRWp33LppFxfsdjgMXL91L\/7Fw2kZxjn56XW8ZGgkB0beE19MEdwgBw1QHuulEKemVbtFPmvVK1DfaO4jMym5NTh84WmLORkluOQSmxFpATILnwUyaTLQtqetHJF49acS8pXTcoYdAFKAU48b+lrWQsW8ZAUAeA3OnM5xQDgJUy+GTnl+Ad2nu5CoQJ9hQKH+8nDPiJXPgKkFmEnI1ThR48rOW2bPBDbk0E7qxFLuKPqJhf3zP\/BneYeucTJnpjN43qruIOfpNQtdr9J6X7Z1ydgMT9xl1RIepJ6+qrjw0TDnOqmBJ7Drf2RgP91zgmx5DibFFZkfQ3mEmOnEl5t9Ny676jkRQthaDSBb9Gr+3UET\/kQIU+Zk2FJnjuJ2\/xZpZp+HcjIH1QewdTtCs9c0RFbMqtQ+Rm7PZmdcT+qUayehxaYUi96K3uQhKsQXLLVsslNJS3EyRCW5gUoKzTplc12CVZJMUroPedavQpsnzHq0yd+xHUenXgRO3XZkL+\/kzqYcTUrdjVCJ+EGa5s9fmBO40swBA\/Qic="}
```

#### 简化模式(implicit)
简化模式(implicit grant type)不通过第三方应用程序的服务器,直接在浏览器中向认证服务器申请令牌,跳过了"授权码"这个步骤.所有步骤在浏览器中完成,令牌对访问者是可见的,且客户端不需要认证.
1. 客户端将用户导向认证服务器
2. 用户决定是否给予客户端授权
3. 假设用户给予授权,认证服务器将用户导向客户度指定的重定向URI,并在URI的Hash部分包含了访问令牌
4. 浏览器向资源服务器发出请求,其中不包括上一步收到了Hash值
5. 资源服务器返回一个网页,其中包含的代码可以获取Hash值中的令牌
6. 浏览器执行上一步获到的脚本,提取出令牌
7. 浏览器将令牌发送给客户端

第一步中客户端发出的参数包括:
+ response_type: 表示授权类型,此处固定值为`token`,必选项
+ client_id: 表示客户端ID,必选项
+ redirect_uri:表示重定向的URI,可选项
+ scope: 表示权限范围,可选项
+ state: 表示客户端状态,可以是任意值,认证服务器会原封不动地返回这个值

```
// index.php
// 配置
$client_id = 4;
$client_secret = 'n6zVH4ufV29acCbeZgGflThYFf7izZHGHwx63JnB';
$auth_uri = 'http://tianqi-user-center.app/oauth/authorize?';
$redirect_uri = 'http://localhost/auth-tianqi-sample/implicit-grant/callback.php';

$query = http_build_query([
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'response_type' => 'token',
    'scope' => '',
]);

header('Location:'.$auth_uri.$query);
```

第三步中认证服务器回应客户端的URI,包括的参数
+ access_token 表示访问令牌,必选项
+ token_type 表示令牌类型,大小写不敏感,必选项
+ expires_in 表示过期时间,单位为秒,如果省略该参数,必须其他地方设置过期时间
+ scope 表示权限范围,如果与客户端申请的范围一致,可省略
+ state 如果客户端的请求中包含该参数,认证服务器的回应也必须一模一样包含该参数

```
// 如下所示
http://localhost/auth-tianqi-sample/implicit-grant/callback.php#access_token=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImQyNGVhMmRlNWE1MmVhYTYzYTU3YTgxMTgyOTNjNjg3MTIzNjhlYWJhOWMwOGIwODBlZTg2MTRkMzQ1ODJlZTE3NzAxYmQ5YTE4ZThjZTUzIn0.eyJhdWQiOiI0IiwianRpIjoiZDI0ZWEyZGU1YTUyZWFhNjNhNTdhODExODI5M2M2ODcxMjM2OGVhYmE5YzA4YjA4MGVlODYxNGQzNDU4MmVlMTc3MDFiZDlhMThlOGNlNTMiLCJpYXQiOjE0OTAxNzE1OTUsIm5iZiI6MTQ5MDE3MTU5NSwiZXhwIjoxNTIxNzA3NTk1LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.DNP8iSDtS5a4tk2gGZct2u1cI7q0ovccoWeRqd_UT5YllIlVkl1wQXFEpyJH-JOSlsGNpOVFePuri7Zx06OEso-k_vTld-DDbgG0KsYAS8-WWt02tGmryCcKhWObbTdgHY9ylMk_su4jaFDz7HBp6o9WEXs8Ug--EomoUBHGgqZO2ShgRKSCX1vq8R16ZDt0XbRWwzkefZUcLi8ibXw6bnMnJjCHy8ROCRGRaWnuTiumnbQ0cz5tZ7WwxHpZ5rlwkr3cWQDYqhNiCLCWOysQvmmZQo41cy-kF1t66M5psm3E-5_js5WcRwSgGNpQNeqPOZIyFj2PwN1k-t8wbnPwjdH9FX8Ww750zGn_3LJqUjiT4gNvsqjBKF3OjkdZMPfQVD8yBU7VgWnxu7GWDjkApW3qUK4EuLxp0DnLaJhgMnZN6-qVZ6Z33ZO8WZbD4vXaTb9J8dSpCcqbrI_YZPuIepGm2gNJVKzIaai_sO5jSHd3jL_uSWfEs-ozM-g5-hiazitlDiK0-zK1JLpO95CDrwpMuhKGPlXUb31PVcCHM7y-XhcLMNw-g1WK5C2r1HZmvo0o1oyMrLCLfNTGni2E7V7VxpQeRC-t1pLgeURrNnZRk1PJ8btB_vvUGyMBGozChxlXpLPeD5Gjjzte5K4F4ocTgbyoxWULPUlih2hXwE8&token_type=bearer&expires_in=31536000
```
认证服务器会返回如上的url,注意在这个网址的Hash部分包含了令牌

如何提取token?下面的例子中包含了一种办法
```html
// callback.php
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>test implicit grand</title>
</head>
<body>

</body>
<script>
    // 解析URL中hash
    function parseToken() {
        var hash, out = {};
        hash = window.location.hash || '';
        hash = hash.substring(1).split('&');

        for (var i = 0; i < hash.length; i++) {
            var arr = hash[i].split('=');
            if (arr[0] == 'access_token') {
                out.access_token = arr[1];
            } else if (arr[0] == 'token_type') {
                out.token_type = arr[1];
            } else if (arr[0] == 'expires_in') {
                out.expires_in = arr[1];
            }
        }
        return out;
    }
    var token = parseToken();
    console.log(token);
</script>
</html>

```

#### 密码模式(resource owner password credentials grant)
在密码模式中,用户向客户端提供自己的用户名和密码,客户端使用这些信息,向服务提供商索要授权.
在这种模式中,用户必须把自己的密码给客户端,但是客户端不得储存密码.这通常用在用户对客户端高度信任的情况下.
1. 用户向客户端提供用户名和密码
2. 客户端将用户名和密码发给认证服务器,向后者请求令牌
3. 认证服务器确认无误后,向客户端提供访问令牌

第二步中客户端发出的HTTP请求包括以下参数:
+ grant_type 表示授权类型,此处固定值为`password`,必须选
+ client_id 表示客户端ID
+ client_secret 客户端密码
+ username 表示用户名,必须选
+ password 表示用户的面目,必须选
+ scope 表示权限范围,可选项

```php
require ('../vendor/autoload.php');

// 配置
$client_id = 7;
$client_secret = 'fvqtEYIfWWxfkVU4sod2RtaZx0kl2a6ABPiUBM3m';
$username='twice@qq.com';
$password='secret';
$auth_get_token_uri='http://tianqi-user-center.app/oauth/token';

$http=new \GuzzleHttp\Client;

$response=$http->post($auth_get_token_uri,[
    'form_params' => [
        'grant_type' => 'password',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'username' => $username,
        'password' => $password,
        'scope' => '',
    ],
]);

$response=json_decode((string)($response->getBody()),true);

$access_token=$response['access_token'];

$user=$http->get('http://tianqi-user-center.app/api/user',[
    'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.$access_token,
    ]
]);
echo $user->getBody();
```


#### 客户端模式(client credentials)
客户端模式指定客户端以自己的名义,而不是以用户的名义,想服务提供商进行认证.在这种模式中,用户直接向客户端注册,客户端以自己的名义要求服务提供商提供服务,其实不存在授权问题.
1. 客户端向认证服务器进行身份认证,并要求一个访问令牌
2. 认证服务器确认无误后,向客户端提供访问令牌

第一步中包含的参数有:
+ grant_type 授权模式,固定值为`client_credentials`
+ client_id 客户端ID
+ client_secret 客户端密码
+ scope 权限范围,可选项

```php
require('../vendor/autoload.php');
// 配置
$client_id = 4;
$client_secret = 'n6zVH4ufV29acCbeZgGflThYFf7izZHGHwx63JnB';
$auth_get_token_uri='http://tianqi-user-center.app/oauth/token';

$http = new \GuzzleHttp\Client();

$response = $http->post($auth_get_token_uri, [
    'form_params' => [
        'grant_type' => 'client_credentials',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'scope' => '',
    ],
]);

$response=json_decode((string)$response->getBody(),true);

$access_token=$response['access_token'];

echo $access_token;
```

#### 更行令牌(Refresh Token)
如果用户访问的时候,客户端的访问令牌已经过期,则需要使用更新令牌来申请一个新的访问令牌
客户端发出一个更新令牌的HTTP请求,包括的参数有:
+ grant_type 授权类型,固定值为`refresh_token`
+ refresh_token 早前收到的更新令牌
+ client_id 客户端ID
+ client_sercret 客户端面膜
+ scope 权限范围,可选项

```php
require('../vendor/autoload.php');

// 配置
$client_id = 7;
$client_secret = 'fvqtEYIfWWxfkVU4sod2RtaZx0kl2a6ABPiUBM3m';
$username='twice@qq.com';
$password='secret';
$auth_get_token_uri='http://tianqi-user-center.app/oauth/token';

$http = new \GuzzleHttp\Client;

$response = $http->post($auth_get_token_uri, [
    'form_params' => [
        'grant_type' => 'password',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'username' => $username,
        'password' => $password,
        'scope' => '',
    ],
]);

$response = json_decode((string)($response->getBody()), true);
$access_token = $response['access_token'];
$refresh_token = $response['refresh_token'];

echo <<<HTML
<h4>原始access_token</h4>
<p>{$access_token}</p>
<h4>原始refresh_token</h4>
<p>{$refresh_token}</p>
HTML;

//
// 获取用户信息
//
$user = $http->get('http://tianqi-user-center.app/api/user', [
    'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $access_token,
    ]
]);
echo <<<HTML
<h4>用户信息</h4>
<p>{$user->getBody()}</p>
HTML;

//
// 刷新access_token
//
$responseNew = $http->post($auth_get_token_uri, [
    'form_params' => [
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'scope' => '',
    ]
]);

$responseNew=json_decode((string)$responseNew->getBody(),true);
$access_token_new=$responseNew['access_token'];

echo <<<HTML
<hr>
<h4>刷新后的access_token</h4>
<p>{$access_token_new}</p>
HTML;

```


