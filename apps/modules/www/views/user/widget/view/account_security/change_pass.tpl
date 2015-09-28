<div id="change-pass">
    <h2 class="header-content">账户安全&nbsp;>&nbsp;修改密码</h2>
    <div class="change-pass-content">
       <dl>
          <dd class="clearfix">
             <div class="title">当前密码</div>
             <div class="content">
                <input type="password" id="pass" maxlength="16"/>
                <div class=" tip " id="tip-pass">请输入当前密码</div>
             </div>
          </dd>
          <dd class="clearfix">
             <div class="title">新密码</div>
             <div class="content">
                <input type="password" id="new-pass" maxlength="16"/>
                <div class=" tip " id="tip-new-pass">请输入6~16位字符，区分大小写</div>
             </div>
          </dd>
          <dd class="clearfix">
             <div class="title">重复新密码</div>
             <div class="content">
                <input type="password" id="new-repass" maxlength="16"/>
                <div class=" tip " id="tip-new-repass">请再次输入密码</div>
             </div>
          </dd>
       </dl>
       <a href="javascript:;" class="next-step user-btn ">确定</a>
    </div>
</div>




{%script type="text/javascript"%}
        console.log({%json_encode($data)%})
		require('user/widget/view/account_security/change_pass').init();
{%/script%}