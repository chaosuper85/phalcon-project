{%$roles = $data.data%}

<div class="role_wrap">
	<ul class="role_nav">
		<li class="title">角色列表</li>
		{%foreach $roles as $i => $role%}
			<li data-index="{%$i%}" data-id="{%$role.id%}" class="item">{%$role.group_name%}({%$role.user.data_sum%})<i class="iconfont icon-xiangyou1"></i></li>
		{%/foreach%}
	</ul>
	<div class="role_content">
		<h3>用户列表</h3>
		<div class="content">
			{%foreach $roles as $i => $group%}
				<ul data-index="{%$i%}" class="user_list">
					{%if $group.user.data%}
						{%foreach $group.user.data as $k => $user%}
							<li class="item">
								{%$user.username%}
								<i class="iconfont icon-cuowu1 remove_user" title="移除" data-uid="{%$user.id%}" data-id="{%$group.id%}"></i>
							</li>
						{%/foreach%}
					{%/if%}
					<li class="add_user" data-id="{%$group.id%}"><i class="iconfont icon-jia"></i>添加用户</li>
				</ul>
			{%/foreach%}
		</div>
	</div>
</div>

{%script type="text/javascript"%}
	require('admin/partial/group/group');
{%/script%}