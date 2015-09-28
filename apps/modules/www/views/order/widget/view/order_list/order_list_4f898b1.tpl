{%$usertype = $data.user.usertype%}

{%$orders = $data.data.order_list%}
{%$status = $data.data.orderStatus%}
{%$boxType = ['','20GP','40GP','40HQ']%}
{%$orderType = ['全部','待确认','待分派','待提箱','待产装','待运抵','已运抵待评价','交易成功','交易关闭']%}

{%$REMARK_TXT = ['','失望','不满','一般','满意','很满意']%}

{%$pageType = ['unactive','unactive','unactive','unactive','unactive','unactive','unactive','unactive','unactive']%}
{%$pageType[$status] = 'active'%}

{%widget name="user/widget/header_content/header_content.tpl" title="订单列表" class="order-list"%}
<div id="order-list" class="user-content">
	<table class="nav">
		<tr class="nav">
			<td class="{%$pageType[0]%}"><a href="/order/list">全部订单</a></td>
			<td class="cut"></td>
			<td class="{%$pageType[1]%}"><a href="/order/list?status=1">待确认<span>({%$data.data.total_count.1%})</span></a></td>
			<td class="cut"></td>
			<td class="{%$pageType[3]%}"><a href="/order/list?status=3">待提箱<span>({%$data.data.total_count.3 + $data.data.total_count.2%})</span></a></td>
			<td class="cut"></td>
			<td class="{%$pageType[4]%}"><a href="/order/list?status=4">待产装<span>({%$data.data.total_count.4%})</span></a></td>
			<td class="cut"></td>
			<td class="{%$pageType[5]%}"><a href="/order/list?status=5">待运抵<span>({%$data.data.total_count.5%})</span></a></td>
			<td class="cut"></td>
			<td class="{%$pageType[6]%}"><a href="/order/list?status=6">待评价<span>({%$data.data.total_count.6%})</span></a></td>
			<td class="cut"></td>
		</tr>
	</table>
	<form id="order_search" method="GET" action="/order/list" class="order-search-form">
		<input type="text" id="q" class="search-box" placeholder="{%if $usertype == 1%}委托方{%else%}承运方{%/if%}" autocomplete="off" name="searchValue" aria-haspopup="true" value="{%$data.data.searchValue%}"></input>
		<div class="select-container"><div id="searchTypeSelector"></div></div>
		<input type="hidden" name="searchType" value="{%$data.data.searchType || 1%}"/>
		<input type="submit" class="search-btn" value="搜索">
		<span class="search-icon"></span>
		<a href="javascript:;" class="clear-btn">×</a>
		<label class="hide-text" for="q"></label>
	</form>

	<table class="content">
		<thead>
			<tr>
				<th width="15%">{%if $usertype == 1%}委托方{%else%}承运方{%/if%}</th>
				<th width="15%">船信息</th>
				<th width="13%">箱型/箱量</th>
				<th width="15%">装货地</th>
				<th width="14%">装/卸货时间</th>
				<th width="13%">订单状态</th>
				<th width="15%">操作</th>
			</tr>
		</thead>
		<tbody>
			{%if count($orders) == 0%}
			{%else%}
				{%foreach $orders as $item%}
					<tr class="item-head">
						<th colspan="7">
							<span class="left">提单号: {%$item.tidan_code%}</span>
							<span class="left">{%if $item.create_time != '0000-00-00 00:00:00'%}成单时间: {%$item.create_time%}{%/if%}</span>
							<span class="right">运单号: {%$item.yundan_code%}</span>
						</th>
					</tr>
					<tr class="item-content">
						<td>
							<p class="top">{%$item.company_name%}</p>
							<p class="bottom">{%$item.contactName%} {%$item.contactNumber%}</p>
							<span class="icon-export"></span>  
						</td>
						<td>
							<p>
								{%if $item.ship_info.full_english_name%}
									{%$item.ship_info.full_english_name%}
								{%else%}
									<p>待完善</p>
								{%/if%}
							</p>
						</td>
						<td>
							{%$number = 0%}
							{%foreach $item.box_info as $box%}
								{%if $box.box_number != 0%}
									{%$number = $number + 1%}
									<p>{%$box.box_number%} * <span class="box-type">{%$boxType[$box.box_type]%}</span></p>
								{%/if%}
							{%/foreach%}
							{%if $number == 0%}
								<p>待完善</p>
							{%/if%}
						</td>
						<td>
							{%if count($item.product_adress_detail)%}
								<p>{%$item.product_adress_detail[0].address%}</p>
								{%if count($item.product_adress_detail) >= 2%}
									<a href="/order/details?orderid={%$item.orderid%}" class="blue" target="_blank">更多地址</a>
								{%/if%}
							{%else%}
								<p>待完善</p>
							{%/if%}
						</td>
						<td>
							{%if count($item.product_adress_detail)%}
								{%if isset($item.product_adress_detail[0].supply_time) && count($item.product_adress_detail[0].supply_time)%}
									<p>{%$item.product_adress_detail[0].supply_time[0]%}</p>
								{%/if%}
								{%if isset($item.product_adress_detail[0].supply_time) && count($item.product_adress_detail[0].supply_time) >= 2%}
									<a href="/order/details?orderid={%$item.orderid%}" class="blue" target="_blank">更多时间</a>
								{%/if%}
							{%else%}
								<p>待完善</p>
							{%/if%}
						</td>
						<td>
							<p class="orange">{%if $item.status != 2%}{%$orderType[$item.status]%}{%else%}待提箱{%/if%}</p>
							<a href="/order/details?orderid={%$item.orderid%}" class="blue clearfix" target="_blank">订单详情</a>
							{%if $item.status > 3 && $item.status != 8%}<a href="/order/trace?orderid={%$item.orderid%}" class="blue clearfix" target="_blank">物流详情</a>{%/if%}
						</td>
						<td class="last">
							{%if $usertype == 2%}
								<!-- 货代 -->
								{%if $item.status == 3%}
									<a href="javascript:;" class="blue user-btn clearfix">下载箱号/铅封号</a>
								{%elseif $item.status == 6%}
									<a href="javascript:;" class="blue user-btn btn_remark clearfix" data-odid="{%$item.orderid%}">评价</a>
								{%/if%}
								
							{%else%}
								<!-- 车队 -->
								{%if $item.status == 1%}
									<a href="/carteam/order/complete?orderid={%$item.orderid%}" target="_blank" class="blue clearfix">完善订单</a>
								{%elseif $item.status == 2%}
									<a href="/order/details?orderid={%$item.orderid%}&dispatch=1" target="_blank" class="blue clearfix">分配/调度</a>
								{%elseif $item.status == 3%}
									<a href="/order/details?orderid={%$item.orderid%}&dispatch=1" target="_blank" class="blue clearfix">分配/调度</a>
									<a href="/order/details?orderid={%$item.orderid%}" target="_blank" class="blue clearfix">产装确认</a>
									<a href="/carteam/order/product_address_list?orderid={%$item.orderid%}" target="_blank" class="blue clearfix">打印产装联系单</a>
								{%elseif $item.status == 4%}
									<a href="/carteam/order/product_address_list?orderid={%$item.orderid%}" class="blue clearfix">打印产装联系单</a>
								{%elseif $item.status == 5%}
								{%elseif $item.status == 6%}
								{%elseif $item.status == 7%}
									{%if $item.order_total_percent%}
										<ul class="star-wrapper clearfix" title="{%$REMARK_TXT[$item.order_total_percent]%}">
											<li {%if $item.order_total_percent > 0%}class="on"{%/if%}><span></span></li>
											<li {%if $item.order_total_percent > 1%}class="on"{%/if%}><span></span></li>
											<li {%if $item.order_total_percent > 2%}class="on"{%/if%}><span></span></li>
											<li {%if $item.order_total_percent > 3%}class="on"{%/if%}><span></span></li>
											<li {%if $item.order_total_percent > 4%}class="on"{%/if%}><span></span></li>
										</ul>
									{%/if%}
								{%/if%}
							{%/if%}
						</td>
					</tr>
				{%/foreach%}
			{%/if%}
		</tbody>
	</table>
	{%if count($orders) == 0 %}
		{%if $data.user.usertype == 2%}
				{%if $data.data.search == 0 && $data.data.orderStatus == 0%}
					<div id="no_order_list_f" class="">
						<div>
							<p>暂无订单，请<a class="choose_order" href="/freight/order/choose" target="_blank">发起订单</a></p>
							<p class="tel-text"></p>
						</div>
					</div>
				{%else%}
					<div id="no_order_list_f" class="">
						<div>
							<p>未找到订单</p>
							<p class="tel-text"></p>
						</div>
					</div>
				{%/if%}
		{%else%}
			{%if $data.data.search == 0 && $data.data.orderStatus == 0%}
				<div id="no_order_list_c" class="">
					<div>
						<p>暂无订单，可邀请货代客户下单，或联系客服添加货代客户</p>
						<p class="tel-text">客服电话<a href="tel:400-8666">400-8666</a></p>
					</div>
				</div>
			{%else%}
					<div id="no_order_list_f" class="">
						<div>
							<p>未找到订单</p>
							<p class="tel-text"></p>
						</div>
					</div>
				{%/if%}
		{%/if%}
	{%/if%}
	{%if $data.data.total_page >= 2%}
		{%widget name="common/widget/pager/pager.tpl" total=$data.data.total_page%}
	{%/if%}
</div>


{%script type="text/javascript"%}
require('order/widget/view/order_list/order_list').init('{%$usertype%}', '{%if !empty($data.data.searchType)%}{%$data.data.searchType%}{%/if%}');
{%/script%}