<?include $_SERVER["DOCUMENT_ROOT"]."/views/homepage/top.php";?>
<link rel="stylesheet" href="/bizM/css/style.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/views/board/bootstrap/css/style.css" />
<link rel="stylesheet" type="text/css" href="/bizM/css/main.css" />
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="/js/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<style type="text/css">
	a.btn-success,button.btn-success,a.btn-primary,button.btn-primary,button.btn-primary i{color:#fff}
	.board h3{width:100%;height:110px;margin-bottom:50px;padding-top:25px;background-color:#fae100;font-size:50px;font-weight:700;letter-spacing:-.08em;text-align:center}
	.board .row{width:1170px;margin:0 auto}
	#fboardlist{width:1170px;margin:0 auto}

	#art05{padding-bottom:0}
	section{position:relative;width:100%;height:100%;margin:0 auto}
	section:after{content:"";display:block;clear:both}
	article{width:1170px;height:100%;margin:0 auto;font-size:16px}
	fieldset{display:block;-webkit-margin-start:2px;-webkit-margin-end:2px;-webkit-padding-before:0.35em;-webkit-padding-start:0.75em;-webkit-padding-end:0.75em;-webkit-padding-after:0.625em;min-width:-webkit-min-content;border-width:2px;border-style:groove;border-color:threedface;border-image:initial;padding:40px 0}
   table{margin:0 auto;width:1170px}

	img, fieldset{border:none}
	#skipNavi, .blind{position:absolute;left:0;top:0;width:0;height:0;font-size: 0;line-height:0;text-indent:-9999px;overflow:hidden;visibility:hidden}
	legend{display:block;-webkit-padding-start: 2px;-webkit-padding-end:2px;border-width:initial;border-style:none;border-color:initial;border-image:initial}
	input, select{padding:0 10px;line-height:30px;height:30px;border:1px solid #ccc}
	img, input{vertical-align:middle;box-sizing:border-box}

	.art05 form{padding:0}
	.art05 tbody tr{width:896px;height:52px;border-bottom:1px solid #ccc;}
	.art05 tbody tr:first-of-type{border-top:3px solid #ccc;}
	.art05 tbody tr.tr_file{height:90px}
	.art05 tbody tr.tr_file td{padding:10px 20px;width:50px}
	.art05 tbody tr.tr_file input{margin:2px 0;width:210px;display:inline-block}

	.art05 tbody tr.tr_txt{height:230px;}
	.art05 tbody tr th{padding-left:10px;background:#f2f2f2;box-sizing:border-box}
	.art05 tbody tr td{padding-left:20px;box-sizing:border-box}
	.art05 input,.art05 select{padding:0 10px;line-height:30px;border:1px solid #ccc;box-sizing:border-box}
	.art05 option{padding:0 10px;line-height:20px;}
	.art05 textarea{border:1px solid #ccc;margin:15px 0}
	.art05 .star{text-align:right;padding:10px 8px}
	.art05 div{width:348px;margin:30px auto 100px;font-size:16px}

	.art05 div button{width:154px;height:50px;border:1px solid #efefef;background:#fae100;margin-right:10px;cursor:pointer;transition:all 0.3s;font-weight:600}
	.art05 div button:hover{background:#3b1e1e;color:#fae100}
</style>

		<!-- 본문 시작 -->
		<h1>상담신청</h1>
		<div id="art05" class="art05">
		<section class="art05">
			<article class="art05" id=ssub5 name=ssub5>
				<form name=weneedweb method="post" enctype='multipart/form-data'>
				<input type=hidden name=lee value=jiyong>
				<input type=hidden name=mode value="insert">
				<fieldset>
					<legend class="blind">상담신청</legend>
					<table>
						<caption class="blind">카카오 알림톡 접수폼</caption>
						<tbody>
							<tr>
								<th width=220>회사명 <span>*</span></th>
								<td><input type="text" name=OM_company_name style="width:77%"/></td>
								<th width=220>담당자명 <span>*</span></th>
								<td><input type="text" name=OM_name style="width:77%"/></td>
							</tr>
							<tr>
								<th>이메일 <span>*</span></th>
								<td colspan="3"><input type="text" name=OM_email /></td>
							</tr>
							<tr>
								<th>휴대폰번호 <span>*</span></th>
								<td colspan="3"><input type="text" name=OM_hp1 size="7" maxlength="3" title="번호앞자리" /> - <input type="text" name=OM_hp2 size="7" maxlength="4" title="번호중간자리" /> - <input type="text" name=OM_hp3 size="7" maxlength="4" title="번호뒷자리" /></td>
							</tr>
							<tr>
								<th>전화번호</th>
								<td colspan="3"><input type="text" name=OM_tel1 size="7" maxlength="3" title="번호앞자리" /> - <input type="text" name=OM_tel2 size="7" maxlength="4" title="번호중간자리" /> - <input type="text" name=OM_tel3 size="7" maxlength="4" title="번호뒷자리" /></td>
							</tr>
							<tr>
								<th>카카오 플러스 친구이름</th>
								<td><input type="text" name=OM_plus_name style="width:77%"/></td>
								<th>카카오 검색용 아이디</th>
								<td><input type="text" name=OM_search_id style="width:77%"/></td>
							</tr>
							<tr>
								<th>카카오 홈 URL</th>
								<td colspan="3"><input type="text" name=OM_kakao_url style="width:90%" /></td>
							</tr>
							<tr>
								<th>회사 홈 도메인</th>
								<td colspan="3"><input type="text" name=OM_domain style="width:90%" /></td>
							</tr>
							<tr>
								<th>문자발송 건수(월) <span>*</span></th>
								<td><input type="text" name=OM_ea_month style="text-align:right;width:80%"/></td>
								<th>호스팅 종류</th>
								<td>
								<select name=OM_hosting_type title="호스팅 종류" style="width:77%">
									<option value="웹호스팅">웹호스팅</option>
									<option value="서버호스팅(자체서버)">서버호스팅(자체서버)</option>
									<option value="모름">모름</option>
								</select>
								</td>
							</tr>
							<tr class="tr_file">
								<th>첨부파일</th>
								<td colspan="3">File 1 : <input type="file" name="OM_file1" style="width:60%"><br />File 2 : <input type="file" name="OM_file2" style="width:60%"></td>
							</tr>
							<tr class="tr_txt">
								<th>의뢰사항</th>
								<td colspan="3"><textarea name="OM_contents" id="" style="width:99%" rows="10"></textarea></td>
							</tr>
						</tbody>
					</table>
					<p class="star">*필수로 입력해야할 내용입니다.</p>
					<div>
						<button type=button onclick="gosubmit()">작성완료</button>
						<button type=button onclick="weneedweb.reset()">다시쓰기</button>
					</div>
				</fieldset>
				</form>
			</article>
		</section>
		<!-- 본문 끝 -->
		<?include $_SERVER["DOCUMENT_ROOT"]."/views/homepage/bottom.php";?>