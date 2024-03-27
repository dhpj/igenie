<form id="voucher-form" action="/untact/save" method="post">
  <div>
      <label>이름</label>
      <input type="text" name="user_name" placeholder="이름">
  </div>
  <div>
      <label>전화번호</label>
      <input type="text" name="tel" placeholder="01012341234">
  </div>
  <div>
      <label>이메일</label>
      <input type="text" name="email" placeholder="email.com">
  </div>
  <div>
      <label>회사 또는 단체</label>
      <input type="text" name="company_name" placeholder="회사 또는 단체">
  </div>
  <div>
      <label>도입 인원</label>
      <select name="employees_num">
        <option value="unselected" selected="">선택안됨</option>
        <option value="1-19">1-19</option>
        <option value="20-49">20-49</option>
        <option value="50-99">50-99</option>
        <option value="100-299">100-299</option>
        <option value="300-499">300-499</option>
        <option value="500-999">500-999</option>
        <option value="1000-">1000-</option>
      </select>
  </div>
  <div>
    <label>고객 유형</label>
    <select name="customer_type">
     <option value="unselected" selected="">선택안됨</option>
     <option value="신규">신규 고객</option>
     <option value="기존">기존 고객</option>
    </select>
  </div>
  <div>
    <label>도입 목적 및 문의 내용</label>
    <textarea name="content" placeholder="도입 목적 (예: 52시간제 관리, 직원의 외근 관리 등) 및 기타 문의 사항을 적어주세요."></textarea>
  </div>
  <div class="text-center">
    <button class="btn btn-sm">지원사업 신청하기</button>
  </div>
  <input type="hidden" name="state" value="신청">
</form>
