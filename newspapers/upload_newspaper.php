<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Newspapers Uploading Panel</title>

</head>

<body>

    <h1>Welcome to file upload web panel!</h1>

    <p>

        Use this web panel to upload your newspapers to the website!

    </p>

    <form id="upload-file-form">
        <input value="" type="file" id="file_input_field" accept="audio/*" multiple>
        <br>
        <label for="select_book">Select Newspaper: </label>
        <select id="select_book"></select>
<br>
        <button id="upload_btn" type="button" onclick="setUploadFile()">Upload</button>
    </form>
    <h2 id="files-status" style="display: none;"></h2>
    <ul id="files-progress">
</ul>
<button onclick="window.location.reload()" id="reload-btn" style="display: none;">Done</button>
    <script src="script.js"></script>
    <script>
        // Check if the user is logged in by checking localStorage
        const isLoggedIn = localStorage.getItem('isLoggedIn');
        const username = localStorage.getItem('username');

        // If the session is not found, redirect to the login page
        if (!isLoggedIn || !username) {
            window.location.href = '/';  // Replace '/' with the path to your login page if needed
        }
    </script>
</body>

</html>