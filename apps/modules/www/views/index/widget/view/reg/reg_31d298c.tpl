{%if $data.type == "carteam" || $data.type == "freight_agent"%}
	{%$type = $data.type%}
{%else%}
	{%$type = ''%}
{%/if%}
<div id="reg">
	<div class="reg-header">
		<div class="wrapper">
			<h2>注册用户</h2>
		</div>
	</div>
	<div class="wrapper">
		<div class="reg-content clearfix">
			<div class="center">
				<dl>
                    <dd class="clearfix">
                        <span class="reg-title">邀请码</span>
                        <input type="text" id="reg-code" name="reg-code" maxlength="10" autocomplete="off"/>
                        <span class="right-mark invisible"></span>
                        <div class="tip">请联系箱典典官方客服400-969-6790获取邀请码</div>
                    </dd>
					<dd class="clearfix">
						<span class="reg-title">用户类型</span>
						<div id="reg-type" class="clearfix" type="{%$type%}">
							{%if $type == "freight_agent"%}
								<div class="radio-box active" type="freight_agent">
							{%else%}
								<div class="radio-box" type="freight_agent">
							{%/if%}
								<span class="marker"></span>
								<span>货代用户</span>
							</div>
							
							{%if $type == "carteam"%}
								<div class="radio-box active" type="carteam">
							{%else%}
								<div class="radio-box" type="carteam">
							{%/if%}
								<span class="marker"></span>
								<span>车队用户</span>
							</div>
						</div>
						<div class="tip">请选择用户类型</div>
					</dd>
					<dd class="clearfix">
						<span class="reg-title">用户名</span>
						<input type="text" id="reg-username" name="reg-username" maxlength="12" autocomplete="off"/>
						<span class="right-mark invisible"></span>
						<div class="tip">4-12位字母或数字，首字符不能为数字</div>
					</dd>
					<dd class="clearfix"><span class="reg-title">手机号</span>
						<input type="text" id="reg-mobile" name="reg-mobile" maxlength="11" autocomplete="off"/>
						<span class="right-mark invisible"></span>
						<div class="tip">可用于登录系统，找回密码</div>
					</dd>
					<dd class="sms clearfix"><span class="reg-title">短信验证码</span>
						<input type="text" id="reg-sms" name="reg-sms" maxlength="4" autocomplete="off"/>
						<span id="get-sms" class="unselectable">发送短信</span>
						<div class="tip">请查收手机短信，并填写短信中的验证码</div>
					</dd>
					<dd class="clearfix"><span class="reg-title">密码</span>
						<input type="password" id="reg-pass" name="reg-pass" maxlength="12"/>
						<span class="right-mark invisible"></span>
						<div class="tip">6～12位数字和字母组合，区分大小写</div>
					</dd>
					<dd class="clearfix"><span class="reg-title">确认密码</span>
						<input type="password" id="reg-repass" name="reg-repass" maxlength="12"/>
						<span class="right-mark invisible"></span>
						<div class="tip">请再次输入密码</div>
					</dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="wrapper regbtn-wrap">
		<div class="agreement">
			<input type="checkbox" id="reg-agree" checked>
            <label for="reg-agree" >我已阅读并同意</label>
            <a href="/index/agreement" target="_blank">《箱典典网站服务条款》</a> 
		</div>
		<button id="reg-submit">立即注册</button>
	</div>
</div>

{%script type="text/javascript"%}
	require('index/widget/view/reg/reg').init();
{%/script%}
