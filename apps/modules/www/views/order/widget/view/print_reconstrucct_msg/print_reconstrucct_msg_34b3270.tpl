{%$freight = $data.data.freight_agent_company_info%}
{%$carteam = $data.data.carrier_company_info%}
{%$yundan = $data.data.yundan_code%}
{%$tidan = $data.data.tidan_code%}
{%$shipInfo = $data.data.ship_info%}
{%$boxType = ['','开顶箱','框架箱','冷冻箱','挂衣箱']%}

<div id="order_complete_rec">
	<!-- 面包屑导航开始 -->
	<div class="breadcrumb">
		<ol>
			<li><a href="/">交易订单</a></li>
			<li>></li>
			<li class="active">完善订单</li>
		</ol>
	</div>
	<!-- 面包屑导航结束 -->
	<!-- 订单信息开始 -->
	<div class="clearfix order-number">
		<span class="number-left">日期：{%$data.data.create_time%}</span>
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
					{%$freight.unverify_enterprisename%}
					<input id="freight_id" type="hidden" value="{%$freight.contactName%}"/>
					<input type="hidden" value="{%$freight.unverify_enterprisename%}" id="freight_name" name="freight_name" readonly="readonly" data-id="{%$data.data.freightagent_user%}"/>
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
					{%$carteam.unverify_enterprisename%}
					<input id="carteam_id" type="hidden" value="{%$carteam.contactName%}"/>
					<input type="hidden" value="{%$carteam.unverify_enterprisename%}" id="carteam_name" name="carteam_name" readonly="readonly" class="" data-id="{%$data.data.carrier_userid%}"/>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>承运方必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
		</div>
	</div>
	<!-- 委托方/承运商结束 -->
	
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
					<input type="text" value="{%if $shipInfo.ship_company_china_name%}{%$shipInfo.ship_company_china_name%}{%else if $shipInfo.ship_company_english_name%}{%$shipInfo.ship_company_english_name%}{%/if%}" id="ship_company" name="ship_company" placeholder="请输入船公司" data-id="{%$data.data.shipping_company_id%}"/>
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
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="{%$tidan%}" id="tidan" name="tidan" />
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
					<input type="text" value="{%if $shipInfo.ship_china_name%}{%$shipInfo.ship_china_name%}{%else if $shipInfo.ship_english_name%}{%$shipInfo.ship_english_name%}{%/if%}" id="ship_name" name="ship_name" placeholder="请输入船名" data-id="{%$data.data.ship_name_id%}" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>船名必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_num">
						<span class="name">船次</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="{%$data.data.ship_ticket%}" id="ship_num" name="ship_num" placeholder="请输入船次" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>船次必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
				<div class="item-name">
					<label for="ship_yard">
						<span class="name">指定堆场</span>
						<span class="icon-require">*</span>
					</label>
				</div>
				<div class="item-content">
					<input type="text" value="{%$data.data.yard_name%}" id="ship_yard" name="ship_yard" placeholder="请输入指定堆场" data-id="{%$data.data.yard_id%}"/>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>指定堆场必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
			<div class="item clearfix">
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
			</div>
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
					{%$boxType[$data.data.product_box_type]%}
					<input type="hidden" value="{%$data.data.product_box_type%}" class="product_type" id="product_type" name="product_type"/>
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
					<div class="box_type_text">
						<ul class="clearfix">
							<li><label for="product_20gp">20GP*{%$data.data.box_20gp_count%}</label></li>
							<li><!-- 
								<a class="decrease" href="javaScript:">－</a> -->
								<input class="product_20gp" id="product_20gp" type="hidden" value="{%$data.data.box_20gp_count%}" disabled="disabled" /><!-- 
								<a class="plus" href="javaScript:">＋</a> -->
							</li>
							<li><label for="product_40gp">40GP*{%$data.data.box_40gp_count%}</label></li>
							<li><!-- 
								<a class="decrease" href="javaScript:">－</a> -->
								<input class="product_40gp" id="product_40gp" type="hidden" value="{%$data.data.box_40gp_count%}" disabled="disabled" /><!-- 
								<a class="plus" href="javaScript:">＋</a> -->
							</li>
							<li><label for="product_40hg">40HQ*{%$data.data.box_40hq_count%}</label></li>
							<li><!-- 
								<a class="decrease" href="javaScript:">－</a> -->
								<input class="product_40hg" id="product_40hg" type="hidden" value="{%$data.data.box_40hq_count%}" disabled="disabled" /><!-- 
								<a class="plus" href="javaScript:">＋</a> -->
							</li>
						</ul>
					</div>
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>箱型箱量必填</div>
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
					<input type="text" value="{%$data.data.product_name%}" id="product_name" name="product_name" />
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
					<input type="text" value="{%$data.data.product_weight%}" id="product_weight" name="product_weight" class="weight" disabled="disabled" />公斤
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
					<input type="text" value="{%$data.data.product_desc%}" id="product_remark" name="product_remark" />
				</div>
				<div class="item-message clearfix">
					<div class="error-message hidden"><i class="icon-warn"></i>备注信息必填</div>
					<div class="right-message hidden"><i class="icon-right"></i></div>
				</div>
			</div>
		</div>
	</div>
	<!-- 货物信息结束 -->
	<button class="order-save">确认重建</button>
</div>


{%script type="text/javascript"%}
	require('order/widget/view/print_reconstrucct_msg/print_reconstrucct_msg').init('{%$data.data.id%}', '{%$tidan%}');
{%/script%}
























