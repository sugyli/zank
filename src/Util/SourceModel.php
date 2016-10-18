<?php

namespace Zank\Util;
/**
 * 资源模型 - 业务逻辑模型
 * @example
 * 根据表名及资源ID，获取对应的资源信息
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class SourceModel
{
     
    /**
     * 储存单例的静态成员
     *
     * @var object
     **/
    protected static $_instance;
    /**
     * 获取单例对象
     *
     * @return object
     * @author Seven Du <lovevipdsw@vip.qq.com>
     **/
    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self('Zank\Util\SourceModel');
        }
        return self::$_instance;
    }


    /**
     * 获取指定资源，并格式化输出
     */
   public function getSourceInfo($_datas, $type = "",$charset = 'utf-8', $contentType = 'text/html')
    {   
        $type = strtolower($type);//转化小写
        switch ($type) {
            case 'cms':
            $htmls = "";
            foreach ($_datas as $key => $_data) {
               $pics =  $this->getEditorImages($_data['content']);
               if (!empty($pics)) {
                   $htmls .=  <<<Eof
                          <ul data-node="listBox" class="listBox">
                            <li class="item clearfix" data-node="item">
                              <div class="item-inner">
                                <div class="lbox left">
                                  <a href="/news_hot/2678.html" target="_blank">
                                  <img class="feedimg" src="{$pics['pic_url_small']}" onload="this.style.opacity=1;" style="opacity: 1;">
                                  </a>
                                </div>
                                <div class="rbox"><!--hold-->
                                  <div class="rbox-inner">
                                    <div class="title-box">
                                      <a ga_event="click_feed_newstitle" class="link title" href="/news_hot/2678.html" target="_blank" data-node="title">
                                       {$_data['title']} </a>
                                    </div>
                                    <!--
                                    <div class="abstract">
                                      <a ga_event="click_feed_newsabstract" class="link" href="/news_hot/2678.html" target="_blank">asdfasdfasdf</a>
                                    </div>-->
                                    <div class="footer clearfix">
                                      <div class="left lfooter">
                                        <a class="lbtn source" href="javascript:;">&nbsp;</a>
                                        <span class="lbtn comment"></span>
                                        <span class="lbtn time">{$_data['updated_at']}</span>
                                      </div>
                                      <div class="right rfooter">
                                        <span data-node="likeGroup" class="">
                                        
                                          <a class="rbtn bury" href="javascript:;" title="踩" onclick="makeRequest('/e/public/digg?classid=1&id=2678&dotop=1&doajax=1&ajaxarea=diggnum','EchoReturnedText','GET','');"></a>
                                          <a class="rbtn digg" href="javascript:;" title="顶" onclick="makeRequest('/e/public/digg?classid=1&id=2678&dotop=0&doajax=1&ajaxarea=diggnum','EchoReturnedText','GET','');"></a>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </li>
                          </ul>
Eof;
               }else{
                    $htmls .=  <<<Eof
                         <ul data-node="listBox" class="listBox">
                            <!--第二种消息-->
                            <li class="item clearfix" data-node="item">  
                              <div class="item-inner">
                                <div class="rbox-inner">         
                                  <div class="title-box">
                                    <a ga_event="click_feed_newstitle" class="link title" href="/news_hot/2679.html" target="_blank" data-node="title">
                                      {$_data['title']}</a>
                                  </div>
                                  <div class="footer clearfix">
                                        <div class="left lfooter">
                                          <a class="lbtn source" href="javascript:;">&nbsp;</a>
                                          <span class="lbtn comment"></span>
                                          <span class="lbtn time">{$_data['updated_at']}</span>
                                        </div>
                                        <div class="right rfooter">
                                          <span data-node="likeGroup" class="">
                                          
                                            <a class="rbtn bury" href="javascript:;" title="踩" onclick="makeRequest('/e/public/digg?classid=1&id=2679&dotop=1&doajax=1&ajaxarea=diggnum','EchoReturnedText','GET','');"></a>
                                            <a class="rbtn digg" href="javascript:;" title="顶" onclick="makeRequest('/e/public/digg?classid=1&id=2679&dotop=0&doajax=1&ajaxarea=diggnum','EchoReturnedText','GET','');"></a>
                                          </span>
                                        </div>
                                  </div>
                                </div>
                              </div>
                            </li>            
                          </ul>
Eof;
                }              
            }
            break;
            case 'cms_ajax':
            $htmls = "";
            foreach ($_datas as $key => $_data) {
               $pics =  $this->getEditorImages($_data['content']);
               if (!empty($pics)) {
                   $htmls .=  <<<Eof

                          <li class="item clearfix" data-node="item" rel="loaded">
                            <div class="item-inner">
                              <div class="lbox left">
                                <a href="/news_hot/2678.html" target="_blank">
                                <img class="feedimg" src="{$pics['pic_url_small']}" onload="this.style.opacity=1;" style="opacity: 1;">
                                </a>
                              </div>
                              <div class="rbox"><!--hold-->
                                <div class="rbox-inner">
                                  <div class="title-box">
                                    <a ga_event="click_feed_newstitle" class="link title" href="/news_hot/2678.html" target="_blank" data-node="title">
                                     {$_data['title']} </a>
                                  </div>
                                  <!--
                                  <div class="abstract">
                                    <a ga_event="click_feed_newsabstract" class="link" href="/news_hot/2678.html" target="_blank">asdfasdfasdf</a>
                                  </div>-->
                                  <div class="footer clearfix">
                                    <div class="left lfooter">
                                      <a class="lbtn source" href="javascript:;">&nbsp;</a>
                                      <span class="lbtn comment"></span>
                                      <span class="lbtn time">{$_data['updated_at']}</span>
                                    </div>
                                    <div class="right rfooter">
                                      <span data-node="likeGroup" class="">
                                      
                                        <a class="rbtn bury" href="javascript:;" title="踩" onclick="makeRequest('/e/public/digg?classid=1&id=2678&dotop=1&doajax=1&ajaxarea=diggnum','EchoReturnedText','GET','');"></a>
                                        <a class="rbtn digg" href="javascript:;" title="顶" onclick="makeRequest('/e/public/digg?classid=1&id=2678&dotop=0&doajax=1&ajaxarea=diggnum','EchoReturnedText','GET','');"></a>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>                       
Eof;
               }else{
                    $htmls .=  <<<Eof
                            <li class="item clearfix" data-node="item" rel="loaded">
                              <div class="item-inner">
                                <div class="rbox-inner">         
                                  <div class="title-box">
                                    <a ga_event="click_feed_newstitle" class="link title" href="/news_hot/2679.html" target="_blank" data-node="title">
                                      {$_data['title']}</a>
                                  </div>
                                  <div class="footer clearfix">
                                        <div class="left lfooter">
                                          <a class="lbtn source" href="javascript:;">&nbsp;</a>
                                          <span class="lbtn comment"></span>
                                          <span class="lbtn time">{$_data['updated_at']}</span>
                                        </div>
                                        <div class="right rfooter">
                                          <span data-node="likeGroup" class="">
                                          
                                            <a class="rbtn bury" href="javascript:;" title="踩" onclick="makeRequest('/e/public/digg?classid=1&id=2679&dotop=1&doajax=1&ajaxarea=diggnum','EchoReturnedText','GET','');"></a>
                                            <a class="rbtn digg" href="javascript:;" title="顶" onclick="makeRequest('/e/public/digg?classid=1&id=2679&dotop=0&doajax=1&ajaxarea=diggnum','EchoReturnedText','GET','');"></a>
                                          </span>
                                        </div>
                                  </div>
                                </div>
                              </div>
                            </li>            
Eof;
                }              
            }
            break;
        }
        return $htmls;
    }

    /**
     * 获取编辑器内容中的第一个图片（非表情图片）
     * @param  string $content 编辑器内容
     * @return array  图片的地址数组
     */
    private function getEditorImages($content)
    {
        preg_match_all('/<img.*src=\s*[\'"](.*)[\s>\'"]/isU', $content, $matchs);
        $info = array();
        if(is_array($matchs)) {
            //获取第一张图片
            foreach($matchs[1] as $match)
            {
                $info['pic_url_small'] = $match;
                $info['pic_url_medium'] = $match;
                $info['pic_url'] = $match;
                break;
            }
        }
        /*
        foreach ($matchs[1] as $match) {
            if (strpos($match, '/emotion/') === false) {
                $file = null;
                $path = substr(UPLOAD_PATH, strlen(SITE_PATH));
                if (strpos($match, $path)) {
                    list($unkn, $file) = explode($path, $match, 2);
                }
                if ($file && is_file(UPLOAD_PATH.$file)) {
                    $info['pic_url_small'] = getImageUrl($file, 120, 120, true);
                    $info['pic_url_medium'] = getImageUrl($file, 240);
                    $info['pic_url'] = getImageUrl($file);
                } else {
                    $info['pic_url_small'] = $match;
                    $info['pic_url_medium'] = $match;
                    $info['pic_url'] = $match;
                }
                break;
            }
        }
        */
        return $info;
    }
} // END class Message