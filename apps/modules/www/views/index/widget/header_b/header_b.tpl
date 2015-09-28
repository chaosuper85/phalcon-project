
{%$home_class = ""%}
{%if isset($page) && $page == 'home'%}
	{%$home_class = "home"%}
{%/if%}

<div class="header-second {%$home_class%}">
	<a href="/"><img src="/static/common/static/image/logo.png" alt="56xdd.com" class="logo"/></a>
	<div class="right">
		{%if empty($data.user)%}
			<a id="login-btn" href="javascript:;">登录</a>
			<a href="/index">关于我们</a>
		{%else%}
			<a href="/account/personalInfo">{%$data.user.username%}</a>
			<a href="javascript:;" id="logout">退出</a>
		{%/if%}
		<a class="header-app" href="javascript:;"><i></i>App下载</a>
		<a href="javascript:;" class="service-phone"><i></i>400-969-6790</a>
	</div>
</div>

{%script type="text/javascript"%}
	require('index/widget/header_b/header_b').init();
{%/script%}
