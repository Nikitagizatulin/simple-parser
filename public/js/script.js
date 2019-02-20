( ($)=>{
    $( () => {
        //modal video
        let imgVideo = $('img.img-responsive');
        if(imgVideo.length > 0){
            imgVideo.click(function(){
                let source = $(this).attr('data-content');
                $('#videoModal').find('iframe').attr('src',source);
                $('#videoModal').modal('show');
            });
        }

        $('#videoModal').on('hidden.bs.modal', function (e) {
            console.log($(this).find('iframe').attr('src',''));
        });
        //__________________________________

        //redmore
        $('div.content').readmore({
            maxHeight: 150,
            speed: 300,
            moreLink: '<a href="#">Reed more ↓</a>',
            lessLink: '<a href="#">Reed less ↑</a>'
        });
        //__________________________________

        $('.delete').submit((e)=>{
            if(!confirm('Are you sure that you want to delete this video')){
            e.preventDefault();
            }
        });
    })
})(jQuery)