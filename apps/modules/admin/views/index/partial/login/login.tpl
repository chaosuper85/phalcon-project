<div class="login-wrap">
	<div id="login-box">
		<img src="/static/assets/image/logo.png" alt="56xdd.com" class="logo"/>
		<dl>
			<dd class="clearfix">
				<input type="text" id="login-user" placeholder="管理员用户名" maxlength="12"/>
				<i class="iconfont icon-yonghu"></i>
			</dd>
			<dd class="clearfix">
				<input type="password" id="login-pass" placeholder="请输入密码" maxlength="16"/>
				<i class="iconfont icon-suoding"></i>
			</dd>
			<dd id="login-captcha-wrap" class="clearfix hidden">
				<input type="text" id="login-captcha" placeholder="验证码" maxlength="4"/>
				<div class="captcha-wrap">
					<img src=""/>
				</div>
			</dd>
		</dl>
		<div id="login-tip" class="invisible"></div>
		<a href="javascript:;" id="login-submit">登 录</a>
	</div>
</div>

{%script type="text/javascript"%}
	require('index/partial/login/login')({%json_encode($data)%});
{%/script%}