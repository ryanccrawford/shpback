$('document').ready(function(){
    $('#addItem').click(function(){
        var item = $('#newItem').val();
        // $.post('../searchusers.php',{search: search},function(response){
        //     $('#userSearchResultsTable').html(response);
        // });
    })
    item.text('#shoplist')
    $('#newItem').keypress(function(e){
        if(e.which == 13){//Enter key pressed
            $('#searchButton').click();//Trigger search button click event
        }
    });

});