$(function(){
	$("#wizard").steps({
        headerTag: "h4",
        bodyTag: "section",
        transitionEffect: "fade",
        enableAllSteps: true,
        onStepChanging: function (event, currentIndex, newIndex) { 
            if ( newIndex === 1 ) {
                $('.wizard > .steps ul').addClass('step-2');
            } else {
                $('.wizard > .steps ul').removeClass('step-2');
            }
            if ( newIndex === 2 ) {
                $('.wizard > .steps ul').addClass('step-3');
            } else {
                $('.wizard > .steps ul').removeClass('step-3');
            }
            return true; 
        },
        onFinished: function (event, currentIndex) {
   
        	if($("#dp1").val() == "" || $("#frm_dif_day").val() == ""){
        		alert('กรุณากรอกรายละเอียดให้ครบถ้วน\nเพื่อการวินิฉัยได้อย่างถูกต้อง');
        		 
        	}else{
        		$('form#frm_wizard').submit();
        	}
         },
        labels: {
            finish: "Submit",
            next: "Continue",
            previous: "Back"
        }
    });
    // Custom Button Jquery Steps
    $('.forward').click(function(){
    	$("#wizard").steps('next');
    })
    $('.backward').click(function(){
        $("#wizard").steps('previous');
    })
    // Date Picker
    var dp1 = $('#dp1').datepicker().data('datepicker');
    dp1.selectDate(new Date());
})
