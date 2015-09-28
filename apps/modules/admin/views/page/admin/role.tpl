{%extends file="page/layout/layout.tpl"%}

{%block name="block_head"%}
    <title>角色管理 - 权限管理 - 箱典典管理平台</title>
{%/block%}

{%block name="block_head_static"%}
	{%require name="bower_components/jquery/dist/jquery.min.js"%}
	{%require name="bower_components/react/react.min.js"%}
	{%require name="bower_components/react/JSXTransformer.js"%}
	{%require name="assets/lib/antd/antd-0.8.0.min.css"%}
	{%require name="assets/lib/antd/antd-0.8.0.min.js"%}
{%/block%}

{%block name="block_content"%}
	{%widget name="common/widget/nav/nav.tpl" page=6 third=1%}
	<div id="main">
		{%widget name="common/widget/header/header.tpl" title="后台用户 - 权限管理 - 角色管理"%}
		<section id="main-wrapper">
			{%widget name="admin/partial/role/role.tpl"%}
		</section>
	</div>
{%/block%}
