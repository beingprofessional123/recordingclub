window.addEventListener("load",function (){
    getBooks();
});
function setUploadFile() {
    document.getElementById("upload-file-form").style.display = "none";
    var form_data = new FormData();
    var files = document.getElementById("file_input_field").files;
    var file_info= [];
    for (let i = 0; i < files.length; i++) {
        file_info.push({
file_name: files[i].name,
file_progress: "0%"
        });
        var li = document.createElement("li");
var ul = document.getElementById("files-progress");
li.id = "f-"+i;
li.innerHTML = "Uploading " + file_info[i]["file_name"];
ul.appendChild(li);
    }
    for (let i = 0; i < file_info.length; i++) {
        var li = document.getElementById("f-"+i);
        var p = document.createElement("p");
        p.id = "fp-"+i;
        li.appendChild(p);
        p.innerHTML = "Progress: " + file_info[i]["file_progress"] + " completed";
        p.setAttribute("aria-live","polite");
    }
    document.getElementById("files-status").innerHTML = "Uploading " + file_info.length + "files...";
    document.getElementById("files-status").style.display = "block";
    var count = 0;
    form_data.append("file", document.getElementById("file_input_field").files[count]);
form_data.append("folder_path",document.getElementById("select_book").value);
    
var request = new XMLHttpRequest();
request.open("POST", "process.php");
request.upload.addEventListener("progress", function(event) {
    var percentCompleted = (event.loaded / event.total) * 100;
    percentCompleted = Math.round(percentCompleted);
    file_info[count]["file_progress"] = percentCompleted + "%";
    document.getElementById("fp-"+count).innerHTML = "Progress: " + file_info[count]["file_progress"] + " completed";
}    );
request.addEventListener("load", function() {
file_info[count]["file_progress"] = "Uploaded";
document.getElementById("fp-"+count).innerHTML = "Progress: " + file_info[count]["file_progress"];
});
request.send(form_data);
    var interval = setInterval(function() {
        if (file_info[count]["file_progress"] === "Uploaded") {
            count++;
form_data.append("file", document.getElementById("file_input_field").files[count]);
form_data.append("folder_path",document.getElementById("select_book").value);
var request = new XMLHttpRequest();
request.open("POST", "process.php");
request.upload.addEventListener("progress", function(event) {
    var percentCompleted = (event.loaded / event.total) * 100;
    percentCompleted = Math.round(percentCompleted);
    file_info[count]["file_progress"] = percentCompleted + "%";
    document.getElementById("fp-"+count).innerHTML = "Progress: " + file_info[count]["file_progress"] + " completed";

}    );
request.addEventListener("load", function() {
file_info[count]["file_progress"] = "Uploaded";

document.getElementById("fp-"+count).innerHTML = "Progress: " + file_info[count]["file_progress"];
});
request.send(form_data);
if (count == file_info.length -1) {
    window.clearInterval(interval);
    document.getElementById("files-status").innerHTML = "All files are uploaded!";
    document.getElementById("reload-btn").style.display = "block";
    alert("All Files Are Uploaded Successfully.");
}
        }
    },1000);
}

function getBooks(){
    let request=new XMLHttpRequest();
request.open("GET","../../books/get/get_books.php");
request.onreadystatechange=()=>{
var myResponse=JSON.parse(request.responseText);
var myArray=myResponse.books;
    let select = document.getElementById("select_book");
    for (let n=0;n<myResponse.books.length; n++){
        let optionTag = document.createElement("option");
        optionTag.value = myArray[n];
        optionTag.innerHTML = myArray[n];
        select.appendChild(optionTag);
    }
select.size = myArray.length;
}
    request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
request.send();
}

