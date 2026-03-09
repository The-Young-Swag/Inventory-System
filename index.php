<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/styles.css">
    <link rel="stylesheet" href="style/modal.css">
</head>
<body>
<div class="sidebar">

</div>

    <div class="mainContent">

    </div>
    


    <div class="modalContainer"></div>

</body>
<script
  src="https://code.jquery.com/jquery-4.0.0.js"
  integrity="sha256-9fsHeVnKBvqh3FB2HYu7g2xseAZ5MlN6Kz/qnkASV8U="
  crossorigin="anonymous"></script>


<script>
function loadPage(page){

$(".mainContent")
    .empty()                         // destroy previous injection
    .load("frontend/" + page);       // inject new page

}

$(function(){
// load sidebar once
$(".sidebar").load("frontend/sidebar.php");
// load default page
loadPage("dashboard.php");
// handle sidebar clicks
$(".sidebar").on("click","a",function(e){
    e.preventDefault();
    const page = $(this).data("page");
    loadPage(page);
});





});



  </script>
</html>