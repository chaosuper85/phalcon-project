{%widget name="user/widget/header_content/header_content.tpl" title="账户安全" class="account"%}
<div id="user-account-security">
    <div class="user-content">
		<dl>
			<dd class="clearfix">
                <div class="title">手机号:</div>
                <div class="content">
                    <span class="info">{%$data.user.mobile%}</span>
                    <a href="javascript:;" class="change-link" id="change-mobile">更改</a>
                </div>
            </dd>
			<dd class="clearfix">
                <div class="title">密码:</div>
                    <div class="content">
					    <a href="/user/changeBindPass" class="change-link" id="change-pass">修改密码</a>
					</div>
			</dd>
    </div>
</div>


{%script type="text/javascript"%}
		require('user/widget/view/account_security/account_security').init();
{%/script%}