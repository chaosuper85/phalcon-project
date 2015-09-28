{%$home_class = ""%}
{%if isset($page) && $page == 'home'%}
	{%$home_class = "home"%}
{%/if%}

<div class="header-second {%$home_class%}">
	<a href="/"><img src="/static/common/static/image/logo_8633d69.png" alt="56xdd.com" class="logo"/></a>
	<div class="right">
		{%if empty($data.user)%}
			<a id="login-btn" href="javascript:;">登录</a>
			<a href="/user/register">注册</a>
		{%else%}
			<a href="/account/personalInfo">{%$data.user.username%}</a>
			<a href="javascript:;" id="logout">退出</a>
		{%/if%}
		<a class="header-app" href="javascript:;"><i></i>App下载</a>
		<a href="javascript:;" class="service-phone"><i></i>400-969-6790</a>
	</div>
</div>

{%script type="text/javascript"%}
	require('index/widget/header/header').init();
{%/script%}
