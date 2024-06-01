function createPost() {
    var content = document.getElementById('post-content').value;
    setButtonLoadingState(['post-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/create-post.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['post-button'], false, false);
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error);
            }
        }
    }
    xhr.send('content=' + content);
}