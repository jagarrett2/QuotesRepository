
//POST REQUEST


function getUrlVars(url) {
    var hash;
    var myJson = {};
    var hashes = url.slice(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        myJson[hash[0]] = hash[1];
    }
    return JSON.stringify(myJson);
}

function AjaxPostCall(path){
    var url = $('form').serialize();
    var test = getUrlVars(url);
    $.ajax({
        type:"POST",
        url: `http://localhost/php_project/api/${path}/`,
        data: test.replace("+", " "),
        ContentType:"application/json",

        success:function(){
            alert('successfully posted');
        },
        error:function(){
            alert('Could not be posted');
        }

    });
}

function AjaxGetCall(path){
    var req;
    req=new XMLHttpRequest();
    req.open("GET", `http://localhost/php_project/api/${path}/`,true);
    req.send();
    console.log(req);
    req.onload=function(){
    var json=JSON.parse(req.responseText);

    //limit data called
    var son = json.filter(function(val) {
           return (val.id >= 4);  
       });

   var html = "";

   //loop and display data
   son.forEach(function(val) {
       var keys = Object.keys(val);

       html += "<div class = 'cat'>";
           keys.forEach(function(key) {
           html += "<strong>" + key + "</strong>: " + val[key] + "<br>";
           });
       html += "</div><br>";
   });

   //append in message class
   document.getElementById(`${path}Data`).innerHTML=html; 
   };
}

$(document).ready(function(){
    $('#quotesPostMessage').click(function(e){
        e.preventDefault();
        AjaxPostCall("quotes");
    });
    $('#authorsPostMessage').click(function(e){
        e.preventDefault();
        AjaxPostCall("authors");
    });
    $('#categoriesPostMessage').click(function(e){
        e.preventDefault();
        AjaxPostCall("categories");
    });
});
    

//GET REQUEST

document.addEventListener('DOMContentLoaded',function(){
document.getElementById('quotesGetMessage').onclick=function(){
    AjaxGetCall("quotes");
};
document.getElementById('authorsGetMessage').onclick=function(){
    AjaxGetCall("authors");
};
document.getElementById('categoriesGetMessage').onclick=function(){
    AjaxGetCall("categories");
};
});
  
