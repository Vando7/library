$(function(){
    // Get click event of manage genre button.
    $('#genreModalButton').click(function(){
        $('#genreModal').modal('show')
            .find('#genreModalContent')
            .load($(this).attr('value'));
    });
});