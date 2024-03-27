<?
// header("Access-Control-Allow-Origin: http://14.43.241.107/");
	$title_cnt = 1; //상품타이틀수
	$goods_num_cnt = 1; //한페이지 상품수

	$print_ladscape = 0 ;
	//$goods_cnt = 1; //상품수
	$data_tit_img = "N"; //POP타이틀 이미지 여부
	$data_img_yn = "Y"; //이미지 사용여부
	$data_option_yn = "N"; //옵션명 사용여부
    $option_type_yn = "N"; //옵션타입여부
	$data_org_price_yn = "N"; //정상가 사용여부
    $data_org_price_fix = "N"; //정상가 폰트크기 고정여부
	$data_pop_name = "POP제목"; //POP제목
	$base_goods_tit = ""; //기본값 POP제목
	$base_goods_name = "딸기 한소쿠리(500g)"; //기본값 상품명
	$base_goods_option = ""; //기본값 옵션명
	$base_goods_org_price = ""; //기본값 정상가
	$base_goods_price = "9,900원"; //기본값 할인가
	$data_date_yn = "N"; //행사기간 사용여부
	$base_goods_date = "행사기간_". date("Y.m.d") ."~". date("m.d", strtotime("+1 week")); //기본값 행사기간

	if($type == "1_01"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$goods_num_cnt = 1;
		$base_goods_tit = "슈퍼특가세일"; //기본값 POP제목
		$data_date_yn = "Y"; //행사기간 사용여부
	}else if($type == "1_02"){
		if(empty($goods_cnt)){
			$goods_cnt = 2;
		}
		$goods_num_cnt = 2;
		//$goods_cnt = 2; //상품수
		$base_goods_tit = "타임세일"; //기본값 POP제목
		$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "1_03"){
		if(empty($goods_cnt)){
			$goods_cnt = 3;
		}
		//$goods_cnt = 3; //상품수
		$goods_num_cnt = 3;
		$base_goods_tit = "오늘의핫딜"; //기본값 POP제목
		$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "1_04"){
		if(empty($goods_cnt)){
			$goods_cnt = 4;
		}
		//$goods_cnt = 4; //상품수
		$goods_num_cnt = 4;
		$base_goods_tit = "슈퍼세일"; //기본값 POP제목
		$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "1_05"){
		if(empty($goods_cnt)){
			$goods_cnt = 5;
		}
		$goods_num_cnt = 5;
		// $goods_cnt = 5; //상품수
		$base_goods_tit = "행사특가"; //기본값 POP제목
	}else if($type == "1_06"){
		$title_cnt = 2; //상품 타이틀수
		//$goods_cnt = 2; //상품수
		if(empty($goods_cnt)){
			$goods_cnt = 2;
		}
		$goods_num_cnt = 2;
		$base_goods_tit = "타임세일"; //기본값 POP제목
		$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "4_01"){
		$data_poptit_yn = "N"; // POP제목 사용여부
			$title_cnt = 2; //상품 타이틀수
			//$goods_cnt = 2; //상품수
			if(empty($goods_cnt)){
				$goods_cnt = 2;
			}
			$goods_num_cnt = 2;
			// $base_goods_tit = "타임세일"; //기본값 POP제목
			$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "4_02"){
		$data_poptit_yn = "N"; // POP제목 사용여부
			$title_cnt = 3; //상품 타이틀수
			//$goods_cnt = 2; //상품수
			if(empty($goods_cnt)){
				$goods_cnt = 3;
			}
			$goods_num_cnt = 3;
			// $base_goods_tit = "타임세일"; //기본값 POP제목
			$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "4_03"){
		$data_poptit_yn = "N"; // POP제목 사용여부
			$title_cnt = 4; //상품 타이틀수
			//$goods_cnt = 2; //상품수
			if(empty($goods_cnt)){
				$goods_cnt = 4;
			}
			$goods_num_cnt = 4;
			// $base_goods_tit = "타임세일"; //기본값 POP제목
			$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "4_04"){
		$data_poptit_yn = "N"; // POP제목 사용여부
			$title_cnt = 4; //상품 타이틀수
			//$goods_cnt = 2; //상품수
			if(empty($goods_cnt)){
				$goods_cnt = 4;
			}
			$print_ladscape = 1;
			$goods_num_cnt = 4;
			// $base_goods_tit = "타임세일"; //기본값 POP제목
			$data_date_yn = "N"; //행사기간 사용여부
    }else if($type == "4_05"){
		$base_goods_tit = "행사상품"; //기본값 POP제목
        $base_goods_name = "즉석식품류"; //기본값 상품명
        $data_option_yn = "Y"; //옵션명 사용여부
        $option_type_yn = "Y"; //옵션타입여부
        $base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
        $base_goods_price = "9,900~19,900"; //기본값 할인가
			$title_cnt = 2; //상품 타이틀수
			//$goods_cnt = 2; //상품수
			if(empty($goods_cnt)){
				$goods_cnt = 2;
			}
			$goods_num_cnt = 2;
			// $base_goods_tit = "타임세일"; //기본값 POP제목
			$data_date_yn = "N"; //행사기간 사용여부
            $data_img_yn = "N"; //이미지 사용여부
	}else if($type == "4_06"){
		$base_goods_tit = "행사상품"; //기본값 POP제목
        $base_goods_name = "즉석식품류"; //기본값 상품명
        $data_option_yn = "Y"; //옵션명 사용여부
        $option_type_yn = "Y"; //옵션타입여부
        $base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
        $base_goods_price = "9,900~19,900"; //기본값 할인가
			$title_cnt = 3; //상품 타이틀수
			//$goods_cnt = 2; //상품수
			if(empty($goods_cnt)){
				$goods_cnt = 3;
			}
			$goods_num_cnt = 3;
			// $base_goods_tit = "타임세일"; //기본값 POP제목
			$data_date_yn = "N"; //행사기간 사용여부
            $data_img_yn = "N"; //이미지 사용여부
	}else if($type == "4_07"){
        $print_ladscape = 1;
		$base_goods_tit = "행사상품"; //기본값 POP제목
        $base_goods_name = "즉석식품류"; //기본값 상품명
        $data_option_yn = "Y"; //옵션명 사용여부
        $option_type_yn = "Y"; //옵션타입여부
        $base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
        $base_goods_price = "9,900~19,900"; //기본값 할인가
			$title_cnt = 4; //상품 타이틀수
			//$goods_cnt = 2; //상품수
			if(empty($goods_cnt)){
				$goods_cnt = 4;
			}
			$goods_num_cnt = 4;
			// $base_goods_tit = "타임세일"; //기본값 POP제목
			$data_date_yn = "N"; //행사기간 사용여부
            $data_img_yn = "N"; //이미지 사용여부
	}else if($type == "4_08"){
		$base_goods_tit = "행사상품"; //기본값 POP제목
        $base_goods_name = "즉석식품류"; //기본값 상품명
        $data_option_yn = "Y"; //옵션명 사용여부
        $option_type_yn = "Y"; //옵션타입여부
        $base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
        $base_goods_price = "9,900~19,900"; //기본값 할인가
			$title_cnt = 8; //상품 타이틀수
			//$goods_cnt = 2; //상품수
			if(empty($goods_cnt)){
				$goods_cnt = 8;
			}
			$goods_num_cnt = 8;
			// $base_goods_tit = "타임세일"; //기본값 POP제목
			$data_date_yn = "N"; //행사기간 사용여부
            $data_img_yn = "N"; //이미지 사용여부
	}else if($type == "4_09"){
        if(empty($goods_cnt)){
			$goods_cnt = 4;
		}
        $title_cnt = 4;
		$goods_num_cnt = 4;
		$base_goods_tit = "전단상품"; //기본값 POP제목
		// $data_date_yn = "Y"; //행사기간 사용여부
	}else if($type == "1_07"){
		//$goods_cnt = 4; //상품수
		if(empty($goods_cnt)){
			$goods_cnt = 4;
		}
		$goods_num_cnt = 4;
		$base_goods_tit = "오늘의 행사상품"; //기본값 POP제목
		$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "1_08"){
		if(empty($goods_cnt)){
			$goods_cnt = 3;
		}
		$goods_num_cnt = 3;
		$title_cnt = 3; //상품 타이틀수
		//$goods_cnt = 3; //상품수
		$base_goods_tit = "행사상품"; //기본값 POP제목
		$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "1_09"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$goods_num_cnt = 1;
		$data_img_yn = "N"; //이미지 사용여부
		$base_goods_tit = "행사상품"; //기본값 POP제목
        $data_org_price_yn = "Y"; //정상가 사용여부
        if($blankdata == "N"){
    		$base_goods_org_price = "15,000"; //기본값 정상가
        }
		$base_goods_price = "9,900"; //기본값 할인가
    }else if($type == "1_10"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$goods_num_cnt = 1;
        $data_poptit_yn = "N"; // POP제목 사용여부
		$data_tit_img = "Y"; //POP타이틀 이미지 여부
		$data_img_yn = "N"; //이미지 사용여부

        $data_option_yn = "Y"; //옵션명 사용여부
        $option_type_yn = "Y"; //옵션타입여부

        $data_org_price_yn = "Y"; //정상가 사용여부

		$base_goods_price = "9,900"; //기본값 할인가
        if(!empty($style)){
			if($style=='popbg110_01'){
				$base_goods_name = "동원참치 "; //기본값 상품명
                $data_org_price_fix="Y";
                if($blankdata == "N"){
            		$base_goods_option = "10캔 묶음"; //기본값 옵션명
                }
			}else if($style=='popbg110_02'){
				$base_goods_name = "한우1등급 갈비살/안심"; //기본값 상품명
                $data_org_price_fix="Y";
                if($blankdata == "N"){
                    $base_goods_option = "500g"; //기본값 옵션명
                }
			}
		}else{
			$base_goods_name = "생고등어"; //기본값 상품명
            if($blankdata == "N"){
                $base_goods_option = "1마리(500g)"; //기본값 옵션명
            }
		}
        if($blankdata == "N"){
           $base_goods_org_price = "15,000"; //기본값 정상가
         }
     }else if($type == "1_11"){
         if(empty($goods_cnt)){
             $goods_cnt = 1;
         }
         $goods_num_cnt = 1;
         $data_poptit_yn = "Y"; // POP제목 사용여부
         $data_pop_name = "할인율"; //POP제목
 		 $base_goods_tit = "50"; //기본값 POP제목
         $data_tit_img = "N"; //POP타이틀 이미지 여부
         $data_img_yn = "N"; //이미지 사용여부


         $data_org_price_yn = "Y"; //정상가 사용여부

         $base_goods_price = "9,900"; //기본값 할인가
         if(!empty($style)){
             if($style=='popbg111_01'){
                 $base_goods_name = "깨끗한나라 잘풀리는집 궁 30롤 3겹"; //기본값 상품명
                 $data_org_price_fix="Y";
                 if($blankdata == "N"){
                     $base_goods_option = ""; //기본값 옵션명
                 }
             }else if($style=='popbg111_02'){
                 $base_goods_name = "깨끗한나라 잘풀리는집 궁 30롤 3겹"; //기본값 상품명
                 $data_org_price_fix="Y";
                 if($blankdata == "N"){
                     $base_goods_option = ""; //기본값 옵션명
                 }
             }
         }else{
             $base_goods_name = "깨끗한나라 잘풀리는집 궁 30롤 3겹"; //기본값 상품명
             if($blankdata == "N"){
                 $base_goods_option = ""; //기본값 옵션명
             }
         }
         if($blankdata == "N"){
            $base_goods_org_price = "15,000"; //기본값 정상가
          }
          $data_date_yn = "Y"; //행사기간 사용여부
          $base_goods_date = "10.1.금 ~ 10.3.일";
	}else if($type == "2_01"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$goods_num_cnt = 1;
		$print_ladscape = 1;
		$base_goods_tit = "슈/퍼/특/가"; //기본값 POP제목
		$data_date_yn = "Y"; //행사기간 사용여부
	}else if($type == "2_02"){
		if(empty($goods_cnt)){
			$goods_cnt = 2;
		}
		$goods_num_cnt = 2;
		$print_ladscape = 1;
		//$goods_cnt = 2; //상품수
		$base_goods_tit = "이런 가격은 처음!"; //기본값 POP제목
		$data_date_yn = "N"; //행사기간 사용여부
	}else if($type == "2_03"){
		$data_poptit_yn = "N"; // POP제목 사용여부
		if(empty($goods_cnt)){
			$goods_cnt = 3;
		}
		$goods_num_cnt = 3;
		$print_ladscape = 1;
		//$goods_cnt = 3; //상품수
		$data_tit_img = "Y"; //POP타이틀 이미지 여부
		$base_goods_tit = "특가세일"; //기본값 POP제목
	}else if($type == "2_04"){
		$data_poptit_yn = "N"; // POP제목 사용여부
		if(empty($goods_cnt)){
			$goods_cnt = 4;
		}
		$goods_num_cnt = 4;
		$print_ladscape = 1;
		//$goods_cnt = 4; //상품수
		$data_tit_img = "Y"; //POP타이틀 이미지 여부
		$base_goods_tit = "수량한정세일"; //기본값 POP제목
	}else if($type == "2_05"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$print_ladscape = 1;
		$goods_num_cnt = 1;
		$base_goods_tit = "전단상품"; //기본값 POP제목
		$data_date_yn = "Y"; //행사기간 사용여부
	}else if($type == "3_01"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$print_ladscape = 1;
		$goods_num_cnt = 1;
		$data_img_yn = "N"; //이미지 사용여부
		$base_goods_tit = "행사상품"; //기본값 POP제목
		$base_goods_name = "딸기 한소쿠리(500g)~"; //기본값 상품명
		$base_goods_price = "9,900~19,900"; //기본값 할인가
		$data_date_yn = "Y"; //행사기간 사용여부
	}else if($type == "3_02"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$print_ladscape = 1;
		$goods_num_cnt = 1;
		$data_img_yn = "N"; //이미지 사용여부
		$base_goods_tit = "오늘 딱 하루!"; //기본값 POP제목
		$base_goods_price = "9,900"; //기본값 할인가
	}else if($type == "3_03"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$print_ladscape = 1;
		$goods_num_cnt = 1;
		$data_img_yn = "N"; //이미지 사용여부
		$data_pop_name = "할인율"; //POP제목
		$base_goods_tit = "50"; //기본값 POP제목
		$base_goods_price = "9,900"; //기본값 할인가
	}else if($type == "3_04"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$print_ladscape = 1;
		$goods_num_cnt = 1;
		$data_poptit_yn = "N"; // POP제목 사용여부
		$data_tit_img = "Y"; //POP타이틀 이미지 여부
		$data_img_yn = "N"; //이미지 사용여부

        $data_option_yn = "Y"; //옵션명 사용여부
        $option_type_yn = "Y"; //옵션타입여부
        $data_org_price_yn = "Y"; //정상가 사용여부
		$base_goods_tit = "행사상품"; //기본값 POP제목
		if(!empty($style)){
			if($style=='popbg304_01'){
				$base_goods_name = "한우등심"; //기본값 상품명
                $data_org_price_fix="Y";
			}else if($style=='popbg304_02'){
				$base_goods_name = "한우등심"; //기본값 상품명
                $data_org_price_fix="Y";
			}
		}else{
			$base_goods_name = "딸기 한소쿠리(500g)"; //기본값 상품명
		}

        if($blankdata == "N"){
    		$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
    		$base_goods_org_price = "15,000"; //기본값 정상가
        }
		$base_goods_price = "9,900"; //기본값 할인가
		$data_date_yn = "Y"; //행사기간 사용여부
	}else if($type == "3_05"){
		if(empty($goods_cnt)){
			$goods_cnt = 1;
		}
		$print_ladscape = 1;
		$goods_num_cnt = 1;
		$data_img_yn = "N"; //이미지 사용여부
        $data_option_yn = "Y"; //옵션명 사용여부
        $option_type_yn = "Y"; //옵션타입여부
		$base_goods_tit = "행사상품"; //기본값 POP제목
        if(!empty($style)){
			if($style=='popbg305_02'){
				$base_goods_name = "즉석식품류"; //기본값 상품명
                $data_org_price_fix="Y";
                $data_date_yn = "Y"; //행사기간 사용여부
			}
		}else{
			$base_goods_name = "딸기 한소쿠리(500g)"; //기본값 상품명
		}
		$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
		$base_goods_price = "9,900~19,900"; //기본값 할인가

    }else if($type == "3_06"){
        if(empty($goods_cnt)){
            $goods_cnt = 1;
        }
        $print_ladscape = 1;
        $goods_num_cnt = 1;
        $data_poptit_yn = "N"; // POP제목 사용여부
        $data_tit_img = "Y"; //POP타이틀 이미지 여부
        $data_img_yn = "N"; //이미지 사용여부

        $data_option_yn = "Y"; //옵션명 사용여부
        $option_type_yn = "Y"; //옵션타입여부

        $data_org_price_yn = "Y"; //정상가 사용여부


        $base_goods_tit = "행사상품"; //기본값 POP제목
        if(!empty($style)){
            if($style=='popbg306_01'){
                $base_goods_name = ""; //기본값 상품명
                $data_org_price_fix="Y";
            }else if($style=='popbg306_02'){
                $base_goods_name = "한우등심"; //기본값 상품명
                $data_org_price_fix="Y";
            }
        }else{
            $base_goods_name = "한돈등심"; //기본값 상품명
        }
        if($blankdata == "N"){
            $base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
            $base_goods_org_price = "15,000"; //기본값 정상가
        }
        $base_goods_price = "9,900"; //기본값 할인가
        $data_date_yn = "N"; //행사기간 사용여부
    }else if($type == "3_07"){
        if(empty($goods_cnt)){
            $goods_cnt = 1;
        }
        $print_ladscape = 1;
        $goods_num_cnt = 1;
        $data_poptit_yn = "Y"; // POP제목 사용여부
        $data_tit_img = "N"; //POP타이틀 이미지 여부
        $data_img_yn = "N"; //이미지 사용여부

        $data_option_yn = "Y"; //옵션명 사용여부
        $option_type_yn = "Y"; //옵션타입여부

        $data_org_price_yn = "Y"; //정상가 사용여부


        $base_goods_tit = "행사상품"; //기본값 POP제목
        if(!empty($style)){
            if($style=='popbg307_01'){
                $base_goods_name = "농심 신라면 묶음"; //기본값 상품명
                $data_org_price_fix="Y";
            }else if($style=='popbg307_02'){
                $base_goods_name = "농심 신라면 묶음"; //기본값 상품명
                $data_org_price_fix="Y";
            }
        }else{
            $base_goods_name = "농심 신라면 묶음"; //기본값 상품명
        }
        if($blankdata == "N"){
            $base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
            $base_goods_org_price = "15,000"; //기본값 정상가
        }
        $base_goods_price = "9,900"; //기본값 할인가
        $data_date_yn = "Y"; //행사기간 사용여부
			}else if($type == "3_08"){
				if(empty($goods_cnt)){
					$goods_cnt = 2;
				}
				// $print_ladscape = 2;
				$goods_num_cnt = 2;
				$data_poptit_yn = "N"; // POP제목 사용여부
				$data_tit_img = "Y"; //POP타이틀 이미지 여부
				$data_img_yn = "N"; //이미지 사용여부

						$data_option_yn = "Y"; //옵션명 사용여부
						$option_type_yn = "Y"; //옵션타입여부
						$data_org_price_yn = "Y"; //정상가 사용여부
				$base_goods_tit = "행사상품"; //기본값 POP제목
				if(!empty($style)){
					if($style=='popbg308_01'){
						$base_goods_name = "한우등심"; //기본값 상품명
										$data_org_price_fix="Y";
					}else if($style=='popbg308_02'){
						$base_goods_name = "한우등심"; //기본값 상품명
										$data_org_price_fix="Y";
					}
				}else{
					$base_goods_name = "딸기 한소쿠리(500g)"; //기본값 상품명
				}

						if($blankdata == "N"){
						$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
						$base_goods_org_price = "15,000"; //기본값 정상가
						}
				$base_goods_price = "9,900"; //기본값 할인가
				$data_date_yn = "N"; //행사기간 사용여부
			}else if($type == "3_09"){
					if(empty($goods_cnt)){
							$goods_cnt = 2;
					}
					// $print_ladscape = 2;
					$goods_num_cnt = 2;
					$data_poptit_yn = "Y"; // POP제목 사용여부
					$data_tit_img = "N"; //POP타이틀 이미지 여부
					$data_img_yn = "N"; //이미지 사용여부

					$data_option_yn = "Y"; //옵션명 사용여부
					$option_type_yn = "Y"; //옵션타입여부

					$data_org_price_yn = "Y"; //정상가 사용여부
                    $title_cnt = 2; //상품 타이틀수

					$base_goods_tit = "행사상품"; //기본값 POP제목
					if(!empty($style)){
							if($style=='popbg309_01'){
									$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
									$data_org_price_fix="Y";
							}else if($style=='popbg309_02'){
									$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
									$data_org_price_fix="Y";
							}
					}else{
							$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
					}
					if($blankdata == "N"){
							$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
							$base_goods_org_price = "15,000"; //기본값 정상가
					}
					$base_goods_price = "9,900"; //기본값 할인가
					$data_date_yn = "N"; //행사기간 사용여부
				}else if($type == "3_10"){
					if(empty($goods_cnt)){
						$goods_cnt = 3;
					}
					// $print_ladscape = 3;
					$goods_num_cnt = 3;
					$data_poptit_yn = "N"; // POP제목 사용여부
					$data_tit_img = "Y"; //POP타이틀 이미지 여부
					$data_img_yn = "N"; //이미지 사용여부

							$data_option_yn = "Y"; //옵션명 사용여부
							$option_type_yn = "Y"; //옵션타입여부
							$data_org_price_yn = "Y"; //정상가 사용여부
					$base_goods_tit = "행사상품"; //기본값 POP제목
					if(!empty($style)){
						if($style=='popbg310_01'){
							$base_goods_name = "한우등심"; //기본값 상품명
											$data_org_price_fix="Y";
						}else if($style=='popbg310_02'){
							$base_goods_name = "한우등심"; //기본값 상품명
											$data_org_price_fix="Y";
						}
					}else{
						$base_goods_name = "딸기 한소쿠리(500g)"; //기본값 상품명
					}

							if($blankdata == "N"){
							$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
							$base_goods_org_price = "15,000"; //기본값 정상가
							}
					$base_goods_price = "9,900"; //기본값 할인가
					$data_date_yn = "N"; //행사기간 사용여부
				}else if($type == "3_11"){
						if(empty($goods_cnt)){
								$goods_cnt = 3;
						}
						// $print_ladscape = 0;
						$goods_num_cnt = 3;
						$data_poptit_yn = "Y"; // POP제목 사용여부
						$data_tit_img = "N"; //POP타이틀 이미지 여부
						$data_img_yn = "N"; //이미지 사용여부

						$data_option_yn = "Y"; //옵션명 사용여부
						$option_type_yn = "Y"; //옵션타입여부

						$data_org_price_yn = "Y"; //정상가 사용여부
                        $title_cnt = 3; //상품 타이틀수

						$base_goods_tit = "행사상품"; //기본값 POP제목
						if(!empty($style)){
								if($style=='popbg311_01'){
										$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
										$data_org_price_fix="Y";
								}else if($style=='popbg311_02'){
										$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
										$data_org_price_fix="Y";
								}
						}else{
								$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
						}
						if($blankdata == "N"){
								$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
								$base_goods_org_price = "15,000"; //기본값 정상가
						}
						$base_goods_price = "9,900"; //기본값 할인가
						$data_date_yn = "N"; //행사기간 사용여부
					}else if($type == "3_12"){
						if(empty($goods_cnt)){
							$goods_cnt = 4;
						}
						$print_ladscape = 1;
						$goods_num_cnt = 4;
						$data_poptit_yn = "N"; // POP제목 사용여부
						$data_tit_img = "Y"; //POP타이틀 이미지 여부
						$data_img_yn = "N"; //이미지 사용여부

								$data_option_yn = "Y"; //옵션명 사용여부
								$option_type_yn = "Y"; //옵션타입여부
								$data_org_price_yn = "Y"; //정상가 사용여부
						$base_goods_tit = "행사상품"; //기본값 POP제목
						if(!empty($style)){
							if($style=='popbg312_01'){
								$base_goods_name = "한우등심"; //기본값 상품명
												$data_org_price_fix="Y";
							}else if($style=='popbg312_02'){
								$base_goods_name = "한우등심"; //기본값 상품명
												$data_org_price_fix="Y";
							}
						}else{
							$base_goods_name = "딸기 한소쿠리(500g)"; //기본값 상품명
						}

								if($blankdata == "N"){
								$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
								$base_goods_org_price = "15,000"; //기본값 정상가
								}
						$base_goods_price = "9,900"; //기본값 할인가
						$data_date_yn = "N"; //행사기간 사용여부
					}else if($type == "3_13"){
							if(empty($goods_cnt)){
									$goods_cnt = 4;
							}
							$print_ladscape = 1;
							$goods_num_cnt = 4;
							$data_poptit_yn = "Y"; // POP제목 사용여부
							$data_tit_img = "N"; //POP타이틀 이미지 여부
							$data_img_yn = "N"; //이미지 사용여부

							$data_option_yn = "Y"; //옵션명 사용여부
							$option_type_yn = "Y"; //옵션타입여부

							$data_org_price_yn = "Y"; //정상가 사용여부
                            $title_cnt = 4; //상품 타이틀수

							$base_goods_tit = "행사상품"; //기본값 POP제목
							if(!empty($style)){
									if($style=='popbg313_01'){
											$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
											$data_org_price_fix="Y";
									}else if($style=='popbg313_02'){
											$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
											$data_org_price_fix="Y";
									}
							}else{
									$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
							}
							if($blankdata == "N"){
									$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
									$base_goods_org_price = "15,000"; //기본값 정상가
							}
							$base_goods_price = "9,900"; //기본값 할인가
							$data_date_yn = "N"; //행사기간 사용여부
						}else if($type == "3_14"){
							if(empty($goods_cnt)){
								$goods_cnt = 8;
							}
							// $print_ladscape = 2;
							$goods_num_cnt = 8;
							$data_poptit_yn = "N"; // POP제목 사용여부
							$data_tit_img = "Y"; //POP타이틀 이미지 여부
							$data_img_yn = "N"; //이미지 사용여부

									$data_option_yn = "Y"; //옵션명 사용여부
									$option_type_yn = "Y"; //옵션타입여부
									$data_org_price_yn = "Y"; //정상가 사용여부
							$base_goods_tit = "행사상품"; //기본값 POP제목
							if(!empty($style)){
								if($style=='popbg314_01'){
									$base_goods_name = "한우등심"; //기본값 상품명
													$data_org_price_fix="Y";
								}else if($style=='popbg314_02'){
									$base_goods_name = "한우등심"; //기본값 상품명
													$data_org_price_fix="Y";
								}
							}else{
								$base_goods_name = "딸기 한소쿠리(500g)"; //기본값 상품명
							}

									if($blankdata == "N"){
									$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
									$base_goods_org_price = "15,000"; //기본값 정상가
									}
							$base_goods_price = "9,900"; //기본값 할인가
							$data_date_yn = "N"; //행사기간 사용여부
						}else if($type == "3_15"){
                            $title_cnt = 8; //상품 타이틀수
								if(empty($goods_cnt)){
										$goods_cnt = 8;
								}
								// $print_ladscape = 2;
								$goods_num_cnt = 8;
								$data_poptit_yn = "Y"; // POP제목 사용여부
								$data_tit_img = "N"; //POP타이틀 이미지 여부
								$data_img_yn = "N"; //이미지 사용여부

								$data_option_yn = "Y"; //옵션명 사용여부
								$option_type_yn = "Y"; //옵션타입여부

								$data_org_price_yn = "Y"; //정상가 사용여부


								$base_goods_tit = "행사상품"; //기본값 POP제목
								if(!empty($style)){
										if($style=='popbg315_01'){
												$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
												$data_org_price_fix="Y";
										}else if($style=='popbg315_02'){
												$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
												$data_org_price_fix="Y";
										}
								}else{
										$base_goods_name = "농심 신라면 묶음"; //기본값 상품명
								}
								if($blankdata == "N"){
										$base_goods_option = "1인 1개 한정판매"; //기본값 옵션명
										$base_goods_org_price = "15,000"; //기본값 정상가
								}
								$base_goods_price = "9,900"; //기본값 할인가
								$data_date_yn = "N"; //행사기간 사용여부

	}

?>
