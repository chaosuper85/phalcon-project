{%$active = Array()%}
{%$active_third = Array()%}
{%for $i = 0; $i <= 15; $i++%}
	{%$active[$i] = ''%}
	{%$active_third[$i] = ''%}
{%/for%}
{%if isset($page)%}
	{%$active[$page] = 'active'%}
{%/if%}
{%if isset($third)%}
	{%$active_third[$third] = 'active'%}
{%/if%}
<nav id="sidebar" class="unselectable">
	<a href="/" class="logo" title="admin.56xdd.com">
		<img src="/static/assets/image/logo_write.png" alt="admin.56xdd.com">
	</a>
	<ul>
		<li class="first">跟单系统</li>
		<a href="/ordersuper/orders"><li class="second {%$active[1]%}" ui-sref="manage"><i class="icon iconfont icon-dingdan"></i>跟单总览</li></a>
		<a href="/ordersuper/myOrder"><li class="second {%$active[2]%}" ui-sref="manage"><i class="icon iconfont icon-dingdanguanli"></i>我的跟单</li></a>

		<li class="first">前台用户</li>
		<a href="/agent/agents"><li class="second {%$active[3]%}"><i class="icon iconfont icon-nanshangjia"></i>货代管理</li></a>
		<a href="/carTeam/carTeams"><li class="second {%$active[4]%}"><i class="icon iconfont icon-wuliu"></i>车队管理</li></a>

		<li class="first">后台用户</li>
		<a href="/account/users"><li class="second {%$active[5]%}"><i class="icon iconfont icon-tianjiadaofenzu"></i>后台用户管理</li></a>
		<li class="second {%$active[6]%} hasChild"><i class="icon iconfont icon-shejishi"></i>权限管理</li>
			<li class="third">
				<ul>
					<a href="/acl/groupUsers"><li class="{%$active_third[2]%}"><i class="link"></i><i class="icon iconfont icon-circle"></i>用户分组</li></a>
					<a href="/acl/groupAcl"><li class="{%$active_third[1]%}"><i class="link"></i><i class="icon iconfont icon-circle"></i>角色管理(开发中)</li></a>
				</ul>
			</li>
		<li class="first">资源管理</li>
		<a href="/yard/yardInfos"><li class="second {%$active[7]%}"><i class="icon iconfont icon-box"></i>堆场管理</li></a>
		<a href="/ship/shipComs"><li class="second {%$active[8]%}"><i class="icon iconfont icon-company"></i>船公司管理</li></a>
		<a href="/ship/ships"><li class="second {%$active[9]%}"><i class="icon iconfont icon-ship"></i>船管理</li></a>
		<li class="first">统计数据</li>
		<a href="javascript:;"><li class="second {%$active[10]%}"><i class="icon iconfont icon-tongji"></i>统计报表(开发中)</li></a>
	</ul>
</nav>