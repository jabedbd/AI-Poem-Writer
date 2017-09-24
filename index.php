<?php 
file_put_contents('prev.txt',"");
?>

<!DOCTYPE html>
<html>
<head>
<title>The Poet</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
<style>
@import url(https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
.search {
  width: 100%;
  position: relative;
}
.search:before {
  position: absolute;
  top: 0;
  right: 0;
  width: 40px;
  height: 40px;
  line-height: 40px;
  font-family: 'FontAwesome';
  content: '\f040';
  background: #8FC357;
  text-align: center;
  color: #fff;
  border-radius: 5px;
  -webkit-font-smoothing: subpixel-antialiased;
  font-smooth: always;
}

.searchTerm {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  width: 100%;
  border: 5px solid #8FC357;
  padding: 5px;
  height: 40px;
  border-radius: 5px;
  outline: none;
}

.searchButton {
  position: absolute;
  top: 0;
  right: 0;
  width: 40px;
  height: 40px;
  opacity: 0;
  cursor: pointer;
}
    
    
div#poem {
    text-transform: capitalize;
}
 
 
@keyframes blink {
    /**
     * At the start of the animation the dot
     * has an opacity of .2
     */
    0% {
      opacity: .2;
    }
    /**
     * At 20% the dot is fully visible and
     * then fades out slowly
     */
    20% {
      opacity: 1;
    }
    /**
     * Until it reaches an opacity of .2 and
     * the animation can start again
     */
    100% {
      opacity: .2;
    }
}

.saving span {
    /**
     * Use the blink animation, which is defined above
     */
    animation-name: blink;
    /**
     * The animation should take 1.4 seconds
     */
    animation-duration: 1.4s;
    /**
     * It will repeat itself forever
     */
    animation-iteration-count: infinite;
    /**
     * This makes sure that the starting style (opacity: .2)
     * of the animation is applied before the animation starts.
     * Otherwise we would see a short flash or would have
     * to set the default styling of the dots to the same
     * as the animation. Same applies for the ending styles.
     */
    animation-fill-mode: both;
}

.saving span:nth-child(2) {
    /**
     * Starts the animation of the third dot
     * with a delay of .2s, otherwise all dots
     * would animate at the same time
     */
    animation-delay: .2s;
}

.saving span:nth-child(3) {
    /**
     * Starts the animation of the third dot
     * with a delay of .4s, otherwise all dots
     * would animate at the same time
     */
    animation-delay: .4s;
} 

</style>
<script>
var line = 0;
var poemline = 0;
var makeline = "";
var poeml = 0;
var fullpoem = "";
var title = ""; 
function complete(){
    $('.saving').hide();
     $('#poem').append("<p><strong style='color:#8FC357'>I just wrote it. Do you like the poem?</strong></p>");
     
     
     $.post("http://alor-nishan.com/poet/save.php",
    {
        poem: fullpoem,
        title: title,
    },
    function(data, status){
         $('#poem').append("Link: <a href='http://alor-nishan.com/poet/poem.php?id="+data+"'>http://alor-nishan.com/poet/poem.php?id="+data+"</a>");
    });
    
}

function AddLine(strline){
    var wcount = strline.split(' ').length;
    if(wcount > 2){
        fullpoem += strline+"<br/>";
         $('#poem').append("<span id='"+poeml+"' style='display:none;'>"+strline+"</span>");
         $("#"+poeml).show('slow');
         $('#poem').append("<br/>");
         poeml++;
    }
    
    
}
    
function containsSpecialCharacters(str){
    var regex = /[ !@।#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;
	return regex.test(str);
}
    
function One(word){
    
    if(line > 10){
        AddLine(makeline);
        makeline = "";
        line = 0;
        poemline++;
    }
    
    if(containsSpecialCharacters(word)){
           AddLine(makeline);
        makeline = "";
    }
    
    $.get("http://alor-nishan.com/poet/poemwrite.php?word="+word, function(data, status){
           // $('#poem').append(data+" ");
        makeline += data+" ";
        if(poemline < 5){
            Two(data);
        }
        else {
          complete()  
        }
        
        line++;
        });
    
}
    
 function Two(word){   
       if(line > 10){
        AddLine(makeline);
        makeline = "";
           line = 0;
           poemline++;
    }
     
           if(containsSpecialCharacters(word)){
        AddLine(makeline);
        makeline = "";
    }
        $.get("http://alor-nishan.com/poet/poemwrite.php?word="+word, function(data, status){
            //$('#poem').append(data+" ");
              makeline += data+" ";
             if(poemline < 5){
            One(data);
             }
             else {
          complete()  
        }
            line++;
        });
     
 }

</script>
</head>
    
<form class="search">
<input class="searchTerm" placeholder="Which type of poem you want?" />
<input class="searchButton" />
</form>
    

<p><div id="poem"></div></p>
<p class="saving" style="display:none">Wait.. I'm still writing<span>.</span><span>.</span><span>.</span></p>
<script>

$('.searchButton').on('click', function(){
   var subject =  $('.searchTerm').val();
   $('.saving').show();
    $('#poem').html(" ");
    line = 0;
    poemline = 0;
    fullpoem = "";
    title = subject;
    makeline += subject+" ";
    One(subject);      
}); 

$(".searchTerm").keyup(function(event){
    if(event.keyCode == 13){
        $(".searchButton").click();
    }
});


</script>
<body>

</body>
</html>
