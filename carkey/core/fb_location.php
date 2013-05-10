<?php
class fb_info extends facebook {

    function __construct($id) {
        parent::__construct($id, "fb_info");
    }

    function print_block() {
        $pass = parent::print_block();
        if ($pass) {

                  $style=$this->merge();

?>
            <script>
                $(function(){
                    FB.api(
                    {
                        method: "fql.query",
                        query: "SELECT page_id,description,pic,name FROM page WHERE page_id='<?php echo $this->get('fb_page_id'); ?>'"
                    },
                    function respondAgain(response){
                        for (i in response){
                            tempUser=response[i];

                            str="<div class='text' style='<?php echo $style; ?>' ><img src='" + tempUser.pic + "' >" + tempUser['description'] + "</div>";
                            $('#block_<?php echo $this->id; ?>').html(str);
                        }
                    });
                });
            </script>
<?php
        }
    }

    function edit_block(){
        parent::edit_block_form_open("Info");
        parent::edit_block_form_close();
    }


    function comments() {
 ?>
        <script>
            $(function(){
                FB.api(
                {
                    method: "fql.query",
                    query: "SELECT message,likes,comments FROM stream WHERE source_id='<?php echo $fb_page_id; ?>' order by updated_time desc limit 5"
                },
                function respondAgain(response){
                    for (i in response){
                        tempUser=response[i];
                        str="<div class='text' ><img src='/plugins/fbook/facebook-logo.png' style='height:35x;width:35px;' >" + tempUser.message;
                        commentsObj=tempUser.comments;
                        comments=commentsObj.comment_list;
                        for(c in comments){
                            comment=comments[c];
                            str=str + "<div style='padding-left:45px;margin-top:10px;' >" + comment.text + "</div>";
                        }
                        str=str + "</div>";
                        $('#block_<?php echo $this->id; ?>').append(str);
                    }
                });
            });

        </script>

<?php }

}
?>

