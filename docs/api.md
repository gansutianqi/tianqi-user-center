## API

All examples of below is use Guzzle as a HTTP client for request a uri, before get started make sure the `guzzlehttp/guzzle` dependency is installed or use your favorite HTTP client.  

If you want experience with our API you can create a Personal Access Token, it without through the typical authorization code redirect flow.

#### get user info
+ URI `http://user.tq0.com/api/user`
+ HTTP method `get`
+ need authorization `Authorization: Bearer YOUR_TOKEN`
+ description, get user information
+ return format`json`
```json
{
  "id":14,
  "name":"buuug7",
  "email":"20639@qq.com",
  "created_at":{
    "date":"2017-12-11 15:57:05.000000",
    "timezone_type":3,
    "timezone":"PRC"
    },
  "avatar_url":"http:\/\/tianqi-user-center.test\/storage\/avatars\/OBx917p1leNi5oruDT3iTwSBXVkRhLcUmhySWCfH.png",
  "location":"Lanzhou",
  "website":"http:\/\/tianqi-user-center.test",
  "bio":"Libero repellendus eos et aspernatur maiores saepe eos."
  }
```  
+ example
```php
use GuzzleHttp\Client;

$token = 'YOUR_TOKEN'; // or personal access token
$userApi = 'http://user.tq0.com/api/user';
$http = new Client();
$response = $http->get($userApi, [
    'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
        ],
    ]);
$result =  json_decode($response->getBody());
```  