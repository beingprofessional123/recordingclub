window.addEventListener("load", getBooks);

function setUploadFile() {
    const fileInput = document.getElementById("file_input_field");
    const files = fileInput.files;
    const filesStatus = document.getElementById("files-status");
    const fileList = document.getElementById("files-progress");
    const select = document.getElementById("select_book");

    if (!files.length || !select.value) return;

    const selectedOption = select.options[select.selectedIndex];
    const folderPath = selectedOption.text;
    const bookId = selectedOption.value;

    document.getElementById("upload-file-form").style.display = "none";
    filesStatus.innerHTML = `Uploading ${files.length} file(s)...`;
    filesStatus.style.display = "block";

    const fileInfo = [];

    for (let i = 0; i < files.length; i++) {
        fileInfo.push({ file_name: files[i].name, file_progress: "0%" });

        const li = document.createElement("li");
        li.id = `f-${i}`;
        li.innerHTML = `Uploading ${fileInfo[i].file_name}`;

        const progress = document.createElement("p");
        progress.id = `fp-${i}`;
        progress.setAttribute("aria-live", "polite");
        progress.innerHTML = `Progress: ${fileInfo[i].file_progress} completed`;

        li.appendChild(progress);
        fileList.appendChild(li);
        console.log(folderPath);
        console.log(bookId);
    }

    let currentIndex = 0;

    const uploadNextFile = () => {
        if (currentIndex >= files.length) {
            filesStatus.innerHTML = "All files are uploaded!";
            document.getElementById("reload-btn").style.display = "block";
            alert("All Files Are Uploaded Successfully.");
            return;
        }

        const formData = new FormData();
        formData.append("file", files[currentIndex]);
        formData.append("folder_path", folderPath); // Book title
        formData.append("book_id", bookId);         // Book ID

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "process.php");

        xhr.upload.addEventListener("progress", (event) => {
            if (event.lengthComputable) {
                const percent = Math.round((event.loaded / event.total) * 100);
                fileInfo[currentIndex].file_progress = `${percent}%`;
                document.getElementById(`fp-${currentIndex}`).innerHTML =
                    `Progress: ${fileInfo[currentIndex].file_progress} completed`;
            }
        });

        xhr.addEventListener("load", () => {
            fileInfo[currentIndex].file_progress = "Uploaded";
            document.getElementById(`fp-${currentIndex}`).innerHTML =
                `Progress: Uploaded`;

            currentIndex++;
            uploadNextFile();
        });

        xhr.send(formData);
    };

    uploadNextFile();
}

function getBooks() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "../../audio_books/fetch_all_audio_books.php", true);

    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const { book, id } = JSON.parse(xhr.responseText);
                const select = document.getElementById("select_book");

                if (book.length !== id.length) {
                    console.error("Mismatch between book titles and IDs.");
                    return;
                }

                book.forEach((title, index) => {
                    const option = document.createElement("option");
                    option.value = id[index];
                    option.text = title;
                    select.appendChild(option);
                });

                select.size = book.length;
            } catch (err) {
                console.error("JSON parsing error:", err);
            }
        }
    };

    xhr.send();
}
