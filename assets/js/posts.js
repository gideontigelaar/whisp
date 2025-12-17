var attachments = [];
var postCooldown = 0;

document.addEventListener('DOMContentLoaded', function() {
    var postContent = document.getElementById('post-content');
    var attachmentUrl = document.getElementById('attachment-url');

    if (postContent) {
        postContent.addEventListener('input', updatePostButton);
        startPostCooldown(parseInt(document.getElementById('post-button').getAttribute('data-cooldown')) || 0);
    }

    if (attachmentUrl) {
        attachmentUrl.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                addAttachment();
            }
        });
        attachmentUrl.addEventListener('input', function() {
            attachmentUrl.classList.remove('is-invalid');
        });
        disableButtonWhenEmpty('add-attachment-button', ['attachment-url']);
    }
});

function updatePostButton() {
    var postButton = document.getElementById('post-button');
    var postContent = document.getElementById('post-content');

    if (postButton && postContent && !postButton.querySelector('#loading')) {
        postButton.disabled = postCooldown > 0 || (postContent.value.trim() === '' && attachments.length === 0);
    }
}

function startPostCooldown(seconds) {
    var postButton = document.getElementById('post-button');
    var buttonText = postButton.getAttribute('data-original-text') || postButton.textContent;
    postButton.setAttribute('data-original-text', buttonText);
    postCooldown = seconds;

    var updateCountdown = function() {
        postButton.textContent = postCooldown > 0 ? buttonText + ' (' + postCooldown + 's)' : buttonText;
        updatePostButton();

        if (postCooldown > 0) {
            postCooldown--;
            setTimeout(updateCountdown, 1000);
        }
    };

    updateCountdown();
}

function toggleAttachmentField() {
    var attachmentField = document.getElementById('attachment-field');
    var show = !attachmentField.classList.contains('show');

    attachmentField.classList.toggle('show', show);
    attachmentField.inert = !show;

    if (show) {
        document.getElementById('attachment-url').focus();
    }
}

function addAttachment() {
    var attachmentUrl = document.getElementById('attachment-url');
    var url = attachmentUrl.value.trim();

    if (url === '' || attachments.length >= 4) {
        return;
    }

    try {
        var parsedUrl = new URL(url);
        if (!['http:', 'https:'].includes(parsedUrl.protocol) || attachments.includes(url)) {
            throw new Error();
        }
    } catch (error) {
        attachmentUrl.classList.add('is-invalid');
        return;
    }

    attachments.push(url);
    attachmentUrl.value = '';
    attachmentUrl.dispatchEvent(new Event('input'));
    renderAttachmentPreviews();
}

function removeAttachment(url) {
    attachments = attachments.filter(attachment => attachment !== url);
    renderAttachmentPreviews();
}

function renderAttachmentPreviews() {
    var attachmentPreviews = document.getElementById('attachment-previews');
    var attachmentUrl = document.getElementById('attachment-url');
    attachmentPreviews.innerHTML = '';

    attachments.forEach(url => {
        var preview = document.createElement('div');
        preview.className = 'position-relative';

        var image = document.createElement('img');
        image.className = 'rounded-3 border attachment-preview';
        image.alt = 'Attachment';
        image.src = url;
        image.onerror = function() {
            removeAttachment(url);
            attachmentUrl.value = url;
            attachmentUrl.dispatchEvent(new Event('input'));
            attachmentUrl.classList.add('is-invalid');
        };

        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-dark rounded-circle position-absolute top-0 end-0 m-1 p-0 d-flex align-items-center justify-content-center attachment-remove';
        removeButton.setAttribute('aria-label', 'Remove attachment');
        removeButton.innerHTML = '<i class="ph ph-x"></i>';
        removeButton.onclick = function() {
            removeAttachment(url);
        };

        preview.append(image, removeButton);
        attachmentPreviews.appendChild(preview);
    });

    attachmentPreviews.classList.toggle('d-none', attachments.length === 0);
    attachmentPreviews.classList.toggle('d-flex', attachments.length > 0);
    attachmentUrl.disabled = attachments.length >= 4;
    attachmentUrl.placeholder = attachments.length >= 4 ? 'You can attach up to 4 images' : 'Paste an image URL';
    updatePostButton();
}

function createPost(replyToPostId) {
    var content = document.getElementById('post-content').value;
    setButtonLoadingState(['post-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/create-post.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['post-button'], false, false);
                var response = JSON.parse(xhr.responseText);

                if (response.cooldown) {
                    startPostCooldown(response.cooldown);
                } else {
                    showError(response.error, true, 'post-button');
                }
            }
        }
    }
    xhr.send('content=' + encodeURIComponent(content) + '&attachments=' + encodeURIComponent(JSON.stringify(attachments)) + (replyToPostId ? '&reply_to_post_id=' + replyToPostId : ''));
}

var loadedPosts = [];
var currentUrl = window.location.pathname;
var loading = false;

function loadPosts() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../../includes/load-posts.php?loaded_posts=' + encodeURIComponent(JSON.stringify(loadedPosts)) + '&current_url=' + encodeURIComponent(currentUrl), true);
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
    xhr.open('POST', '../../api/like-post.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('post_id=' + postId);
}

let notificationActive = false;

function sharePost(postId) {
    if (notificationActive) return;

    var post = document.getElementById('post-' + postId);
    var displayName = post.getAttribute('data-display-name');
    var userName = post.getAttribute('data-username');

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
    setButtonLoadingState(['delete-post-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../api/delete-post.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['delete-post-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'delete-post-button');
            }
        }
    }
    xhr.send('post_id=' + postId);
}