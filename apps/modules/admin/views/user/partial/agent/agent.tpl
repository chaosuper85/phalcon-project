{%$teams = $data.data%}
{%$param = $data.paras%}
{%$audit_status = ['全部','新注册','已驳回','待审核','已认证']%}
{%$status = ['全部','正常','已锁定','已删除']%}
{%$active = array(10)%}
{%$active_status = array(10)%}
{%for $i = 0; $i < 10; $i++%}
	{%$active[$i] = ''%}
	{%$active_status[$i] = ''%}
{%/for%}
{%if !$param.status%}
	{%$active[$param.audit_status] = "active"%}
{%/if%}
{%$active_status[$param.status] = "active"%}

<ul class="table-filter clearfix">
	<li>
		{%widget name="common/widget/table/date_filter.tpl/date.tpl" from="begin_time" to="end_time"%}
	</li>
	<li>
		<div id="filter_platform"></div>
	</li>
	<li>
		<div id="filter_version"></div>
	</li>
	<div class="clear"></div>
	<li>
		<span class="title">查找</span>
		<div id="filter_search_type"></div>
	</li>
	<li>
		{%if $param.name%}
			<input type="text" class="filter-input" value="{%$param.name%}"/>
		{%else%}
			<input type="text" class="filter-input" value="{%$param.mobile%}"/>
		{%/if%}
	</li>
	<li>
		<a href="javascript:;" class="btn" role="button" id="search">搜索</a>
	</li>
	<li>
		<a href="javascript:;" role="button" id="clearAll">清除条件</a>
	</li>
</ul>
<table class="x-table agent-table" border="1">
	<ul class="x-table-nav">
		<a href="/agent/agents" class="item {%$active[0]%}">全部</a>
		<a href="/agent/agents?audit_status=1" class="item {%$active[1]%}">新注册</a>
		<a href="/agent/agents?audit_status=3" class="item {%$active[3]%}">待审核</a>
		<a href="/agent/agents?audit_status=2" class="item {%$active[2]%}">已驳回</a>
		<a href="/agent/agents?audit_status=4" class="item {%$active[4]%}">已认证</a>
		<a href="/agent/agents?status=2" class="item {%$active_status[2]%}">已锁定</a>
		<a href="/agent/agents?status=3" class="item {%$active_status[3]%}">已删除</a>
	</ul>
	<thead>
		<tr>
			<th width="5%"><input type="checkbox" class="radio-all"/></th>
			<th width="20%">货代名称</th>
			<th width="10%">用户名</th>
			<th width="10%">注册手机</th>
			<th width="15%">注册时间</th>
			<th width="10%">平台</th>
			<th width="10%">版本号</th>
			<th width="10%">当前状态</th>
			<th width="10%">详情</th>
		</tr>
	</thead>
	<tbody>
		{%foreach $teams as $i => $item%}
			{%if $i%2 == 1%}
				<tr class="double">
			{%else%}
				<tr>
			{%/if%}
				<td class="checkbox"><input type="checkbox" data-id="{%$item.id%}" class="checkbox_data"/></td>
				<td>{%$item.unverify_enterprisename%}</td>
				<td>{%$item.username%}</td>
				<td>{%$item.mobile%}</td>
				<td>{%$item.regist_time%}</td>
				<td>{%$item.regist_platform%}</td>
				<td>{%$item.regist_version%}</td>
				<td>{%$audit_status[$item.audit_status]%}</td>
				<td><a href="javascript:;" class="btn style1 btn_detail" data-id={%$item.id%}>查看</a></td>
			</tr>
		{%/foreach%}
	</tbody>
	<tfoot>
	</tfoot>
</table>
{%if $data.data_sum == 0%}
	<div class="table-nodata"><span><i class="iconfont icon-tixing"></i>暂无数据</span></div>
{%else%}
	<ul class="table-functions clearfix">
		<li><a href="javascript:;" data-type="del" class="btn red">删除</a></li>
		<li><a href="javascript:;" data-type="unlock" class="btn">解锁</a></li>
		<li><a href="javascript:;" data-type="lock" class="btn">锁定</a></li>
		<li><a href="javascript:;" data-type="reject" class="btn">驳回</a></li>
		<li><a href="javascript:;" data-type="pass" class="btn">通过</a></li>
	</ul>
{%/if%}

{%if $data.page_sum >= 2%}
	{%widget name="common/widget/pager/pager.tpl" total=$data.data_sum current=$data.page_no%}
{%/if%}

{%script type="text/javascript"%}
	require('user/partial/agent/agent').init({%json_encode($param)%});
{%/script%}
