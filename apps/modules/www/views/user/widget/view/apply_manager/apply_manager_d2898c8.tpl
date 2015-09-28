{%widget name="user/widget/header_content/header_content.tpl" title="申请成为企业管理员"%}
<div id="user-admin-apply">
	<div class="user-content">
		<h3>企业信息</h3>
		<dl>
			<dd class="clearfix">
				<div class="title"><span class="must">*</span>企业名称</div>
				<div class="content">
					<input type="text" id="input-company"/>
				</div>
			</dd>
			<dd class="clearfix">
				<div class="title"><span class="must">*</span>营业执照注册号</div>
				<div class="content">
					<input type="text" id="input-licence"/>
				</div>
			</dd>
			<dd class="clearfix">
				<div class="title"><span class="must">*</span>营业执照扫描件</div>
				<div class="content">
					<div class="btn-wrap">
						<a href="javascript:;" class="user-btn">上传图片</a>
						<input id="upload-licence" type="file" name="FILES" multiple>
					</div>
					<div class="info-wrap">
						<p>请上传营业执照清晰彩色原件扫描件或照片</p>
						<p>支持.jpg.jpeg.bmp.gif格式照片.pdf扫描文件，大小不超过20MB</p>
					</div>
				</div>
			</dd>
			<dd class="clearfix">
				<div class="title"><span class="must">*</span>企业账户管理申请公函</div>
				<div class="content">
					<div class="btn-wrap">
						<a href="javascript:;" class="user-btn">上传图片</a>
						<input id="upload-official" type="file" name="FILES" multiple>
					</div>
					<div class="info-wrap">
						<p>请下载<a href="">《企业账户管理申请公函》</a>，按指定要求填写信息</p>
						<p>请上传加盖企业公章（不能是财务章，私章）的申请公函扫描件或照片</p>
					</div>
				</div>
			</dd>
		</dl>
	</div>

	<div class="user-content">
		<h3>管理员信息</h3>
		<dl>
			<dd class="clearfix">
				<div class="title"><span class="must">*</span>管理员姓名</div>
				<div class="content">
					<input type="text" id="input-name"/>
				</div>
			</dd>
			<dd class="clearfix">
				<div class="title"><span class="must">*</span>管理员身份证号码</div>
				<div class="content">
					<input type="text" id="input-id"/>
				</div>
			</dd>
			<dd class="clearfix">
				<div class="title"><span class="must">*</span>管理员手机号码</div>
				<div class="content">
					<span class="user-mobile">185****8888</span>
					<a href="javascript:;" class="user-btn">发送短信</a>
				</div>
			</dd>
			<dd class="clearfix">
				<div class="title"><span class="must">*</span>手机验证码</div>
				<div class="content">
					<input type="text" id="input-sms"/>
				</div>
			</dd>
		</dl>
	</div>
	
	<div class="submit-wrap">
		<a href="javascript:;" class="user-btn" id="input-submit">提交审核</a>
		<span class="submit-tip">输入错误</span>
	</div>
</div>

{%script type="text/javascript"%}
	require('user/widget/view/apply_manager/apply_manager').init();
{%/script%}