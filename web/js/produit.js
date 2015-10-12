$(document).ready(function(){
    //On un met un click sur l'vénément produitAction et on sur l'enfant delete
    //Le .delete correspond à la classe du bouton pour faire correspondre l'événement
    $("#produitAction").on("click", ".delete", function(event){
    event.preventDefault();
    if(confirm("Êtes vous sûr de vouloir supprimer ?")) {
        //Dans ce cas de figure that == convetion
        var that=$(this);
        var urlDelete= that.attr("href");
        $.ajax({
            type:"GET",
            url: urlDelete
        })
            //Si tout c'est bien passé faire (il existe le fail et le
            // always dans le concept de promesse javascript
        .done(function(){
            //Remonter l'arbo html jusqu"au tr
            that.closest("tr").fadeOut(600,function(){
                    $(this).remove();
                }
            );
        });
    }})
})


