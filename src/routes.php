<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zank\Application;

Application::any('/test', function () {
    $loggerSettings = $this->get('settings')['logger'];
    print_r($loggerSettings);exit;
    $user = \Zank\Model\User::find(1);

    var_dump($user->attachs);
})
->add(\Zank\Middleware\InitDb::class);

Application::any('/', \Zank\Controller\Api\Cms::class.':index')
->add(\Zank\Middleware\InitDb::class)
->add(\Zank\Middleware\ExceptionHandle2API::class);


Application::group('/api', function () {
    // index
    $this->any('', function (Request $request, Response $response): Response {
        $apiList = [
            '/api/sign' => '用户注册｜登陆',
            '/api/captcha' => '验证码',
            '/api/upload' => '上传相关',
            '/api/user' => '用户相关',
        ];
        $response->withJson($apiList);

        return $response;
    });

    // 用户注册｜登陆
    $this->group('/sign', function () {
        // 索引
        $this->any('', \Zank\Controller\Api\Sign::class);

        // 基本信息注册
        $this
            ->post('/up/base', \Zank\Controller\Api\Sign::class.':stepRegisterBase')
            ->add(\Zank\Middleware\Sign\Up\ValidateUserInviteCode::class)
            ->add(\Zank\Middleware\Captcha\ValidateByPhoneCaptcha::class)
            ->add(\Zank\Middleware\Sign\Up\ValidateUserByPhone::class)
            ->add(\Zank\Middleware\InitDb::class);

        // 登陆
        $this
            ->post('/in', \Zank\Controller\Api\Sign::class.':in')
            ->add(\Zank\Middleware\Sign\In\ValidateUserByPhone::class)
            ->add(\Zank\Middleware\InitDb::class);

        // 刷新token
        $this
            ->post('/refresh-token', \Zank\Controller\Api\Sign::class.':refreshToken')
            ->add(\Zank\Middleware\InitDb::class);
    });

    // CMS系统
    $this->group('/cms', function () {
        // 索引
        $this->any('', function (Request $request, Response $response): Response {
            $apiList = [
                '111'
            ];

            return $response->withJson($apiList);
        });

        // 帖子提交地址
        $this
            ->post('/dopost', \Zank\Controller\Api\Cms::class.':doPost')
            ->add(\Zank\Middleware\InitDb::class);
        //首页分页
        $this
            ->get('/{page:\d+}', \Zank\Controller\Api\Cms::class.':index')
            ->add(\Zank\Middleware\InitDb::class);
        //分类提交地址
        $this
            ->post('/addsort', \Zank\Controller\Api\Cms::class.':addSort')
            ->add(\Zank\Middleware\InitDb::class);
        //分类提交地址
        $this
            ->get('/findsort', \Zank\Controller\Api\Cms::class.':findSort')
            ->add(\Zank\Middleware\InitDb::class);   
        $this
            ->any('/view/{name}', function (Request $request,Response $response, $args) {
                
                return $this->view->render($response, 'profile.html.twig', [
                    'name' => $args['name']
                ]);
            })
            ->setName('hello')   
            ->add(\Zank\Middleware\InitDb::class); 
    });

    // 验证码相关
    $this->group('/captcha', function () {
        // 索引
        $this->any('', function (Request $request, Response $response): Response {
            $apiList = [
                '/api/captcha/phone/get/register' => '获取手机号码验证码',
                '/api/captcha/phone/has' => '验证手机号码验证码',
            ];

            return $response->withJson($apiList);
        });

        // 获取手机号码验证码
        $this
            ->post('/phone/get/register', \Zank\Controller\Api\Captcha\Phone::class.':get')
            ->add(\Zank\Middleware\Sign\Up\ValidateUserByPhone::class)
            ->add(\Zank\Middleware\InitDb::class);

        // 验证手机号码验证码
        $this
            ->post('/phone/has', \Zank\Controller\Api\Captcha\Phone::class.':has')
            ->add(\Zank\Middleware\Captcha\ValidateByPhoneCaptcha::class)
            ->add(\Zank\Middleware\InitDb::class);
    });


    // 上传附件相关
    $this
        ->group('/upload', function () {
            // 索引
            $this->any('', function (Request $request, Response $response) {
                $apiList = [
                    '/api/upload/attach' => '上传附件',
                    '/api/uplaod/avatar' => '上传头像',
                ];

                return $response->withJson($apiList);
            });

            // 上传附件
            $this
                ->post('/attach', \Zank\Controller\Api\Upload::class.':attach')
                ->add(\Zank\Middleware\AttachUpload::class);

            // 上传头像
            $this
                ->post('/avatar', \Zank\Controller\Api\Upload::class.':avatar')
                ->add(\Zank\Middleware\AttachUpload::class);
        })
        ->add(\Zank\Middleware\InitAliyunOss::class)
        ->add(\Zank\Middleware\AuthenticationUserToken::class)
        ->add(\Zank\Middleware\InitDb::class);

    // 用户相关
    $this
        ->group('/user', function () {
            // api 索引
            $this->any('', \Zank\Controller\Api\User::class);

            // change data
            $this
                ->post('/change', \Zank\Controller\Api\User::class.':changeDate')
                ->add(\Zank\Middleware\User\Change\Role::class)
                ->add(\Zank\Middleware\User\Change\Kg::class)
                ->add(\Zank\Middleware\User\Change\Height::class)
                ->add(\Zank\Middleware\User\Change\Age::class)
                ->add(\Zank\Middleware\User\Change\Username::class);
        })
        ->add(\Zank\Middleware\AuthenticationUserToken::class)
        ->add(\Zank\Middleware\InitDb::class);
})
->add(\Zank\Middleware\ExceptionHandle2API::class);

// 附件相关
Application::get('/attach/{id:\d+}[/{type:[0|1]}]', function (Request $request, Response $response, $args) {
    $attach = \Zank\Model\Attach::find($args['id']);

    // 先不用判断是非存在oss中，如果是迁移，可能也有可能回源的附件。
    if (!$attach/* || file_exists(($ossPath = 'oss://'.$attach->path)) === false*/) {
        return $response
            ->withStatus(404)
            ->write('Page not found.');
    }

    $url = attach_url($attach->path);

    if ((bool) $request->getAttribute('type') === true) {
        return with(new \Zank\Common\Message($response, true, '', $url))
            ->withJson();
    }

    return $response
        ->withStatus(307)
        ->withRedirect($url);
})
->add(\Zank\Middleware\InitAliyunOss::class)
->add(\Zank\Middleware\InitDb::class);
