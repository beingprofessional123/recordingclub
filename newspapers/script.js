window.addEventListener("load", function () {
    getNewspapers();
});

function getNewspapers() {
    let request = new XMLHttpRequest();
    request.open("GET", "get_newspapers.php");
    request.onreadystatechange = () => {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                const myResponse = JSON.parse(request.responseText);
                const newspapers = myResponse.newspapers;
                populateDropdown(newspapers);
            } else {
                // Handle the error gracefully
                console.error("Error fetching newspapers:", request.statusText);
            }
        }
    };

    request.send();
}

function populateDropdown(newspapers) {
    const selectElement = document.getElementById("select_book");
    let optionTags = "";
    for (let i = 0; i < newspapers.length; i++) {
        optionTags += `<option value="${newspapers[i]}">${newspapers[i]}</option>`;
    }
    selectElement.innerHTML = optionTags;
}

function setUploadFile() {
    const fileInput = document.getElementById("file_input_field");
    const selectedNewspaper = document.getElementById("select_book").value;

    const fileInfos = [];
    const fileList = document.getElementById("files-progress");
    const filesStatus = document.getElementById("files-status");
    const reloadBtn = document.getElementById("reload-btn");

    for (let i = 0; i < fileInput.files.length; i++) {
        const file = fileInput.files[i];
        fileInfos.push({
            file_name: file.name,
            file_progress: "0%"
        });

        const li = document.createElement("li");
        li.id = "f-" + i;
        li.innerHTML = `Uploading ${fileInfos[i].file_name}`;
        fileList.appendChild(li);

        const p = document.createElement("p");
        p.id = "fp-" + i;
        p.innerHTML = `Progress: ${fileInfos[i].file_progress} completed`;
        p.setAttribute("aria-live", "polite");
        li.appendChild(p);
    }

    filesStatus.innerHTML = `Uploading ${fileInfos.length} files...`;
    filesStatus.style.display = "block";

let count = 0;

function uploadNextFile() {
    const formData = new FormData();
    formData.append("newspaper_file", fileInput.files[count]);
    formData.append("newspaper_name", selectedNewspaper);
    formData.append("newspaper_title", fileInput.files[count].name);

    const request = new XMLHttpRequest();
    request.open("POST", "set_newspaper_daily_post.php");
    request.upload.addEventListener("progress", function (event) {
        if (event.lengthComputable) {
            const percentCompleted = Math.round((event.loaded / event.total) * 100);
            fileInfos[count].file_progress = percentCompleted + "%";
            document.getElementById("fp-" + count).innerHTML = `Progress: ${fileInfos[count].file_progress} completed`;
        }
    });

    request.addEventListener("load", function () {
        fileInfos[count].file_progress = "Uploaded";
        document.getElementById("fp-" + count).innerHTML = `Progress: ${fileInfos[count].file_progress}`;
        count++;

        if (count < fileInfos.length) {
            uploadNextFile();
        } else {
            filesStatus.innerHTML = "All files are uploaded!";
            reloadBtn.style.display = "block";
            alert("All Files Are Uploaded Successfully.");
        }
    });

    request.send(formData);
}

uploadNextFile();
}
