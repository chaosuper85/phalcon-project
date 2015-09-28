{%$orders = $data.data.assign_list%}
{%$box_type = ['','开顶箱','框架箱','冷冻箱','挂衣箱']%}
{%$box_size_type =['','20GP','40GP','40HQ']%}

<div id="product_address_list">
	<div class="clearfix header-content">交易订单>运单{%$data.data.orderInfo.yundan_code%}>打印产装联系单</div>
	<div class="clearfix load-list">
		<a href="/freight/order/export_box_info?orderid={%$data.data.orderInfo.orderid%}" target="_blank" class="btn-load user-btn">导出箱号/铅封号</a>
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
                            <a href="/carteam/order/product_address?orderboxid={%$item.box_id%}" target="_blank" class="print blue clearfix ">查看产装联系单</a>
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