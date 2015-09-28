{%$roles = $data.data%}

<div class="role_wrap">
	<ul class="role_nav">
		<li class="title">角色列表</li>
		{%foreach $roles as $i => $role%}
			{%if $i == 0%}
				<li data-id="{%$role.id%}" class="item active">{%$role.group_name%}<i class="iconfont icon-xiangyou1"></i></li>
			{%else%}
				<li data-id="{%$role.id%}" class="item">{%$role.group_name%}<i class="iconfont icon-xiangyou1"></i></li>
			{%/if%}
		{%/foreach%}
		<li class="add"><i class="iconfont icon-jia"></i>创建角色</li>
	</ul>
	<div class="role_content">
		<h3>权限详情</h3>
		<div class="content">
			<div id="role-tree"></div>
		</div>
	</div>
</div>

{%script type="text/javascript"%}
	require('admin/partial/role/role')();
{%/script%}