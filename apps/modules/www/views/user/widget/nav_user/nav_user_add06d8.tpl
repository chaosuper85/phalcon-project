{%$active = ['','','','']%}
{%if isset($page)%}
	{%$active[$page] = 'active'%}
{%/if%}
{%require name="user/static/style/nav.less"%}
<ul id="user-nav">
	<div class="user-nav-wrap">
	<li class="item {%$active[0]%}">
		<a href="/account/personalInfo">
			<span class="nav-icon person"></span>
			个人信息
		</a>
	</li>
	<li class="item {%$active[1]%}">
		<a href="/account/enterpriseInfo">
			<span class="nav-icon company"></span>
			公司信息
		</a>
	</li>
	<li class="item {%$active[2]%}">
		<a href="/account/accountSecurity">
			<span class="nav-icon account"></span>
			帐号安全
		</a>
	</li>
	</div>
</ul>