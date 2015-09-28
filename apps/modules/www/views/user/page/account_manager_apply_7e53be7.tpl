{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>管理员申请 - 箱典典</title>
{%/block%}

{%block name="block_head_static"%}
    {%require name="user/static/style/common.less"%}
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl"%}
    <div class="wrapper-wide clearfix bottom50">
    	{%widget name="user/widget/nav_user/nav_user.tpl" title="我的公司"%}
    	<div class="wrapper right">
    		{%widget name="user/widget/view/apply_manager/apply_manager.tpl"%}
    	</div>
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}