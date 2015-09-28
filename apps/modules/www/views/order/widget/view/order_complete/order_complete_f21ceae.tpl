{%$freight = $data.data.freight%}
{%$carteam = $data.data.carteam%}
{%$yundan = $data.data.yundan%}
{%$tidan = $data.data.tidan%}
{%$product_box_type = $data.data.product_box_type%}
<div id="order_complete">
	<!-- 面包屑导航开始 -->
	<div class="breadcrumb">
		<ol>
			<li><a href="/order/list">交易订单</a></li>
			<li>></li>
			<li class="active">完善订单</li>
		</ol>
	</div>
	<!-- 面包屑导航结束 -->
	<!-- 订单信息开始 -->
	<div class="clearfix order-number">
		<span class="number-left">日期：{%$data.data.create_date%}</span>
		<span class="number-right">运单号{%$yundan%}</span>
	</div>
	<!-- 订单信息结束 -->
	<!-- 委托方/承运商开始 -->
	<div class="carteam_freight">
		<div class="title">
			<h3>委托方／承运方</h3>
		</div>
		<div class="content">
			<div class="item clearfix">
				<div class="item-name">
					<label for="freight_name">
						<span class="name">委托方</span>
					</label>
				</div>
				<div class="item-content">
					<input id="freight_id" type="hidden" value="{%$freight.id%}"/>
					<input type="text" value="{%$freight.name%}" id="freight_name" name="freight_name" readonly="readonly" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>委托方必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="carteam_name">
						<span class="name">承运方</span>
					</label>
				</div>
				<div class="item-content">
					<input id="carteam_id" type="hidden" value="{%$carteam.id%}"/>
					<input type="text" value="{%$carteam.name%}" id="carteam_name" name="carteam_name" readonly="readonly" class="" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>承运方必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
		</div>
	</div>
	<!-- 委托方/承运商结束 -->
	<!-- 下载文件开始 -->
	{%widget name="order/widget/com/order_file_download/order_file_download.tpl"%}
	<!-- 下载文件结束 -->
	<!-- 船期开始 -->
	<div class="ship_info">
		<div class="title">
			<h3>船期</h3>
		</div>
		<div class="content">
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_company">
						<span class="name">船公司</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="ship_company" name="ship_company" placeholder="请输入船公司"/>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>船公司必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="tidan">
						<span class="name">提单号</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="{%$tidan%}" id="tidan" name="tidan" readonly="readonly" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>提单号必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_name">
						<span class="name">船名</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="ship_name" name="ship_name" placeholder="请输入船名" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>船名必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_num">
						<span class="name">航次</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="ship_num" name="ship_num" placeholder="请输入船次" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>船次必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_yard">
						<span class="name">运抵堆场</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<div id="ship_yard" class="selectBox"></div>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>运抵堆场必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<!-- <div class="item clearfix">
				<div class="item-name">
					<label for="ship_remark">
						<span class="name">备注</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="ship_remark" name="ship_remark" placeholder="请输入备注" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>备注必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div> -->
		</div>
	</div>
	<!-- 船期结束 -->
	<!-- 货物信息开始 -->
	<div class="product_info">
		<div class="title">
			<h3>货物信息</h3>
		</div>
		<div class="content">
			<div class="item clearfix">
				<div class="item-name">
					<label for="product_type">
						<span class="name">货物箱型</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<div id="product_type" class="selectBox"></div>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>货物箱型必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item box_type clearfix">
				<div class="item-name">
					<label>
						<span class="name">箱型箱量</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<p class="message-tip">此处箱型箱量请填写提箱单上给出的总箱量</p>
					<div class="box_type_text">
						<ul class="clearfix">
							<li><label for="product_20gp">20GP</label></li>
							<li>
								<a class="decrease" href="javaScript:">－</a>
								<input class="product_20gp" id="product_20gp" type="text" value="0" />
								<a class="plus" href="javaScript:">＋</a>
							</li>
							<li><label for="product_40gp">40GP</label></li>
							<li>
								<a class="decrease" href="javaScript:">－</a>
								<input class="product_40gp" id="product_40gp" type="text" value="0" />
								<a class="plus" href="javaScript:">＋</a>
							</li>
							<li><label for="product_40hg">40HQ</label></li>
							<li>
								<a class="decrease" href="javaScript:">－</a>
								<input class="product_40hg" id="product_40hg" type="text" value="0" />
								<a class="plus" href="javaScript:">＋</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>箱量总数不能为0或不能超过1000000</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="product_name">
						<span class="name">货物名称</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="product_name" name="product_name" maxlength="26"/>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>货物名称必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="product_weight">
						<span class="name">货物重量</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="product_weight" name="product_weight" class="weight" />KGS
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>货物重量必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="product_remark">
						<span class="name">备注信息</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="" id="product_remark" name="product_remark" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>备注信息必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
		</div>
	</div>
	<!-- 货物信息结束 -->
	<!-- 装货地址开始 -->
	<div class="address_info">
		<div class="title">
			<h3>装货地址</h3>
		</div>
		<div class="content">
			<dl class="address-item clearfix" data-item='1'>
				<dd class="address-title">
					<div class="address-name">
						<h4>装货地址<span class="num">1</span></h4>
					</div>
					<div class="address-del"></div>
				</dd>
				<dt class="item package_date clearfix">
					<div class="item-name">
						<label for="package_date">
							<span class="name">装货时间</span>
							<span class="icon-require">*</span>
						</label>
					</div>
					<div class="item-content">
						<div class="package_date_selectBox data_1 clearfix" data-flag="1"></div>
						<a class="add-date" href="javaScript:">+增加其它装货时间</a>
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>装货时间必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<dt class="item clearfix">
					<div class="item-name">
						<label for="package_location">
							<span class="name">装货地</span>
							<span class="icon-require">*</span>
						</label>
					</div>
					<div class="item-content">
						<div class="package_location"></div>
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>装货地必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<dt class="item clearfix">
					<div class="item-name">
						<label for="package_address">
							<span class="name">详细地址</span>
							<span class="icon-require">*</span>
						</label>
					</div>
					<div class="item-content">
						<input type="text" value="" class="package_address" id="package_address" name="package_address" />
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>详细地址必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<dt class="item clearfix">
					<div class="item-name">
						<label for="linkman">
							<span class="name">工厂联系人</span>
							<span class="icon-require">*</span>
						</label>
					</div>
					<div class="item-content">
						<input type="text" value="" class="linkman" id="linkman" name="linkman" placeholder="工厂联系人"/>
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>工厂联系人必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<dt class="item clearfix">
					<div class="item-name">
						<label for="contact">
							<span class="name">联系方式</span>
							<span class="icon-require">*</span>
						</label>
					</div>
					<div class="item-content">
						<input type="text" value="" id="contact" name="contact" class="contact" placeholder="请输入联系方式" />
					</div>
					<div class="item-message clearfix">
						<div class="error-message hidden"><i class="icon-warn"></i>联系方式必填</div>
						<div class="right-message hidden"><i class="icon-right"></i></div>
					</div>
				</dt>
				<div class="clearfix address-bottom-line"></div>
			</dl>
			<button class="add-address" type="button">+增加新的装货地址</button>
		</div>
	</div>
	<!-- 装货地址结束 -->
	<button class="order-save">确认接单</button>
</div>


{%script type="text/javascript"%}
	require('order/widget/view/order_complete/order_complete').init('{%$data.data.order_fregiht_id%}',{%json_encode($product_box_type)%}, {%json_encode($data.data.yard_info)%});
{%/script%}
























