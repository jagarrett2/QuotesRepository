<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="jquery.js" type="text/javascript"></script>
    <script src="ajaxCall.js"></script>

      <style>
        body {
          text-align: center;
          font-family: "Helvetica", sans-serif;
        }
        h1 {
          font-size: 2em;
          font-weight: bold;
        }
        .box {
          border-radius: 5px;
          background-color: #eee;
          padding: 20px 5px;
        }
        button {
          color: white;
          background-color: #4791d0;
          border-radius: 5px;
          border: 1px solid #4791d0;
          padding: 5px 10px 8px 10px;
        }
        button:hover {
          background-color: #0F5897;
          border: 1px solid #0F5897;
        }
      </style>
</head>
<body>



<div style="height: 500px; overflow: auto; position: relative">
  <div style="float:left; width:50%; position: sticky; top: 0;">
    <h1>Quotes Data Sender</h1> 
    <form id="apiform">
  
      <textarea name="quote" id="" cols="30" rows="10" placeholder="quote" autocomplete="on" ></textarea><br>
      <input type="text" name="author_id" placeholder="author_id" autocomplete="on" ><br>
      <input type="text" name="category_id" placeholder="category_id" autocomplete="on" ><br><br>
  
      <button id="quotesPostMessage"> Post Message </button>
    </form>
  </div>
  
  <div style="float:right; width:50%;">
    <h1>Quotes Data Finder</h1> 
    <p id="quotesData" class="message box"> The message will go here </p>
    <p><button id="quotesGetMessage"> Get Message </button></p>
  </div> 
</div>




<div style="height: 500px; overflow: auto; position: relative">
  <div style="float:left; width:50%; position: sticky; top: 0;">
    <h1>Author Data Sender</h1> 
    <form id="apiform">
  
      <input type="text" name="author" placeholder="author" autocomplete="on" ><br><br>
  
      <button id="authorsPostMessage"> Post Message </button>
    </form>
  </div>
  
  <div style="float:right; width:50%;">
    <h1>Author Data Finder</h1> 
    <p id="authorsData"  class="message box"> The message will go here </p>
    <p><button id="authorsGetMessage"> Get Message </button></p>
  </div> 
</div>




<div style="height: 500px; overflow: auto; position: relative">
  <div style="float:left; width:50%; position: sticky; top: 0;">
    <h1>Categories Data Sender</h1> 
    <form id="apiform">
  
      <input type="text" name="category" placeholder="category" autocomplete="on" ><br>
  
      <button id="categoriesPostMessage"> Post Message </button>
    </form>
  </div>
  
  <div style="float:right; width:50%;">
    <h1>Categories Data Finder</h1> 
    <p id="categoriesData"  class="message box"> The message will go here </p>
    <p><button id="categoriesGetMessage"> Get Message </button></p>
  </div> 
</div>

</body>
</html>






