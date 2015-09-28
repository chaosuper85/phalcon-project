<div id="login">
    <div class="login-header">
        <div class="wrapper">
            <h2>
                登录
            </h2>
        </div>
    </div>
    <div class="wrapper">
        <div class="pageLogin-content ">
            <div class="center">
                <dl>
                    <dd class="clearfix">
                        <span class="pageLogin-title">
                            用户名/手机号
                        </span>
                        <input type="text" id="pageLogin-username" name="pageLogin-username" maxlength="11"/>
                        <span class="right-mark invisible">
                        </span>
                        <div class="tip">
                            请输入用户名或11位手机号码
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="pageLogin-title">
                            密码
                        </span>
                        <input type="password" id="pageLogin-pass" name="pageLogin-pass" maxlength="16"/>
                        <span class="right-mark invisible">
                        </span>
                        <div class="tip">
                            请输入密码
                        </div>
                    </dd>
                    <dd class="clearfix none">
                        <span class="pageLogin-title">
                            验证码
                        </span>
                        <input type="text" id="pageLogin-code" name="pageLogin-code" maxlength="4"/>
                        <div class="code">
                        </div>
                        <span class="right-mark invisible code-mark">
                        </span>
                        <div class="tip">
                            请输入验证码
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="wrapper regbtn-wrap">
        <div class="remember clearfix">
            <!-- <input type="checkbox" id="pageLogin-remember">
            <label for="pageLogin-remember">
                自动登录
            </label> -->
            <a href="/index/findPwd">
                忘记密码
            </a>
        </div>
        <div class="btn clearfix">
            <span class="pageLogin-title invisible">
            </span>
            <a href="javascript:;" id="pageLogin-submit">
                登录
            </a>
        </div>
    </div>
</div>

{%script type="text/javascript"%}
    require('index/widget/view/login/login').init('{%$data.from%}');
{%/script%}