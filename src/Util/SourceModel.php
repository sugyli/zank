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
    private $data = array();
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
   public function getSourceInfo($_datas, $type = "")
    {   
       
        $type = strtolower($type);//转化小写
        $htmls = "";
        switch ($type) {
          case 'cms':
          $file1 = "cms". DS . "cms_1.html";
          $file2 = "cms". DS . "cms_2.html";
          foreach ($_datas as $key => $_data) 
          {
            $pics =  $this->getEditorImages($_data['content']);
            if (!empty($pics)) 
            {
              $this->assign("pic_url_small",$pics['pic_url_small']);
              $htmls .= $this->display($file1,$_data);

            }else{
              $htmls .= $this->display($file2,$_data);

            }
          }                 
          break;
          case 'cms_ajax': 
          $file1 = "cms". DS . "cms_ajax_1.html";
          $file2 = "cms". DS . "cms_ajax_2.html";
          foreach ($_datas as $key => $_data) 
          {
            $pics =  $this->getEditorImages($_data['content']);
            if (!empty($pics)) 
            {
              $this->assign("pic_url_small",$pics['pic_url_small']);
              $htmls .= $this->display($file1,$_data);

            }else{
              $htmls .= $this->display($file2,$_data);

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


    private function assign($name, $value) {
      $this->data[$name] = $value;
    }

    private function display($file ,$data = array()) {
      $file = VIEW . $file;

      $content = "";
      if (file_exists($file)) {
          $content = file_get_contents($file);

          if(!empty($data))
          {
            foreach($data as $key=>$value) 
            {  
                $content = str_replace("{" . $key . "}", $value, $content);

            }
          }
          
          if(!empty($this->data))
          {
            foreach($this->data as $key=>$value) 
            {
                $content = str_replace("{" . $key . "}", $value, $content);
            }
          }          
      }

      return $content;
    }
} // END class Message