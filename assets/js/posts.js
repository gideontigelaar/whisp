function createPost(replyToPostId) {
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
                showError(response.error, false);
            }
        }
    }
    xhr.send('content=' + content + (replyToPostId ? '&reply_to_post_id=' + replyToPostId : ''));
}

var loadedPosts = [];
var currentUrl = window.location.pathname;
var loading = false;

function loadPosts() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../../includes/load-posts.php?loaded_posts=' + JSON.stringify(loadedPosts) + '&current_url=' + currentUrl, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var responseHTML = xhr.responseText;
                var postsContainer = document.getElementById('posts-container');

                if (responseHTML === '') {
                    window.removeEventListener('scroll', scrollListener);
                    if (postsContainer.innerHTML.trim() === '') {
                        postsContainer.innerHTML += '<div class="text-center opacity-50 py-1">-- There\'s nothing here. --</div>';
                    } else {
                        postsContainer.innerHTML += '<div class="text-center opacity-50 py-1">-- That\'s all for now. --</div>';
                    }
                } else {
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
            loading = false;
        }
    }
    xhr.send();
}

document.addEventListener('DOMContentLoaded', function() {
    var posts = document.getElementsByClassName('post');
    for (var i = 0; i < posts.length; i++) {
        var post = posts[i];
        var postId = post.id.split('-')[1];
        if (loadedPosts.indexOf(postId) === -1) {
            loadedPosts.push(postId);
        }
    }

    if (document.documentElement.scrollHeight === document.documentElement.clientHeight) {
        loadPosts();
    }
});

function scrollListener() {
    if (!loading && (window.innerHeight + window.scrollY) >= (document.documentElement.offsetHeight - 100)) {
        loading = true;
        loadPosts();
    }
}

window.addEventListener('scroll', scrollListener);

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

let notificationActive = false;

function sharePost(postId, displayName, userName) {
    if (notificationActive) return;

    if (navigator.share) {
        navigator.share({
            title: displayName + ' (@' + userName + ') on Whisp',
            url: window.location.origin + '/post/' + postId
        });
    } else {
        navigator.clipboard.writeText(window.location.origin + '/post/' + postId);

        var copyAlert = document.createElement('small');
        copyAlert.className = 'd-inline-flex position-fixed bottom-0 end-0 me-2 mb-2 px-2 py-1 fw-semibold text-light bg-success border border-success rounded-2 fade-in-bottom';
        copyAlert.innerHTML = 'Copied to clipboard';
        document.body.appendChild(copyAlert);
        notificationActive = true;

        setTimeout(function() {
            copyAlert.classList.add('fade-out-bottom');
            setTimeout(function() {
                copyAlert.remove();
                notificationActive = false;
            }, 300);
        }, 2000);
    }
}

function deletePost(postId) {
    setButtonLoadingState(['delete-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/delete-post.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['delete-button'], false, false);
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, false);
            }
        }
    }
    xhr.send('post_id=' + postId);
}