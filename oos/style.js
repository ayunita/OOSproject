$(document).ready(function(){
    $("#add_sensor_btn").click(function(){
        $("#add_sensor_panel").slideToggle("medium");
    });
    
    $("#del_sensor_btn").click(function(){
        $("#del_sensor_panel").slideToggle("medium");
    });
    
    $("#add_user_btn").click(function(){
        $("#add_user_panel").slideToggle("medium");
    });
    
    $("#edit_user_btn").click(function(){
        $("#edit_user_panel").slideToggle("medium");
    });
    
    $("#del_user_btn").click(function(){
        $("#del_user_panel").slideToggle("medium");
    });
	
	$("#upload_audio_btn").click(function(){
        $("#upload_audio_panel").slideToggle("medium");
    });
	
	$("#upload_images_btn").click(function(){
        $("#upload_images_panel").slideToggle("medium");
    });
	
	$("#upload_scalar_btn").click(function(){
        $("#upload_scalar_panel").slideToggle("medium");
    });
	
});
