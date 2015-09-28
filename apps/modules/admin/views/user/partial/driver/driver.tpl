{%$drivers = $data.data%}
<div class="driver_wrap">
	<h3 class="x-table-title">
		{%if $data.data_head%}
			{%$data.data_head.teamName%}
		{%/if%}
	</h3>
		<table class="x-table carteam-table" border="1">
			<thead>
				<tr>
					<th width="15%">司机姓名</th>
					<th width="10%">联系电话</th>
					<th width="10%">车牌号</th>
					<th width="10%">行驶证号</th>
					<th width="10%">注册时间</th>
				</tr>
			</thead>
			<tbody>
				{%foreach $drivers as $i => $driver%}
					{%if $i%2 == 1%}
						<tr class="double">
					{%else%}
						<tr>
					{%/if%}
						<td>{%$driver.driver_name%}</td>
						<td>{%$driver.mobile%}</td>
						<td>{%$driver.car_number%}</td>
						<td>{%$driver.drive_number%}</td>
						<td>{%$driver.regist_time%}</td>
					</tr>
				{%/foreach%}
			</tbody>
		</table>
	<div class="table-panel clearfix">
		<div class="uploader">
			<div id="upload">上传文件</div>
			<div class="info"></div>
			<div id="submit">开始上传</div>
		</div>
	</div>
</div>


{%if $data.page_sum >= 2%}
	{%widget name="common/widget/pager/pager.tpl" total=$data.data_sum current=$data.page_no%}
{%/if%}

{%script type="text/javascript"%}
	require('user/partial/driver/driver').init();
{%/script%}