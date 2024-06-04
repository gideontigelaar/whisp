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

var loadedPosts = [];

function loadPosts(limit) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../../includes/load-posts.php?limit=' + limit + '&loaded_posts=' + JSON.stringify(loadedPosts), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var responseHTML = xhr.responseText;
                var postsContainer = document.getElementById('posts-container');
                postsContainer.innerHTML += responseHTML;

                var posts = document.getElementsByClassName('post');
                for (var i = 0; i < posts.length; i++) {
                    var post = posts[i];
                    var postId = post.id.split('-')[1];
                    if (loadedPosts.indexOf(postId) === -1) {
                        loadedPosts.push(postId);
                    }
                }
            }
        }
    }
    xhr.send();
}

function isScrolledToBottom() {
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    var scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
    var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
    return scrollTop + clientHeight >= scrollHeight;
}

window.addEventListener('scroll', function() {
    if (isScrolledToBottom()) {
        loadPosts(15);
    }
});

function likePost(postId) {
    var likeCount = document.getElementById('like-count-' + postId);
    var likeIcon = document.getElementById('like-icon-' + postId);
    var likeValue = likeIcon.className === 'ph ph-heart text-primary' ? 1 : -1;
    likeCount.innerHTML = parseInt(likeCount.innerHTML) + likeValue;
    likeIcon.className = likeValue === 1 ? 'ph-fill ph-heart text-primary' : 'ph ph-heart text-primary';

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/like-post.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('post_id=' + postId);
}

function sharePost(postId) {
    navigator.clipboard.writeText(window.location.origin + '/post/' + postId);
}

function deletePost(postId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/delete-post.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error);
            }
        }
    }
    xhr.send('post_id=' + postId);
}