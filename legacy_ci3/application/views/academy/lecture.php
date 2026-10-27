<div class="wrap">

    <div class="lecture_title"><?=$info -> name?></div>
    <div class="lecture_info">
        <span><?=$_SESSION['name']?></span>님은 현재 <br />
        총 23개의 교육과정 중 <span>16</span>개 교육을 통과하셨습니다. 
        <div class="lecture_info_score">통과율 <span>76%</span></div>
    </div>


    <div class="lecture_c">1. 홈페이지 메인 프로그램 Part 1</div>

    <div class="lecture_m">
        <div class="lecture_mt">제1강</div>
        <div class="lecture_mc">
            <div class="lecture_mct">접속방법, 메인프로그램 소개, 진단평가</div>
            <div class="lecture_mcb">총 10분, 시험 문항 5개</div>
        </div>
        <div class="lecture_mpass">통과</div>
        <div class="lecture_mb">
            <div class="lecture_mb_bt4" onclick="showVideo(1)">강의 다시 보기</div>
            <div class="lecture_mb_bt3" onclick="showTest(1)">문제 다시 풀기</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="lecture_m">
        <div class="lecture_mt">제2강</div>
        <div class="lecture_mc">
            <div class="lecture_mct">수강생 관리, 활동지 및 북히스토리</div>
            <div class="lecture_mcb">총 10분, 시험 문항 5개</div>
        </div>
        <div class="lecture_mb">
            <div class="lecture_mb_bt" onclick="showVideo(1)">강의보기</div>
            <div class="lecture_mb_bt2" onclick="showTest(1)">문제풀기</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="lecture_m">
        <div class="lecture_mt">제3강</div>
        <div class="lecture_mc">
            <div class="lecture_mct">접속방법, 메인프로그램 소개, 진단평가</div>
            <div class="lecture_mcb">총 10분, 시험 문항 5개</div>
        </div>
        <div class="lecture_mb">
            <div class="lecture_mb_bt5" onclick="showVideo(1)">강의 계속 보기</div>
            <div class="lecture_mb_bt2" onclick="showTest(1)">문제풀기</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="lecture_m">
        <div class="lecture_mt">제4강</div>
        <div class="lecture_mc">
            <div class="lecture_mct">접속방법, 메인프로그램 소개, 진단평가</div>
            <div class="lecture_mcb">총 10분, 시험 문항 5개</div>
        </div>
        <div class="lecture_mb">
            <div class="lecture_mb_bt" onclick="showVideo(1)">강의보기</div>
            <div class="lecture_mb_bt2" onclick="showTest(1)">문제풀기</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>


    <div class="lecture_c">2. 주문 및 결제 안내</div>

    
    <div class="lecture_m">
        <div class="lecture_mt">제5강</div>
        <div class="lecture_mc">
            <div class="lecture_mct">접속방법, 메인프로그램 소개, 진단평가</div>
            <div class="lecture_mcb">총 10분, 시험 문항 5개</div>
        </div>
        <div class="lecture_mb">
            <div class="lecture_mb_bt5" onclick="showVideo(1)">강의 계속 보기</div>
            <div class="lecture_mb_bt2" onclick="showTest(1)">문제풀기</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="lecture_m">
        <div class="lecture_mt">제6강</div>
        <div class="lecture_mc">
            <div class="lecture_mct">접속방법, 메인프로그램 소개, 진단평가</div>
            <div class="lecture_mcb">총 10분, 시험 문항 5개</div>
        </div>
        <div class="lecture_mb">
            <div class="lecture_mb_bt" onclick="showVideo(1)">강의보기</div>
            <div class="lecture_mb_bt2" onclick="showTest(1)">문제풀기</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>

</div>

<script>

    function showVideo(index){
        window.open('/academy/lecture_view/'+index,'_blank','width=740px, height=630px');
    }

    function showTest(index){
        window.open('/academy/lecture_test/'+index,'_blank','width=740px, height=630px');
    }
</script>