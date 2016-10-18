<?php

namespace Zank\Controller\Api;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zank\Controller;
use medz\Component\Pinyin\Pinyin;
class Cms extends Controller
{


    public function index(Request $request, Response $response, $args)
    {   //$cms = $request->getHeaderLine('Cms');
        $page =  isset($args['page']) ? intval($args['page']) : 1;
        if ($page > 1) 
        {   
            $cmss = \Zank\Model\Cms::pageData($page);
            $cmsdata = $cmss->toArray();
            $html = \Zank\Util\SourceModel::getInstance()->getSourceInfo($cmsdata,'cms_ajax');
            return $html;
        }
        //获取分类
        $cmssorts = \Zank\Model\Cmssort::getAllSort();
        $sortdata = $cmssorts->toArray();
        //获取内容
        $cmss = \Zank\Model\Cms::pageData();
        $total = \Zank\Model\Cms::$total;
        $cmsdata = $cmss->toArray();
        $html = \Zank\Util\SourceModel::getInstance()->getSourceInfo($cmsdata,'cms');
       
        return $this->ci->view
                        ->render($response, 'cms/index.html.twig', [
                            'sortdatas' => $sortdata,
                            'htmls'  => $html,
                            'total' => $total
                        ]);
        unset($html);
    }
   /**
    * 发帖
    *
    * @return void
    * @author 
    **/
   public function doPost(Request $request, Response $response)
    {
        $title = $request->getParsedBodyParam('title');
        $content = $request->getParsedBodyParam('content');
        $sort_id = $request->getParsedBodyParam('sort_id');

        $title = t($title);
        $content = RemoveXSS($content);
        $content = h($content);
        if (strlen($title) <= 0) {
            return with(new \Zank\Common\Message($response, false, '帖子标题不能为空！'))
            ->withJson();
        }
        if (strlen(t($content)) <= 0) {
            return with(new \Zank\Common\Message($response, false, '帖子内容不能为空！'))
            ->withJson();
        }

        $sort_id = $sort_id +0;
        $sort_id = intval($sort_id);
        if($sort_id<=0){
            return with(new \Zank\Common\Message($response, false, '帖子分类不能为空！'))
            ->withJson();
        }
        /*
        preg_match_all('/./us', $title, $match);
        if (count($match[0]) > 30) {     //汉字和字母都为一个字
            return with(new \Zank\Common\Message($response, false, '帖子标题不能超过30个字!'))
            ->withJson();
        }*/
        $cms = new \Zank\Model\Cms();
        $cms->title = $title;
        $cms->content = $content;
        $cms->sort_id = $sort_id;
        $cms->post_uid = 1;
        unset($content);
        if ($cms->save()) {
            return with(new \Zank\Common\Message($response, true, '提交成功'))
            ->withJson();
        }
        return with(new \Zank\Common\Message($response, false, '提交失败'))
            ->withJson();
    }

    /**
     * undocumented class
     *
     * @package default
     * @author 
     **/
    public function addSort(Request $request, Response $response) 
    {
        $sort_name = $request->getParsedBodyParam('sort_name');
        $sort_icon = $request->getParsedBodyParam('sort_icon');
        $sort_name = t($sort_name);
        $sort_icon = t($sort_icon);
        if (strlen($sort_name) <= 0) {
            return with(new \Zank\Common\Message($response, false, '分类标题不能为空！'))
            ->withJson();
        }
        if (strlen($sort_icon) <= 0) {
            return with(new \Zank\Common\Message($response, false, '分类icon不能为空！'))
            ->withJson();
        }
        $cmssort = new \Zank\Model\Cmssort();
        $pinyin = new Pinyin();
        $cmssort->sort_pingyin = $pinyin->permalink($sort_name, '');
        if (strlen($cmssort->sort_pingyin) <= 0) {
            return with(new \Zank\Common\Message($response, false, '分类标题转化拼音失败！'))
            ->withJson();
        }
        $cmssort->sort_name = $sort_name;
        $cmssort->sort_icon = $sort_icon;
        if ($cmssort->save()) {
            return with(new \Zank\Common\Message($response, true, '提交成功'))
            ->withJson();
        }
        return with(new \Zank\Common\Message($response, false, '提交失败'))
            ->withJson();
    }

    /**
     * 查出所有分类
     *
     * @package default
     * @author 
     **/
    public function findSort(Request $request, Response $response)  
    {   
        $cmssorts = \Zank\Model\Cmssort::all();
       // $cmssorts = $cmssort::all();
        //临时要显示的字段
        //$sortdata = $cmssorts->makeVisible(['cid','who_can_post'])->toArray();
        $sortdata = $cmssorts->toArray();
        echo "<select name='list'>";
        for ($i=0; $i < count($sortdata); $i++) { 
            echo '<option value="' . $sortdata[$i]['sort_id'] . '">' . str_replace(PHP_EOL, '', $sortdata[$i]['sort_name']) . '</option>';
        }
        echo "</select>";
    } 
}
