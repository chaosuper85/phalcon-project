{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>修改记录 - 箱典典</title>
{%/block%}

{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl"%}
    <div class="wrapper-wide clearfix bottom50">
    		{%widget name="order/widget/view/modify_record/modify_record.tpl"%}
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}