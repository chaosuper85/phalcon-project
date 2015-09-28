{%$user = $data.data.personalInfo.user%}
{%widget name="user/widget/header_content/header_content.tpl" title="个人信息" class="person"%}
<div id="user_info">
    <div class="user-content">
        <dl>
            <dd class="clearfix">
                <div class="title">头像</div>
                <div class="content">
                    <div class="head-img-content">
                        <img id="head-img" src="/static/user/static/image/img1.png"/>
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
            <dd class="clearfix">
                <div class="title">用户分组</div>
                {%if $user.group_name%}
                <div class="content">{%$user.group_name%}</div>
                {%else%}
                <div class="content none">暂无</div>
                {%/if%}
                <div class="tip invisible">
                    <i></i>
                </div>
            </dd>
            <dd class="clearfix edit-dd">
                <div class="title">真实姓名</div>
                {%if $user.real_name%}
                <div id="name" class="content show">{%$user.real_name%}</div>
                {%else%}
                <div id="name" class="content show none">暂无</div>
                {%/if%}
                <input type="text" id="input-name" class="input0 input-edit hidden" value="">
                <div class="tip invisible">
                    <i></i>
                </div>
            </dd>
            <dd class="clearfix edit-dd">
                <div class="title">联系方式</div>
                <div id="phone" class="content show">{%$user.contactNumber%}</div>
                <input type="text" id="input-phone" class="input1 input-edit hidden" value="">
                <div class="tip invisible">
                    <i></i>
                </div>
            </dd>
            <dd class="clearfix edit-dd">
                <div class="title" >座机</div>
                {%if $user.telephone_number%}
                <div id="telephone" class="content show">{%$user.telephone_number%}</div>
                {%else%}
                <div id="telephone" class="content show none">暂无</div>
                {%/if%}
                <input type="text" id="input-telephone" class="input2 input-edit hidden" value="">
                <div class="tip invisible">
                    <i></i>
                </div>
            </dd>
            <dd class="edit">
                <div class="content">
                     <div class="btn-wrap ">
                         <a href="javascript:;" id="edit-info" class="user-btn">编辑资料</a>
                         <a href="javascript:;" id="submit-info" class="user-btn submit-btn">确认提交</a>
                     </div>
                </div>
            </dd>
        </dl>
    </div>
</div>


{%script type="text/javascript"%}
	require('user/widget/view/account_info/account_info').init();
{%/script%}