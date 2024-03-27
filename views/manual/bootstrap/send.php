<!-- 3차 메뉴 -->
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/views/biz/inc/send_menu7.php');
?>
<!-- //3차 메뉴 -->
<div class="wrap_manual">
  <div class="s_tit">알림톡 쉽게 따라하기</div>
  <div class="manual_box">
    <video class="send_video" width="1200" controls>
      <source src="/dhn/movie/altalk_20201201.mp4" type="video/mp4">
    </video>
    <div class="manual_message1">
      <div class="message1_main">
        <img src="/images/manual/message1_img.jpg" />
          <div class="message1_text">
            <p class="tit mg_t30">
              <span>START.</span> &nbsp;&nbsp;좌메뉴 > 메시지 발송 > 이미지 , 텍스트 알림톡 선택
            </p>

            <p class="tit mg_t30">
              <span>STEP 1.</span> &nbsp;버튼타입 선택(탬플릿)
            </p>
            <p class="text">
              - 알림톡 템플릿 버튼 타입을 선택해주세요. 알림톡 버튼 변경하기 버튼을 클릭하시면 다양한 버튼 타입을 선택하실 수 있습니다.
            </p>
            <p class="tit">
              <span>STEP 2.</span> &nbsp;발송내용 입력
            </p>
            <p class="text">
                - 노란색 박스 영역(변수값)만 작성하시면 됩니다. 내용저장 기능을 사용하셨을 경우 불러오기 버튼으로 이전 메시지 내용을 불러오실 수 있습니다.
            </p>
          </div>
          <div class="arrow1">
            <img src="/images/manual/arrow1.png" />
            <img src="/images/manual/arrow1.png" />
          </div>
        </div>
        <div class="message1_img">
          <img src="/images/manual/message2.jpg" />
          <img src="/images/manual/message3.jpg" width="210px"/>
        </div>

    </div>
    <div class="maunal_message2">
      <div class="message2_main">
          <img src="/images/manual/message4_img.jpg" />
        <div class="message2_img">
          <img src="/images/manual/message5.jpg" />
          <img src="/images/manual/message6.jpg" />
          <div class="arrow2">
            <img src="/images/manual/arrow2.png" />
            <img src="/images/manual/arrow3.png" />
          </div>
        </div>
        </div>
        <div class="message2_text">
          <p class="tit mg_t30">
            <span>STEP 3.</span> &nbsp;버튼내용 선택
          </p>
          <p class="text">
              - 버튼링크는 스마트전단(지니에서 미리제작), 에디터사용(사진+텍스트 편집), 스마트쿠폰, 직접입력(외부링크입력)으로 최대 3개까지 선택하실 수 있습니다.
          </p>
          <p class="text">
              - 스마트전단 사용시에는 [ 전단불러오기 ] 버튼 클릭시 미리 작성해 놓은 전단목록을 불러옵니다. (발송 전 미리 작성바랍니다.)
          </p>
          <p class="text">
              - 에디터 사용시 이미지등록(예 : 전단이미지 업로드) 및 텍스트 편집이 가능합니다.
          </p>
        </div>
    </div>
    <div class="maunal_message3">
      <div class="message3_main">
        <img src="/images/manual/message7_img.jpg" />
        <img src="/images/manual/message8.jpg" />
        <div class="arrow3">
          <img src="/images/manual/arrow4.png" />
        </div>
      </div>
      <div class="message3_text">
        <p class="tit mg_t30">
          <span>STEP 4.</span> &nbsp;2차 발송 선택
        </p>
        <p class="text">
            - 알림톡 실패건을 문자 (SMS/LMS)로 재발송합니다.
        </p>
        <p class="text">
            - 링크 가져오기 버튼 클릭시 알림톡 버튼 링크 정보를 가져옵니다.
        </p>
        <p class="text">
            - 알림톡내용 가져오기 버튼 클릭시 알림톡 발송내용을 가져옵니다.
        </p>
        <p class="tit mg_t30">
          <span>STEP 5.</span> &nbsp;수신고객 선택
        </p>
        <p class="text">
            - 미리 등록한 그룹선택  또는 엑셀 파일 업로드, 직접입력 으로 선택 가능합니다.
        </p>
        <p class="text">
            - 알림톡내용 가져오기 버튼 클릭시 알림톡 발송내용을 가져옵니다.
        </p>
      </div>
    </div>
    <div class="maunal_message4">
      <div class="message4_img">
        <img src="/images/manual/message9_img.jpg" />
        <div class="message4_text">
          <p class="tit mg_t30">
            <span>  선택사항. </span> &nbsp;예약발송 선택
          </p>
          <p class="text">
              - 원하는 날짜와 시간에맞춰 발송을예약하실 수 있습니다.
          </p>
          <p class="tit mg_t30">
            <span> 선택사항. </span> &nbsp;테스트발송
          </p>
          <p class="text">
              - 최종발송 전 테스트로 발송을하실 수 있습니다.
          </p>
          <p class="text">
              - 미리 등록된관리자번호로 보내시려면 번호추가 버튼을 클릭하시면 됩니다.
          </p>
          <p class="text">
              - 직접 전화번호를 입력 후 전화번호추가 버튼을 클릭하시면 추가로 발송하실수있습니다.
          </p>
          <p class="tit mg_t30">
            <span>END.</span> &nbsp;알림톡 발송
          </p>
          <p class="text">
              - 설정하신내용으로 알림톡을 발송합니다.
          </p>
          <p class="tip_text">
              * 스마트전단이나 에디터를 사용한 <span>전단링크는 발송 후에도 내용수정이 가능</span>합니다.
          </p>
        </div>
      </div>
    </div>

  </div>
</div>
