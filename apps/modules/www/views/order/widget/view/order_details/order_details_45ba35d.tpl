
{%$status = $data.data.orderStatus%}
{%$utype = $data.user.usertype%}
{%$orderInfo = $data.data.order_info%}
{%$boxType = ['','20GP','40GP','40HQ']%}
{%$addressInfo = $data.data.address_assign_info%}
{%$orderType = ['','待确认','待提箱','待装货','待运抵','已落箱','已运抵待评价','交易成功','交易关闭']%}

<div id="order_details">
	<!-- 面包屑导航开始 -->
	<div class="breadcrumb">
		<ol>
			<li><h3>陆运服务</h3></li>
			<li><a href="/order/list?orderid={%$data.data.order_fregiht_id%}">订单列表></a></li>
			<li><a href="/order/list">交易订单></a></li>
			<li class="active">运单{%$data.data.yundan%}</li>
		</ol>
	</div>
	<!-- 面包屑导航结束 -->
	<!-- 订单信息开始 -->
	<!-- <div class="clearfix order-number">
		<span class="number-left">提单号: {%$data.data.tidan%}</span>
		<span class="number-right">运单号{%$data.data.yundan%}</span>
	</div> -->
	<!-- 订单信息结束 -->
	<div class="order-title">
		<span class="status">{%$orderType[$status]%}</span>
		<span class="num-tidan">提单号: {%$data.data.tidan%}</span>
		<div class="right">
			{%if $utype == 1%}
				<p class="name">委托方 : {%$data.data.freight.name%}</p>
				<p class="contact">联系人 : {%$data.data.freight.contactName%}<span>{%$data.data.freight.contactNumber%}</span></p>
			{%else%}
				<p class="name">承运方 : {%$data.data.carteam.name%}</p>
				<p class="contact">联系人 : {%$data.data.carteam.contactName%}<span>{%$data.data.carteam.contactNumber%}</span></p>
			{%/if%}
		</div>
	</div>
	{%if $data.dispatch != 1 && $data.dispatch != 2%}
		<div class="order-funcs clearfix">
			{%if $utype == 1 && ($status < 6)%}
				<a href="javascript:;" id="quit_order" class="order-btn style2">退载</a>
				<a href="/carteam/order/reConstruct_msg?orderid={%$data.data.order_fregiht_id%}" id="reconstruct_orderid" class="order-btn style2">退载重建</a>
			{%/if%}
			{%if $status == 4 || $status == 5 || $status == 6%}
				<a href="/carteam/order/product_address_list?orderid={%$data.data.order_fregiht_id%}" class="order-btn style1">打印产装联系单</a>
			{%/if%}
			{%if $utype == 1 && ($status == 2 || $status == 3 || $status == 4 || $status == 5)%}
				<a href="/order/details?orderid={%$data.data.order_fregiht_id%}&dispatch=1" target="_blank" class="order-btn style1" id="btn_dispatch">分配/调度</a>
			{%/if%}
			{%if $utype == 1 && $status == 1%}
				<a href="/carteam/order/complete?orderid={%$data.data.order_fregiht_id%}" class="order-btn style1">完善订单</a>
			{%/if%}
		</div>
	{%/if%}
	{%if $data.dispatch != 1 && $data.dispatch != 2%}
		{%widget name="order/widget/com/order_file_download/order_file_download.tpl"%}
	{%/if%}
	<div class="order-wrap dispatch-info {%if $utype == 2 && $status == 1%}hidden{%/if%}">
		<h3 class="second-title">调度信息</h3>
		{%widget name="order/widget/view/order_details/partial/box_info/box_info.tpl"%}
	</div>

	<div class="order-wrap order-info">
		<h3 class="second-title">船务信息<span class="edit-records right" data-orderid="{%$data.data.order_fregiht_id%}"><a href="/order/records?orderid={%$data.data.order_fregiht_id%}" target="_blank">修改纪录</a></span></h3>
		<div class="order-info-wrap clearfix">
			<ul>
				<li><label>起运港</label><span>{%$orderInfo.ship_info.dock_city_code%}</span></li>
				<li><label>船公司</label><span>{%$orderInfo.ship_info.ship_company_name%}</span></li>
				<li><label>提单号</label><span>{%$orderInfo.ship_info.tidan_code%}</span></li>
			</ul>
			<ul>
				<li><label>船名/航次</label><span>{%$orderInfo.ship_info.ship_name%}/{%$orderInfo.ship_info.ship_ticket%}</span></li>
				<li><label>船期备注</label><span>{%$orderInfo.ship_info.ship_ticket_desc%}</span></li>
				<li><label>运抵堆场</label><span>{%$orderInfo.ship_info.yard_name%}</span></li>
			</ul>
		</div>
		<h3 class="second-title">货物信息</h3>
		<div class="order-info-wrap clearfix">
			<ul>
				<li><label>货品名称</label><span>{%$orderInfo.product_info.product_name%}</span></li>
				<li><label>箱体类型</label><span>{%$orderInfo.product_info.product_box_type%}</span></li>
			</ul>
			<ul>
				<li class="box-type clearfix"><label>箱型箱量</label>
					{%foreach $orderInfo.product_info.box_type_number as $box%}
						{%if 1 || $box.number != 0%}
							<div><span>{%$boxType[$box.type]%}</span> * {%$box.number%}</div>
						{%/if%}
					{%/foreach%}
				</li>
				<li><label>货物重量</label><span>{%$orderInfo.product_info.product_weight%} KG</span></li>
			</ul>
		</div>
		<h3 class="second-title">产装信息
			{%if $utype == 1 && ($status == 2 || $status == 3)%}
				<div class="btn-wrap right">
					<a href="javascript:;" id="modify_productInfo">修改产装信息</a>
				</div>
			{%/if%}
		</h3>
		<div class="order-info-wrap clearfix">
			{%if count($orderInfo.address_info) != 0 %}
				{%foreach $orderInfo.address_info as $i => $address%}
					<ul>
						<h4 class="title">产装信息-{%$i+1%}</h4>
						<li><label>装箱地址</label><span>{%$address.box_address_detail%}</span></li>
						<li><label>联系人</label><span>{%$address.contactName%}</span></li>
						<li><label>联系电话</label><span>{%$address.contactNumber%}</span></li>
						<li>
							<label>装箱日期</label>
							<div class="time">
								{%foreach $address.box_date as $date%}
									<p>{%$date.product_supply_time%}</p>
								{%/foreach%}
							</div>
						</li>
					</ul>
					{%if $i%2 == 1%}
						<div class="clear"></div>
					{%/if%}
				{%/foreach%}
			{%/if%}
			
			
		</div>
	</div>
	
</div>


{%script type="text/javascript"%}
	require('order/widget/view/order_details/order_details').init({%json_encode($orderInfo.address_info)%}, '{%$data.data.order_fregiht_id%}', '{%$data.dispatch%}');
{%/script%}

