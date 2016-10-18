if(!('console' in window)) {window.console = {log: function(msg) {}};}
$(function(){
    $('.feedbtn').click(function(){$('.feedback_dialog').toggleClass('on')});
    $('.feedback_dialog .close').click(function(){$('.feedback_dialog').removeClass('on')});
    $('#loginboxbtn').click(function(){$('#bgDiv,#lggoodBox').show();});
});
function submitfeedback(obj){
  var name='匿名';
  var email=$('[name=email]').val();
  var lytext=$('[name=lytext]').val();
  if(!email){alert('请填写联系方式');return false;}
  if(!lytext){alert('您的意见');return false;}

}
function dengluview(){
    document.write("   <div id=\"lggoodBox\" style=\"position:absolute; left:369px; top:150px; z-index:1123123123; display:none;\" ><div class=\"title\" id=\"Mdown\">                <span class=\"t1\">会员登陆</span>              <span class=\"t2\" title=\"关闭\" onClick=\"lggood.style.display=\'none\';bgDiv.style.display=\'none\'\">X</span>            </div>      <div class=\"lggood\"><form name=login method=post action=\"/e/member/doaction.php\"> <input type=hidden name=enews value=login>    <input type=hidden name=ecmsfrom value=9>                    <ul id=\"loginBox\"><li>账号：<span class=\"mcInputBox\"><span><input name=\"username\" class=\"loginInput loginDefaultInput\"></span></span></li><li>密码：<span class=\"mcInputBox\"><span><input name=\"password\" class=\"loginInput\" type=\"password\"></span></span></li></ul>                   <input id=\"meta_button\" type=\"submit\" name=\"Submit\" value=\"登陆\" class=\"button\" />                    <span class=\"tiptxt\">没有帐号？<a href=\"http://www.haik8.com/e/member/register/index.php?groupid=1&button=%CF%C2%D2%BB%B2%BD/\">请注册</a></span>        </form>            </div>        </div>");
}


