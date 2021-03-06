{%$box=$data.data%}
{%$shipInfo=$data.data.ship_info%}
{%$productInfo=$data.data.product_info%}
{%$addressInfo=$data.data.address_info%}
{%$carteamInfo=$data.data.carteam_info%}
{%$boxType = ['','20GP','40GP','40HQ']%}
{%$boxName = ['','开顶箱','框架箱','冷冻箱','挂衣箱']%}

<div id="print_product_address">
	<!-- {%$data|@print_r%} -->
	<div class="print-header">
		<h3>陆运服务</h3>
		<span> 交易订单> 运单{%$box.yundan_code%}> 打印产装联系单 > 产装联系单详情</span>
	</div>
	<div class="download">
		<a href="/carteam/order/download/product_address?boxid={%$box.id%}" id="download_address" class="btn-download">下载产装单</a>
	</div>
	<div class="print-content">
		<div class="print-title">
			<h2>{%$box.carrier_company_name%}</h2>
			<p>装货联系单</p>
		</div>
		<div class="print-date">
			<div class="left">
				<label>日期：</label>
				<p>{%$box.create_time%}</p>
			</div>
			<div class="right">
				<label>运单号：</label>
				<p>{%$box.yundan_code%}</p>
			</div>
		</div>
		<div class="print-table">
			<table>
				<tbody>
					<tr class="tr-title">
						<td colspan="4"><h3>船信息</h3></td>
					</tr>
					<tr>
						<td class="title">起运港：</td>
						<td>{%$shipInfo.dock_city_code%}</td>
						<td class="title">船名/航次:</td>
						<td>{%$shipInfo.ship_name%}</td>
					</tr>
					<tr>
						<td class="title">船公司：</td>
						<td>{%$shipInfo.ship_company_name%}</td>
						<td class="title">船期:</td>
						<td>{%$shipInfo.ship_ticket%}</td>
					</tr>
					<tr>
						<td class="title">提单号：</td>
						<td>{%$shipInfo.tidan_code%}</td>
						<td class="title">运抵堆场:</td>
						<td>{%$shipInfo.yard_name%}</td>
					</tr>
					<tr class="tr-title">
						<td colspan="4"><h3>货物信息</h3></td>
					</tr>
					<tr>
						<td class="title">货品名称：</td>
						<td>{%$productInfo.product_name%}</td>
						<td class="title">箱型:</td>
						<td>{%$boxType[$productInfo.box_type_number.box_size_type]%}  <span>{%$boxName[$productInfo.box_type_number.product_box_type]%}</span></td>
					</tr>
					<tr>
						<td class="title">箱体类型：</td>
						<td>普通货箱</td>
						<td class="title">货物重量:</td>
						<td>{%$productInfo.product_weight%}</td>
					</tr>
					{%foreach $addressInfo as $i =>$address%}
					<tr class="tr-title">
						<td colspan="4"><h3>装货信息-{%$i+1%}</h3></td>
					</tr>
					<tr>
						<td class="title">装箱日期：</td>
						<td class="date">
							{%foreach $address.box_date as $date%}
								<p>{%$date%}</p>
							{%/foreach%}
						</td>
						<td class="title">到厂时间:</td>
						<td></td>
					</tr>
					<tr>
						<td class="title">装箱地址：</td>
						<td>{%$address.box_address%}</td>
						<td class="title">签字:</td>
						<td></td>
					</tr>
					<tr>
						<td class="title">联系人：</td>
						<td>{%$address.contactName%}</td>
						<td class="title">离厂时间:</td>
						<td></td>
					</tr>
					<tr>
						<td class="title">联系方式：</td>
						<td>{%$address.contactNumber%}</td>
						<td class="title">签字:</td>
						<td></td>
					</tr>
					{%/foreach%}
					<tr class="tr-title">
						<td colspan="4"><h3>调度信息</h3></td>
					</tr>
					<tr>
						<td class="title">司机姓名：</td>
						<td>{%$carteamInfo.name%}</td>
						<td class="title">车牌号:</td>
						<td>{%$carteamInfo.car_number%}</td>
					</tr>
					<tr>
						<td class="title">箱号：</td>
						<td>{%$carteamInfo.box_number%}</td>
						<td class="title">铅封号:</td>
						<td>{%$box.box_ensupe%}</td>
					</tr>
					<tr>
						<td class="title">箱型：</td>
						<td>{%$boxType[$carteamInfo.box_type]%}</td>
						<td class="title"></td>
						<td></td>
					</tr>
					<tr class="tr-title">
						<td colspan="4"><h3>备注：</h3></td>
					</tr>
					<tr>
						<td colspan="4" class="notice">
							<p></p>
							<p></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="bottom-print">
			<a href="/carteam/order/download/product_address?boxid={%$box.id%}" id="print_address" class="btn-download">打印产装单</a>
		</div>
	</div>
</div>

{%script type="text/javascript"%}
	require('order/widget/view/print_product_address/print_product_address').init();
{%/script%}









