{%extends file="page/layout/layout.tpl"%}

{%block name="block_head"%}
    <title>订单详情 - 箱典典管理平台</title>
{%/block%}

{%block name="block_head_static"%}
	{%require name="bower_components/jquery/dist/jquery.min.js"%}
	{%require name="bower_components/react/react.min.js"%}
	{%require name="bower_components/react/JSXTransformer.js"%}
	{%require name="assets/lib/antd/antd-0.8.0.min.css"%}
	{%require name="assets/lib/antd/antd-0.8.0.min.js"%}
	{%require name="www/common/static/style/base.less"%}
    {%require name="www/common/static/script/jquery.json.js"%}
    {%require name="www/common/static/script/boot.js"%}
{%/block%}

{%block name="block_content"%}
	{%widget name="common/widget/nav/nav.tpl" page=1%}
	<div id="main">
		{%widget name="common/widget/header/header.tpl" title="跟单系统 - 订单详情"%}
		<section id="main-wrapper">
			{%widget name="www/order/widget/view/order_details/order_details.tpl" title="跟单系统 - 订单详情"%}
		</section>
	</div>
{%/block%}
