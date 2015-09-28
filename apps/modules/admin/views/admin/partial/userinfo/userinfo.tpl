{%$users = $data.data%}
{%$status = ['无','正常','锁定','删除']%}
{%$param = $data.paras%}

<ul class="table-filter clearfix">
	<li>
		<a href="javascript:;" class="btn" id="add-new">新增</a>
	</li>
	<li>
		{%widget name="common/widget/table/date_filter.tpl/date.tpl" from="begin_time" to="end_time"%}
	</li>
	<li>
		<a href="/account/users" role="button" id="clearAll">清除条件</a>
	</li>
</ul>
<table class="x-table carteam-table" border="1">
	<thead>
		<tr>
			<th width="10%">ID</th>
			<th width="12%">用户名</th>
			<th width="12%">手机号</th>
			<th width="10%">真实姓名</th>
			<th width="12%">Email</th>
			<th width="12%">创建时间</th>
			<th width="12%">更新时间</th>
			<th width="10%">状态</th>
			<th width="10%">操作</th>
		</tr>
	</thead>
	<tbody>
		{%foreach $users as $i => $item%}
			{%if $i%2 == 1%}
				<tr class="double">
			{%else%}
				<tr>
			{%/if%}
				<td class="user_id">{%$item.id%}</td>
				<td class="user_name">{%$item.username%}</td>
				<td class="user_mobile">{%$item.phone_number%}</td>
				<td class="user_real">{%$item.real_name%}</td>
				<td class="user_email">{%$item.email%}</td>
				<td>{%$item.created_at%}</td>
				<td>{%$item.updated_at%}</td>
				<td>{%$status[$item.user_status]%}</td>
				<td><a href="javascript:;" target="_blank" class="btn style1 user_edit">编辑</a></td>
			</tr>
		{%/foreach%}
	</tbody>
</table>
{%if $data.data_sum == 0%}
	<div class="table-nodata"><span><i class="iconfont icon-tixing"></i>暂无数据</span></div>
{%/if%}

{%if $data.page_sum >= 2%}
	{%widget name="common/widget/pager/pager.tpl" total=$data.data_sum current=$data.page_no%}
{%/if%}

{%script type="text/javascript"%}
	require('admin/partial/userinfo/userinfo').init();
{%/script%}