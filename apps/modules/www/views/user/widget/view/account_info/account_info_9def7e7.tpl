{%$user = $data.data.personalInfo.user%}

{%widget name="user/widget/header_content/header_content.tpl" title="个人信息" class="person"%}
<div id="user_info">
    <div class="user-content">
         <div class="status">
            {%if $data.user.status == 2%}
                <span class="red">您的资料审核未通过，请联系客服400-969-6790</span>
            {%elseif $data.user.status == 3%}
                <span>您的资料正在审核，请耐心等待，如有问题请拔打客服热线400-969-6790</span>
            {%elseif $data.user.status == 4%}
                恭喜，您的资料已审核通过！可使用<a href="/order/list">陆运服务</a>
            {%else%}
                请先上传<a href="/account/enterpriseInfo">公司信息</a>
            {%/if%}
        </div>
        <dl>
            <dd class="clearfix">
                <div class="title">头像</div>
                <div class="content">
                    <div class="head-img-content">
                        <img id="head-img" src="/static/user/static/image/img1_6fdbde3.png"/>
                    </div>
                    <div class="btn-wrap btn-head hidden">
                        <a href="javascript:;" class="user-btn">上传图片</a>
                        <input type="file" name="IMAGE" multiple="" id="upload-headimg" accept="image/*"/>
                        <div class="info-wrap">
                            <p>支持jpg.jpeg.bmp.gif格式照片</p>
                            <p>pdf扫描文件，大小不超过20MB</p>
                        </div>
                    </div>
                </div>
            </dd>
            <dd class="clearfix">
                <div class="title">用户名</div>
                <div class="content">{%$user.username%}</div>
            </dd>
            <dd class="clearfix">
                <div class="title">手机号</div>
                <div class="content">{%$user.mobile%}</div>
            </dd>
            <!-- <dd class="clearfix">
                <div class="title">用户分组</div>
                {%if $user.group_name%}
                    <div class="content">{%$user.group_name%}</div>
                {%else%}
                    <div class="content none">暂无</div>
                {%/if%}
                <div class="tip invisible">
                    <i></i>
                </div>
            </dd> -->
            <dd class="clearfix edit-dd">
                <div class="title">真实姓名</div>
                {%if $user.real_name%}
                    <div id="txt_name" class="content content_txt">{%$user.real_name%}</div>
                {%else%}
                    <div id="txt_name" class="content content_txt none">暂无</div>
                {%/if%}
                <input type="text" id="input-name" class="input0 input-edit hidden" placeholder="请输入真实姓名" maxlength="10">
                <div class="tip invisible"><i></i></div>
            </dd>
            <dd class="clearfix edit-dd">
                <div class="title">联系方式</div>
                {%if $user.real_name%}
                    <div id="txt_phone" class="content content_txt">{%$user.contactNumber%}</div>
                {%else%}
                    <div id="txt_phone" class="content content_txt none">暂无</div>
                {%/if%}
                <input type="text" id="input-phone" class="input1 input-edit hidden" placeholder="请输入11位手机号" maxlength="11">
                <div class="tip invisible"><i></i></div>
            </dd>
            <dd class="clearfix edit-dd">
                <div class="title" >座机</div>
                {%if $user.telephone_number%}
                    <div id="txt_telephone" class="content content_txt">{%$user.telephone_number%}</div>
                {%else%}
                    <div id="txt_telephone" class="content content_txt none">暂无</div>
                {%/if%}
                <input type="text" id="input-telephone" class="input2 input-edit hidden" placeholder="区号-座机号(例如：010-87654321)" maxlength="15">
                <div class="tip invisible"><i></i></div>
            </dd>
            <dd class="edit">
                <div class="content">
                     <div class="btn-wrap ">
                         <a href="javascript:;" id="userinfo_edit" class="user-btn">编辑资料</a>
                         <a href="javascript:;" id="userinfo_submit" class="user-btn submit-btn">确认提交</a>
                     </div>
                </div>
            </dd>
        </dl>
    </div>
</div>


{%script type="text/javascript"%}
	require('user/widget/view/account_info/account_info')();
{%/script%}