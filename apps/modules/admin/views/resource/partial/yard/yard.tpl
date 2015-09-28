{%$yards = $data.data%}
{%$type = ['后台添加','用户添加']%}
{%$param = $data.paras%}

<ul class="table-filter clearfix">
	<li>
		<a href="/yard/yarddetail" class="btn">新增</a>
	</li>
	<li>
		<div id="filter-from"></div>
	</li>
	<li>
		<span class="title">查找</span>
		<input type="text" class="filter-input" placeholder="堆场名"/>
	</li>
	<li>
		<a href="javascript:;" class="btn" role="button" id="search">搜索</a>
	</li>
	<li>
		{%widget name="common/widget/table/date_filter.tpl/date.tpl" from="create_time_start" to="create_time_end"%}
	</li>
	<li>
		<a href="/yard/yardInfos" role="button" id="clearAll">清除条件</a>
	</li>
</ul>
<table class="x-table carteam-table" border="1">
	<thead>
		<tr>
			<th width="10%">ID</th>
			<th width="30%">堆场名</th>
			<th width="15%">类型</th>
			<th width="15%">创建时间</th>
			<th width="15%">更新时间</th>
			<th width="10%">操作</th>
		</tr>
	</thead>
	<tbody>
		{%foreach $yards as $i => $item%}
			{%if $i%2 == 1%}
				<tr class="double">
			{%else%}
				<tr>
			{%/if%}
				<td>{%$item.id%}</td>
				<td>{%$item.yard_name%}</td>
				<td>
					{%if $item.type == 1%}
						用户添加
					{%else%}
						后台添加
					{%/if%}
				</td>
				<td>{%$item.create_time%}</td>
				<td>{%$item.update_time%}</td>
				<td><a href="/yard/yarddetail?yard_id={%$item.id%}" target="_blank" class="btn style1">编辑</a></td>
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
	require('resource/partial/yard/yard').init({%json_encode($param)%});
{%/script%}