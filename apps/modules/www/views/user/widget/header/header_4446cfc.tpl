{%$active = ['','','','']%}
{%if isset($page)%}
	{%$active[$page] = 'active'%}
{%/if%}

<div class="header-wrapper">
	<div id="user-header" class="wrapper-wide">
		<a href="/" class="logo">
			<img src="/static/user/static/image/logo_orange_210a10e.png" alt="56xdd.com"/>
		</a>
		<div class="nav clearfix">
			<ul class="left">
				{%if $data.user.status == 4%}<li><a href="/order/list" class="{%$active[0]%}">陆运服务</a></li>{%/if%}
				<li><a href="/account/personalInfo" class="{%$active[2]%}">帐号管理</a></li>
			</ul>
			<ul class="right">
				<li><a class="nav-help" title="联系电话">免费客服电话：400-9696790</a></li>
				<li><a id="apps-btn" href="javascript:;" class="nav-app" title="APP"><div class="icon"></div><span>APP</span></a></li>
				<li class="user-func">
					<a href="javascript:;" class="clearfix">
						<div class="avatar"></div>
						<span class="username">{%$data.user.username%}</span>
						<span class="arrow"></span>
						<div class="user-menu">
							<span id="ucenter">个人中心</span>
							<span id="logout">退出账户</span>
						</div>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

{%script type="text/javascript"%}
	require('user/widget/header/header')();
{%/script%}
