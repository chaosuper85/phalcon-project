{%$ships = $data.data%}

{%$param = $data.paras%}

<ul class="table-filter clearfix">
	<li>
		<a href="javascript:;" class="btn" id="add_com">新增</a>
	</li>
	<li>
		{%widget name="common/widget/table/date_filter.tpl/date.tpl" from="begin_time" to="end_time"%}
	</li>
	<li>
		<a href="/yard/yardInfos" role="button" id="clearAll">清除条件</a>
	</li>
</ul>
<table class="x-table carteam-table" border="1">
	<thead>
		<tr>
			<th width="13%">船名</th>
			<th width="13%">英文名</th>
			<th width="10%">联系人</th>
			<th width="10%">联系电话</th>
			<th width="14%">联系地址</th>
			<th width="10%">创建时间</th>
			<th width="10%">更新时间</th>
			<th width="10%">来源</th>
			<th width="10%">操作</th>
		</tr>
	</thead>
	<tbody>
		{%foreach $ships as $i => $item%}
			{%if $i%2 == 1%}
				<tr class="double">
			{%else%}
				<tr>
			{%/if%}
				<td class="name_zh">{%$item.china_name%}</td>
				<td class="name_en">{%$item.eng_name%}</td>
				<td class="contact_name">{%$item.contact_name%}</td>
				<td class="mobile">{%$item.phone_mobile%}</td>
				<td class="address">{%$item.com_address%}</td>
				<td>{%$item.create_time%}</td>
				<td>{%$item.update_time%}</td>
				<td>
					{%if $item.type == 2%}
						用户添加
					{%else%}
						后台添加
					{%/if%}
				</td>
				<td><a href="javascript:;" target="_blank" class="btn style1 ship_edit" data-id="{%$item.id%}">编辑</a></td>
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
	require('resource/partial/ship/ship').init();
{%/script%}