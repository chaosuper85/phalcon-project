{%extends file="page/layout/layout.tpl"%}

{%block name="block_head"%}
    <title>货代管理 - 箱典典管理平台</title>
{%/block%}

{%block name="block_head_static"%}
	{%require name="bower_components/jquery/dist/jquery.min.js"%}
	{%require name="bower_components/react/react.min.js"%}
	{%require name="bower_components/react/JSXTransformer.js"%}
	{%require name="assets/lib/antd/antd-0.8.0.min.css"%}
	{%require name="assets/lib/antd/antd-0.8.0.min.js"%}
{%/block%}

{%block name="block_content"%}
	{%widget name="common/widget/nav/nav.tpl" page=3%}
	<div id="main">
		{%widget name="common/widget/header/header.tpl" title="前台用户 - 货代管理"%}
		<section id="main-wrapper">
			{%widget name="user/partial/agent/agent.tpl"%}
		</section>
	</div>
{%/block%}
