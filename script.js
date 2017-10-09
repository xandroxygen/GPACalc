// --- main jQuery function ---
$(function() {
    $(".alert.alert-success").hide();
    $(".error").hide();
    
    // --- submit a form ---
    
    $(".btn.btn-primary").click(function(e) {
        e.preventDefault();
        $(".error").hide();
        $("#nameREQ").show();
        
        // validate name, gather input
        var name = $("input#name").val();
        if (name == "")
        {
            $("#nameREQ").hide();
            $(".error").show();
            return false;
        }
        var points = $("input#curr_points").val();
        var hours = $("input#curr_hours").val();

        // process form
        var formData = "name=" + name + "&points=" + points + "&hours=" + hours;
        $.post("basic-gpa-calc/getGPA.php", formData, function(data) {
                $("#showGPA").show();
                $("#result").text(data);
            });
    });
    
    // --- close the alert box and ready for new submit ---
    
    $(".close").click(function() {
        $(".alert.alert-success").hide();
    });
    
    // --- enable tooltips
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
});
