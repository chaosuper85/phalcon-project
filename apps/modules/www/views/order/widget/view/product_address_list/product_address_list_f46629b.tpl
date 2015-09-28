{%$orders = $data.data.assign_list%}
{%$box_type = ['','普货箱','开顶箱','框架箱','冷藏箱','危险品','灌箱']%}
{%$box_size_type =['','20GP','40GP','40HQ']%}

<div id="product_address_list">
	<div class="clearfix header-content"><h2>陆运服务</h2><a href="/order/list">交易订单</a>><a href="/order/details?orderid={%$data.data.orderInfo.orderid%}">订单详情：运单{%$data.data.orderInfo.yundan_code%}</a>>下载装货单</div>
	<div class="clearfix load-list">
		<a href="/carteam/order/download_all_proContacts?orderid={%$data.data.orderInfo.orderid%}" target="_blank" class="btn-load user-btn">下载全部装货单</a>
	</div>
	<div class="clearfix list-content">
		<table class="list-product">
			<thead>
				<tr>
					<th width="15%">箱型</th>
					<th width="20%">箱号</th>
					<th width="20%">铅封号</th>
					<th width="10%">司机</th>
					<th width="15%">车牌号</th>
					<th class="operate" width="20%">操作</th>
				</tr>
			</thead>
			<tbody>
                {%foreach $orders as $item%}
                    <tr class="scope">
                        <td>{%$box_type[$item.box_type]%}&nbsp;{%$box_size_type[$item.box_size_type]%}</td>
                        <td>{%$item.box_code%}</td>
                        <td>{%$item.box_ensupe%}</td>
                        <td>{%$item.driver_name%}</td>
                        <td>{%$item.car_number%}</td>
                        <td>
                            <a href="/carteam/order/product_address?orderboxid={%$item.box_id%}" target="_blank" class="print blue clearfix ">查看装货单</a>
                        </td>
                    </tr>
                {%/foreach%}
			</tbody>
		</table>
	</div>
</div>

{%script type="text/javascript"%}
require('order/widget/view/product_address_list/product_address_list').init();
{%/script%}