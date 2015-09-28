{%extends file="page/layout/layout.tpl"%}

{%block name="block_head"%}
    <title>堆场详情 - 箱典典管理平台</title>
{%/block%}

{%block name="block_head_static"%}
	{%require name="bower_components/jquery/dist/jquery.min.js"%}
	{%require name="bower_components/react/react.min.js"%}
	{%require name="bower_components/react/JSXTransformer.js"%}
	{%require name="assets/lib/antd/antd-0.8.0.min.css"%}
	{%require name="assets/lib/antd/antd-0.8.0.min.js"%}
	{%require name="assets/lib/antd/antd-0.8.0.min.js"%}
	<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=d27d23298c924c68b45ee1f9ef2eddb7"></script>
{%/block%}

{%block name="block_content"%}
	{%widget name="common/widget/nav/nav.tpl" page=7%}
	<div id="main">
		{%if isset($data.yard_name)%}
			{%widget name="common/widget/header/header.tpl" title="资源管理 - 堆场管理 - 编辑"%}
		{%else%}
			{%widget name="common/widget/header/header.tpl" title="资源管理 - 堆场管理 - 新建"%}
		{%/if%}
		<section id="main-wrapper">
			{%widget name="resource/partial/yard_detail/yard_detail.tpl"%}
		</section>
	</div>
{%/block%}
