{%$enterpriseType = ['','车队用户','货代用户']%}

{%$userType = $data.user.usertype%}
{%widget name="user/widget/header_content/header_content.tpl" title="我的公司" class="company"%}
<div id="user-company" class="user-content">
	<div class="info">
        {%if $data.data.companyInfo.enterprise%}
            <div class="status">
                {%if $data.user.status == 2%}
                    <span class="red">
                    您的资料审核未通过，请联系客服400-969-6790
                    </span>
                {%/if%}
                {%if $data.user.status == 3%}
                    您的资料正在审核，请耐心等待，如有问题请拔打客服热线400-969-6790
                {%/if%}
                {%if $data.user.status == 4%}
                    恭喜，您的资料已审核通过！可使用<a href="/order/list">陆运服务</a>
                {%/if%}
            </div>
            {%if $data.user.status == 3||$data.user.status == 4%}
                <dl>
                    <dd class="clearfix">
                        <span class="title">企业全称</span>
                        <div class="content">
                            <span class="txt">{%$data.data.companyInfo.enterprise.enterpriseName%}</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">企业类型</span>
                        <div class="content">
                            <span class="txt">{%$enterpriseType[{%$data.data.companyInfo.enterprise.type%}]%}</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">所在城市</span>
                        <div class="content">
                            <span class="txt">{%$data.data.companyInfo.enterprise.cityName%}</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">成立时间</span>
                        <div class="content">
                            <span class="txt">{%$data.data.companyInfo.enterprise.buildDate%}</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">区号-座机-分机</span>
                        <div class="content">
                            <span class="txt">{%$data.data.companyInfo.enterprise.contactMobile%}</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">营业执照号</span>
                        <div class="content">
                            <span class="txt">{%$data.data.companyInfo.enterprise.licenceNumber%}</span>
                        </div>
                    </dd>
                    <dd class="clearfix">
                        <span class="title">营业执照附件</span>
                        <div class="content">
                            <img src="{%$data.data.companyInfo.enterprise.licencePic%}" alt="图1" class="zhizhao license">
                        </div>
                    </dd>
                </dl>
            {%else%}
                {%if $data.user.status == 2%}
                    <dl>
                        <dd class="clearfix">
                            <span class="title">企业全称</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <input type="text" id="enterpriseName" value={%$data.data.companyInfo.enterprise.enterpriseName%} />
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">企业类型</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <div id="type-selector">
                                    </div>
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">所在城市</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <div id="address-selector" data-pid={%$data.data.companyInfo.enterprise.provinceId%} data-cid={%$data.data.companyInfo.enterprise.cityCode%} data-aid="0" >

                                    </div>
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">成立时间</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <div id="company_date_selectBox" data-time={%$data.data.companyInfo.enterprise.buildDate%}></div>
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">区号-座机-分机</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <input type="text" id="contactMobile-city" class="input-short input-must" value={%$data.data.companyInfo.enterprise.contactMobile_city%} >
                                    - <input type="text" id="contactMobile-number" class="input-long input-must" value={%$data.data.companyInfo.enterprise.contactMobile_number%} >
                                    - <input type="text" id="contactMobile-fenji" class="input-short" value={%$data.data.companyInfo.enterprise.contactMobile_fenji%} >

                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">营业执照号</span>
                            <div class="content">
                                <div class="edit-wrap">
                                    <input type="text" id="licenseNumber" value={%$data.data.companyInfo.enterprise.licenceNumber%} />
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                        <dd class="clearfix">
                            <span class="title">营业执照附件</span>
                            <div class="content">
                                <div class="edit-wrap upload-license">
                                    <a href="javascript:;" class="user-btn">上传图片</a>
                                    <input type="file" name="license" multiple="" id="upload-license">
                                </div>
                                <span class="info-wrap">
                                    <p>支持图片/pdf格式文件</p>
                                    <p>大小不超过2MB</p>
                                </span>
                                    <div class="img-license-content ">
                                    <img src={%$data.data.companyInfo.enterprise.licencePic%} alt="图1" class="license" id="img-license">
                                    <a href="javascript:;" id="img-clean" title="关闭" ></a>
                                </div>
                            </div>
                            <div class="tip invisible">
                                <i></i>
                            </div>
                        </dd>
                    </dl>
                {%/if%}
                <div class="user-btn-wrap">
                    <a href="javascript:;" class="user-btn" id="submitInfo">确认提交</a>
                </div>
            {%/if%}
         {%else%}
            <dl>
                <dd class="clearfix">
                    <span class="title">企业全称</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <input type="text" id="enterpriseName"/>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">企业类型</span>
                    <div class="content">
                        <div class="edit-wrap">
                             <div id="type-selector">
                             </div>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">所在城市</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <div id="address-selector">

                            </div>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">成立时间</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <div id="company_date_selectBox" data-time=""></div>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">区号-座机-分机</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <input type="text" id="contactMobile-city" class="input-short input-must" />
                            - <input type="text" id="contactMobile-number" class="input-long input-must" />
                            - <input type="text" id="contactMobile-fenji" class="input-short" />

                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">营业执照号</span>
                    <div class="content">
                        <div class="edit-wrap">
                            <input type="text" id="licenseNumber"/>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
                <dd class="clearfix">
                    <span class="title">营业执照附件</span>
                    <div class="content">
                        <div class="edit-wrap upload-license">
                            <a href="javascript:;" class="user-btn">上传图片</a>
                            <input type="file" name="license" multiple="" id="upload-license">
                        </div>
                        <span class="info-wrap">
                            <p>支持图片/pdf格式文件</p>
                            <p>大小不超过2MB</p>
                        </span>
                            <div class="img-license-content hidden">
                            <img src="" alt="图1" class="license" id="img-license">
                            <a href="javascript:;" id="img-clean" title="关闭" ></a>
                        </div>
                    </div>
                    <div class="tip invisible">
                        <i></i>
                    </div>
                </dd>
            </dl>
            <div class="user-btn-wrap">
                <a href="javascript:;" class="user-btn" id="submitInfo">确认提交</a>
            </div>
        {%/if%}
	</div>
</div>

{%script type="text/javascript"%}
        console.log({%json_encode($data)%})
	require('user/widget/view/account_company/company').init({%$data.user.usertype%});
{%/script%}