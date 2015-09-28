{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>账户安全 - 箱典典</title>
{%/block%}

{%block name="block_head_static"%}
    {%require name="user/static/style/common.less"%}
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page="2"%}
    <div class="wrapper-wide clearfix bottom50">
    		{%widget name="user/widget/view/account_security/change_pass.tpl"%}
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}