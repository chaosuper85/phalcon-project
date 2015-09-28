<div id="forgot_pwd">
    <div class="steps">
        <div class="wrapper clearfix">
            <span class="title">找回密码</span>
            <ul class="clearfix">
                <li class="step step1 cur"><span></span>确认账号</li>
                <li class="border"></li>
                <li class="step step2"><span></span>重置密码</li>
                <li class="border"></li>
                <li class="step step3"><span></span>完成</li>
            </ul>
        </div>
    </div>

    <div id="step1" class="wrapper container">
        <dl>
            <dd class="clearfix">
                <span class="title">注册手机</span>
                <input autocomplete="off" type="text" id="data_mobile" maxlength="11"/>
                <span class="right-mark invisible"></span>
                <div class="tip">请输入注册使用的手机号码</div>
            </dd>
            <dd class="clearfix">
                <span class="title"></span>
                <button id="getSms" class="disable">获取短信验证码</button>
            </dd>
            <dd class="clearfix">
                <span class="title">短信验证码</span>
                <input autocomplete="off" type="text" id="data_sms" maxlength="4"/>
                <span class="right-mark invisible"></span>
                <div class="tip">请输入4位短信验证码</div>
            </dd>
        </dl>
        <div class="submit-wrap">
            <a href="javascript:;" id="submit_step1" class="disable">下一步</a>
        </div>
    </div>

    <div id="step2" class="wrapper container">
        <dl>
            <dd class="clearfix">
                <span class="title">新密码</span>
                <input autocomplete="off" type="password" id="data_pass" maxlength="16"/>
                <span class="right-mark invisible"></span>
                <div class="tip">6到15位数字和字母组合</div>
            </dd>
            <dd class="clearfix">
                <span class="title">重复新密码</span>
                <input autocomplete="off" type="password" id="data_repass" maxlength="16"/>
                <span class="right-mark invisible"></span>
                <div class="tip">请再次输入新密码</div>
            </dd>
        </dl>
        <div class="submit-wrap">
            <a href="javascript:;" id="submit_step2" class="disable">提 交</a>
        </div>
    </div>

    <div id="step3" class="wrapper container finish">
        <div class="content">
            <span class="icon"></span>
            <span>您的密码已经成功修改！</span>
        </div>
        <div class="submit-wrap">
            <p>您已重置密码，建议马上登录验证新密码</p>
            <a href="/" id="submit_step3">回到首页</a>
        </div>
    </div>
</div>
{%script type="text/javascript"%}
    require('index/widget/view/forgotPassword/forgot_pwd').init();
{%/script%}