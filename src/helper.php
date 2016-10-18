<?php

use Zank\Util\Yaml;

if (!function_exists('cfg')) {
    /**
     * cfg YAML配置获取.
     *
     * @param string $key     键名
     * @param mixed  $default 默认值
     *
     * @return mixed
     *
     * @author Seven Du <lovevipdsw@outlook.com>
     * @homepage http://medz.cn
     */
    function cfg(string $key, $default = null)
    {
        $clientKey = '.zank.yaml';
        $filename = dirname(__DIR__).'/.zank.yaml';

        Yaml::addClient($clientKey, $filename);

        return Yaml::getClient($clientKey)->get($key, $default);
    }
}

if (!function_exists('get_oss_bucket_name')) {
    /**
     * 获取oss的bucket名称.
     *
     * @return string
     *
     * @author Seven Du <lovevipdsw@outlook.com>
     * @homepage http://medz.cn
     */
    function get_oss_bucket_name()
    {
        return \Zank\Application::getContainer()->get('settings')->get('oss')['bucket'];
    }
}

if (!function_exists('attach_url')) {
    /**
     * 更具附件文件地址，获取URL.
     *
     * @param string $path 文件地址（相对）
     *
     * @return string URL
     *
     * @author Seven Du <lovevipdsw@outlook.com>
     * @homepage http://medz.cn
     */
    function attach_url(string $path)
    {
        $settings = \Zank\Application::getContainer()->get('settings')->get('oss');

        if ($settings['sign'] === true) {
            return \Zank\Application::getContainer()
                ->get('oss')
                ->signUrl($settings['bucket'], $path, $settings['timeout']);
        }

        return sprintf('%s/%s', $settings['source_url'], $path);
    }
}

if (!function_exists('database_source_dir')) {
    /**
     * 获取数据相关资源目录.
     *
     * @return string
     *
     * @author Seven Du <lovevipdsw@outlook.com>
     * @homepage http://medz.cn
     */
    function database_source_dir()
    {
        return dirname(__DIR__).'/database';
    }
}

function daying($arr =[]){

    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    exit;
}
/**
 * @去除XSS（跨站脚本攻击）的函数
 * @par $val 字符串参数，可能包含恶意的脚本代码如<script language="javascript">alert("hello world");</script>
 * @return 处理后的字符串
 * @Recoded By Androidyue
 **/
if (!function_exists('RemoveXSS')) {
    function RemoveXSS($val)
    {
        // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        // this prevents some character re-spacing such as <java\0script>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08\x0b-\x0c\x0e-\x19])/', '', $val);

        // straight replacements, the user should never need these since they're normal characters
        // this prevents like <IMG SRC=@avascript:alert('XSS')>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
          // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

          // @ @ search for the hex values
          $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
          // @ @ 0{0,7} matches '0' zero to seven times
          $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
        }

        // now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);

        $found = true; // keep replacing as long as the previous round replaced something
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < count($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
             $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
             if ($val_before == $val) {
                 // no replacements were made, so exit the loop
                $found = false;
             }
            }
        }

        return $val;
    }
}


/**
 * t函数用于过滤标签，输出没有html的干净的文本
 * @param string text 文本内容
 * @return string 处理后内容
 */
if (!function_exists('t')) {
    function t($text)
    {
        $text = nl2br($text);
        $text = real_strip_tags($text);
        $text = addslashes($text);
        $text = trim($text);

        return $text;
    }
}

if (!function_exists('real_strip_tags')) {
    function real_strip_tags($str, $allowable_tags = '')
    {
        $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');

        return strip_tags($str, $allowable_tags);
    }
}

/**
 * h函数用于过滤不安全的html标签，输出安全的html
 * @param  string $text 待过滤的字符串
 * @param  string $type 保留的标签格式
 * @return string 处理后内容
 */
if (!function_exists('h')) {
    function h($text, $type = 'html')
    {
        // 无标签格式
        $text_tags = '';
        //只保留链接
        $link_tags = '<a>';
        //只保留图片
        $image_tags = '<img>';
        //只存在字体样式
        $font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
        //标题摘要基本格式
        $base_tags = $font_tags.'<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
        //兼容Form格式
        $form_tags = $base_tags.'<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
        //内容等允许HTML的格式
        $html_tags = $base_tags.'<meta><ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed><param>';
        //专题等全HTML格式
        $all_tags = $form_tags.$html_tags.'<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
        //过滤标签
        $text = real_strip_tags($text, ${$type.'_tags'});
        // 过滤攻击代码
        if ($type != 'all') {
            // 过滤危险的属性，如：过滤on事件lang js
            while (preg_match('/(<[^><]+)(allowscriptaccess|ondblclick|onclick|onload|onerror|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat)) {
                $text = str_ireplace($mat[0], $mat[1].$mat[3], $text);
            }
            while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
                $text = str_ireplace($mat[0], $mat[1].$mat[3], $text);
            }
        }
        $text = trim($text);
        return $text;
    }

}

