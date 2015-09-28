<div id="change-content">
    <h2 class="header-content">账户安全&nbsp;>&nbsp;修改手机号</h2>
    <div class="change-phone-content">
        <div class="nav-bar">
            <ul class=" clearfix">
               <li class="current nav-one">
                  <i></i>
                  <span>认证身份</span>
               </li>
               <li class="nav-two">
                  <i></i>
                  <span>修改手机</span>
               </li>
               <li class="nav-three">
                  <i></i>
                  <span>完成</span>
               </li>
            </ul>
        </div>
        <div class="step">
            <div id="step-one-sms" class="hidden">
                <dl>
                    <dd class="clearfix">
                        <div class="title">手机号</div>
                        <div class="content">{%$data.user.mobile%}</div>
                        <a href="javascript:;" class="user-btn" id="get-sms">免费获取短信验证码</a>
                    </dd>
                    <dd class="clearfix">
                        <div class="title">验证码</div>
                        <div class="content">
                           <input type="text" id="change-phone-sms"></input>
                           <div class="tip-sms tip red"></div>
                        </div>
                    </dd>
                </dl>
                <a href="javascript:;" class="next-step user-btn next-step-one">下一步</a>
            </div>

            <div id="step-one-pass" class="hidden">
                 <dl>
                    <dd class="clearfix">
                        <div class="title"></div>
                        <div class="content">
                            请输入用户名为<span>{%$data.user.mobile%}</span>的密码
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <div class="title">密码</div>
                        <div class="content">
                           <input type="password" id="step-one-password" ></input>
                           <div class="tip-pass tip red"></div>
                        </div>
                    </dd>
                </dl>
                <a href="javascript:;" class="next-step user-btn next-step-one">下一步</a>
            </div>

            <div id="step-two" class="hidden">
                <dl>
                    <dd class="clearfix">
                        <div class="title">手机号</div>
                        <div class="content">
                           <input type="text" id="new-mobile"></input>
                           <a href="javascript:;" class="user-btn" id="get-new-sms">免费获取短信验证码</a>
                           <div  id ="new-mobiletip"class="tip tip-newmobile red"></div>
                        </div>
                    </dd>

                    <dd class="clearfix">
                        <div class="title">验证码</div>
                        <div class="content">
                           <input type="text" id="new-phone-sms"></input>
                           <div class="tip-newsms tip red"></div>
                        </div>
                    </dd>
                </dl>
                <a href="javascript:;" class="next-step user-btn" id="next-step-two">下一步</a>
            </div>

            <div id="step-three" class="hidden">
                <dl>
                    <dd class="clearfix">
                        <div class="title"></div>
                        <div class="content">
                           <i></i>
                           <span>手机号修改成功！</span>
                        </div>
                    </dd>
                </dl>
                <a href="javascript:;" class="next-step user-btn" id="next-step-three"></a>
            </div>

        </div>
    </div>
</div>


{%script type="text/javascript"%}
        console.log({%json_encode($data)%})
		require('user/widget/view/account_security/change_phone').init();
{%/script%}