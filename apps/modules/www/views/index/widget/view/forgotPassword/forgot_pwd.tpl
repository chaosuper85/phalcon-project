<div id="forgotPassword">
    <div class="navbar">
        <div class="wrapper-wide">
            <div class="wrapper">
                <div class="nav-fondPassword">
                    <ul class="nav-tag">
                        <li class="lable">找回密码</li>
                        <li class="current"><span class="circle-cur"></span><span>确认账号</span></li>
                        <li class="reset-password"><span class="circle"></span>重置密码</li>
                        <li class="finsh-password"><span class="circle"></span>完成</li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <div class="pwd-content clearfix">
            <div class="clearfix left">
                <dl>
                    <dd class="clearfix">
                        <span class="pwd-title">注册账号</span>
                        <input type="text" id="pwd_account" name="changePwd" maxlength="11"/>
                        <span class="right-mark invisible"></span>
                        <div class="tip xdd-mobile">箱点点注册手机号码</div>
                    </dd>
                        <dd class="clearfix">
                        <span class="pwd-title"></span>
                        <button id="btn_getMsg">免费获取短信验证码</button>
                    </dd>
                    <dd class="clearfix">
                        <span class="pwd-title">手机验证码</span>
                        <input type="text" id="pwd_msg"  name="changePwd" maxlength="4"/>
                        <span class="right-mark invisible"></span>
                        <div class="tip xdd-msg">请输入手机四位数字验证码</div>
                    </dd>
                </dl>
            </div>
            <div class="clearfix next_step">
                <span class="pwd-title"></span>
                <a href="javascript:;" id="next_step">下一步</a>
            </div>
        </div>
    </div>
</div>
<div id="new_password">
    <div class="navbar">
        <div class="wrapper-wide">
            <div class="wrapper">
                <div class="nav-fondPassword">
                    <ul class="nav-tag">
                        <li class="lable">找回密码</li>
                        <li class="confirm-account"><span class="circle-finshed"></span><span>确认账号</span></li>
                        <li class="current"><span class="circle-cur"></span>重置密码</li>
                        <li class="finsh-password"><span class="circle"></span>完成</li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
   </div>
   <div class="wrapper">
        <div class="pwd-content clearfix">
            <div class="left">
                <dl>
                    <dd class="clearfix">
                        <span class="pwd-title">新密码</span>
                        <input type="password" id="new_pwd"  name="changePwd" maxlength="16"/>
                        <span class="right-mark invisible"></span>
                        <div class="tip xdd-newpwd">密码必须是数字和字母的组合</div>
                    </dd>
                    <dd class="clearfix">
                        <span class="pwd-title">重复新密码</span>
                        <input type="password" id="confirm_pwd"  name="changePwd" maxlength="16"/>
                        <span class="right-mark invisible"></span>
                        <div class="tip xdd-repwd">请再次输入新密码</div>
                    </dd>
                </dl>
            </div>
            <div class="clearfix next_step">
                <span class="pwd-title"></span>
                <a href="javascript:;" id="next_confirm">确定</a>
            </div>
        </div>
    </div>
</div>
<div id="finsh_password">
    <div class="navbar">
        <div class="wrapper-wide">
            <div class="wrapper">
                <div class="nav-fondPassword">
                    <ul class="nav-tag">
                        <li class="lable">找回密码</li>
                        <li class="confirm-account"><span class="circle-finshed"></span><span>确认账号</span></li>
                        <li class="confirm-account"><span class="circle-finshed"></span>重置密码</li>
                        <li class="current"><span class="circle-cur"></span>完成</li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
   <div class="wrapper">
        <div class="pwd-content clearfix">
            <div class="finsh-reminder">
                <div class="reminder-content">
                    <span class="finsh-icon"></span>
                    <span>通过手机验证码成功找回密码！</span>
                </div>
                <div class="login-reminder">
                    <a href="/account/personalInfo" class="sign-account"></a>
                    <span>您已成功重置密码，建议马上登录验证新密码</span>
                </div>
            </div>
            <div class="clearfix next_step">
                <span class="pwd-title"></span>
                <a href="/index" id="goto_index"></a>
            </div>
        </div>
    </div>
</div>

{%script type="text/javascript"%}
    require('index/widget/view/forgotPassword/forgot_pwd').init();
{%/script%}