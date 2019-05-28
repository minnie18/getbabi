$(function(){
	enableFinishButton: false;
	$("#wizard").steps({
        headerTag: "h2",
        bodyTag: "section",
        transitionEffect: "fade",
        enableAllSteps: true,
        transitionEffectSpeed: 500,
        onFinished: function (event, currentIndex) {
 
            var a01_1 = $('input[name=a01_1]:checked').val();
            var a01_2 = $('input[name=a01_2]:checked').val();
            var a01_3 = $('input[name=a01_3]:checked').val(); 
            
            if ($('input[name="a01_1"]').is(':checked') && $('input[name="a01_2"]').is(':checked') && $('input[name="a01_3"]').is(':checked')) {
            	$('form#wizard').submit();
            }else{
            	alert('กรุณากรอกรายละเอียดให้ครบถ้วน\nเพื่อการวินิฉัยได้อย่างถูกต้อง');
            }
         },
        labels: {
            finish: "Submit",
            next: "Forward",
            previous: "Backward"
        }
    });
    $('.wizard > .steps li a').click(function(){
    	$(this).parent().addClass('checked');
		$(this).parent().prevAll().addClass('checked');
		$(this).parent().nextAll().removeClass('checked');
    });
    // Custome Jquery Step Button
    $('.forward').click(function(){
    	$("#wizard").steps('next');
    }) 
    $('.backward').click(function(){
        $("#wizard").steps('previous');
    })
    // Select Dropdown
    $('html').click(function() {
        $('.select .dropdown').hide(); 
    });
    $('.select').click(function(event){
        event.stopPropagation();
    });
    $('.select .select-control').click(function(){
        $(this).parent().next().toggle();
    })    
    $('.select .dropdown li').click(function(){
        $(this).parent().toggle();
        var text = $(this).attr('rel');
        $(this).parent().prev().find('div').text(text);
    })
})
