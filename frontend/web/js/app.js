$(function(){
    const $leaveComment=$("#leave-comment");
    const $cancelComment=$("#cancel-comment");
    const $createCommentForm=$(".create-comment-form");
    const $commentWrapper=$("#comment-wrapper");
    const $commentCount=$("#comment-count");


    $leaveComment.click(()=>{
        $leaveComment
            .attr('rows','2')
            .closest('.create-comment')
            .addClass('focused');
    });
    $cancelComment.click(resetForm);
    $("#submit-comment").on('click',function (){
        // e.preventDefault()
        let id=$("#video_id").val()
        let message=$("#leave-comment").val();
        if(message === "")
        {
            alert('Pleace blink the field and Comment');
        }
        $.ajax({
            url:'/frontend/web/comment/create',
            type:'POST',
            data:{'id':id,'message':message},
            success:function (data){
               if(data.success)
               {
                   $commentWrapper.prepend(data.comment);
                   resetForm();
                   $commentCount.text(parseInt($commentCount.text())+1);
                   initComments()
               }
               else
               {
                   alert(data.comment)
               }
               resetForm()
            }
        })
     })
     initComments();

    function resetForm()
     {
         $leaveComment.attr('rows','1')
         $cancelComment
             .closest('.create-comment')
             .removeClass('focused')
         $createCommentForm.trigger('reset')
     }
     function onDeletedComment(ev)
     {
         ev.preventDefault();
         const $delete=$(ev.target)
         if(confirm('Are you sure you want delete this message ?'))
         {
             $.ajax({
                 method:'post',
                 url:$delete.attr('href'),
                 success:function (data){
                     if(data.success)
                     {
                         $delete.closest('.comment-media').remove();
                         $commentCount.text(parseInt($commentCount.text()) - 1);
                     }
                 }
             })
         }
     }
     function initComments()
     {
         let $commentDelete=$(".item-comment-delete");
         let $editComment=$(".item-comment-edit");
         let $cancelEdit=$(".comment-media .btn-cancel");
         let $saveEdit=$(".comment-media .btn-save");
         let $commentForm=$(".comment-edit-text");

         $commentDelete.on('click',onDeletedComment);

         $editComment.click(ev =>{
             ev.preventDefault();
             const $this=$(ev.target)
             const $item=$this.closest('.comment-media').addClass('edited');
             const $textWrapper=$item.find('.text-wrapper');
             const $input=$item.find("textarea");
             $input.val($textWrapper.text().trim());
         })

         $commentForm.off('submit').on('submit',ev => {
             ev.preventDefault();
             const $this=$(ev.target);
             $.ajax({
                 method: $this.attr('method'),
                 url:$this.attr('action'),
                 data:$this.serializeArray(),
                 success:function (data){
                     if(data.success)
                     {
                         const $item=$this.closest('.comment-media').addClass('edited');
                         const $textWrapper=$item.find('.text-wrapper');
                         const $input=$item.find("textarea");
                         $textWrapper.text($input.val())
                         $this.closest('.media-body').removeClass('edited');
                         $item.replaceWith(data.comment);
                         initComments();
                     }
                     else
                     {
                         confirm('Warning ! This comment belongs to another user .');
                     }
                 }
             })
         })
         $cancelEdit.click(ev => {
             ev.preventDefault();
             const $this=$(ev.target);
             $this.closest('.comment-media').removeClass('edited');
         })
     }
})